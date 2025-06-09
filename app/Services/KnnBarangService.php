<?php

namespace App\Services;

use App\Models\HistoryBarang;
use App\Models\KnnClassification;
use Illuminate\Support\Collection;

class KnnBarangService
{
    protected int $k;
    protected Collection $trainingData;
    protected float $minJumlah;
    protected float $maxJumlah;
    protected float $rangeJumlah;

    public function __construct(int $k = 10)
    {
        $this->setK($k);
        $this->initializeTrainingData();
    }

    public function setK(int $k): self
    {
        if ($k < 1) {
            throw new \Exception("Nilai K harus lebih dari 0");
        }
        $this->k = $k;
        return $this;
    }

    protected function initializeTrainingData(): void
    {
        $this->trainingData = HistoryBarang::whereNotNull('prioritas')->get();

        if ($this->trainingData->isEmpty()) {
            throw new \Exception("Data latih tidak ditemukan.");
        }

        $this->minJumlah = $this->trainingData->min('jumlah');
        $this->maxJumlah = $this->trainingData->max('jumlah');
        $this->rangeJumlah = ($this->maxJumlah - $this->minJumlah) ?: 1;
    }

    protected function normalizeJumlah($value): float
    {
        return ($value - $this->minJumlah) / $this->rangeJumlah;
    }

    protected function convertLokasi($gudangId): int
    {
        return (int) $gudangId; // bisa diganti dengan mapping manual jika perlu
    }

    public function classify(int $gudangId, int $jumlah): string
    {
        $normalizedJumlah = $this->normalizeJumlah($jumlah);
        $lokasiNumerik = $this->convertLokasi($gudangId);

        $distances = $this->trainingData->map(function ($data) use ($normalizedJumlah, $lokasiNumerik) {
            $normJumlah = $this->normalizeJumlah($data->jumlah);
            $lokasiLatih = $this->convertLokasi($data->gudang_id);

            $distance = sqrt(
                pow($normJumlah - $normalizedJumlah, 2) +
                    pow($lokasiLatih - $lokasiNumerik, 2)
            );

            return [
                'distance' => $distance,
                'prioritas' => $data->prioritas,
                'data' => $data
            ];
        });

        $nearest = $distances->sortBy('distance')->take($this->k);

        $result = $nearest->pluck('prioritas')->countBy()->sortDesc()->keys()->first();

        return $result;
    }

    public function classifyWithDetails(int $gudangId, int $jumlah): array
    {
        $prediction = $this->classify($gudangId, $jumlah);
        return [
            'prediction' => $prediction,
            'neighbors' => $this->getKNearest($gudangId, $jumlah)
        ];
    }

    /**
     * @return Collection<int, array{distance: float, prioritas: string, data: HistoryBarang}>
     */
    protected function getKNearest(int $gudangId, int $jumlah): Collection
    {
        $normalizedJumlah = $this->normalizeJumlah($jumlah);
        $lokasiNumerik = $this->convertLokasi($gudangId);

        return $this->trainingData->map(function ($data) use ($normalizedJumlah, $lokasiNumerik) {
            $normJumlah = $this->normalizeJumlah($data->jumlah);
            $lokasiLatih = $this->convertLokasi($data->gudang_id);

            $distance = sqrt(
                pow($normJumlah - $normalizedJumlah, 2) +
                    pow($lokasiLatih - $lokasiNumerik, 2)
            );

            return [
                'distance' => $distance,
                'prioritas' => $data->prioritas,
                'data' => $data
            ];
        })->sortBy('distance')->take($this->k)->values();
    }

    /**
     * Mengembalikan array hasil prediksi dengan neighbors dan prediksi mayoritas
     * @return array{
     *  k_value: int,
     *  neighbors: array<int, array{distance: float, original_data: HistoryBarang, classification: string}>,
     *  prediction: string
     * }
     */
    public function predictWithNeighbors(int $gudangId, int $jumlah): array
    {
        $neighborsRaw = $this->getNearestNeighbors($gudangId, $jumlah, $this->k);

        $neighbors = [];
        foreach ($neighborsRaw as $index => $neighbor) {
            $neighbors[$index] = [
                'distance' => $neighbor['distance'],
                'original_data' => $neighbor['historyBarang'], // objek lengkap
                'classification' => $neighbor['historyBarang']->prioritas,
            ];
        }

        $prediction = $this->getMajorityPriority($neighbors);

        return [
            'k_value' => $this->k,
            'neighbors' => $neighbors,
            'prediction' => $prediction,
        ];
    }

    /**
     * Fungsi ini mengembalikan array tetangga terdekat berdasarkan jarak Euclidean
     * @return array<int, array{historyBarang: HistoryBarang, distance: float}>
     */
    public function getNearestNeighbors(int $gudangId, int $jumlah, int $k): array
    {
        $normalizedJumlah = $this->normalizeJumlah($jumlah);
        $lokasiNumerik = $this->convertLokasi($gudangId);

        $distances = [];

        foreach ($this->trainingData as $data) {
            $normJumlah = $this->normalizeJumlah($data->jumlah);
            $lokasiLatih = $this->convertLokasi($data->gudang_id);

            $distance = sqrt(
                pow($normJumlah - $normalizedJumlah, 2) +
                    pow($lokasiLatih - $lokasiNumerik, 2)
            );

            $distances[] = [
                'historyBarang' => $data,
                'distance' => $distance
            ];
        }

        usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

        return array_slice($distances, 0, $k);
    }

    /**
     * Menghitung prioritas mayoritas berdasarkan neighbors, dengan voting weighted (bisa dikembangkan)
     * @param array<int, array{distance: float, original_data: HistoryBarang, classification: string}> $neighbors
     */
    public function getMajorityPriority(array $neighbors): string
    {
        $votes = ['tinggi' => 0, 'sedang' => 0, 'rendah' => 0];

        foreach ($neighbors as $neighbor) {
            // Ambil prioritas dari key classification
            $prioritas = $neighbor['classification'] ?? null;
            if ($prioritas && isset($votes[$prioritas])) {
                $votes[$prioritas]++;
            }
        }

        arsort($votes);

        return key($votes);
    }

    public function logPrediction(array $inputData, string $prediction, array $knnResult, $barang = null): void
    {
        $knnLog = null;

        if ($barang && $barang->exists) {
            $latestLog = KnnClassification::where('barang_id', $barang->id)
                ->latest()
                ->first();

            if ($latestLog) {
                $latestLog->update([
                    'nilai_k' => $knnResult['k_value'],
                    'neighbors' => $knnResult['neighbors'],
                ]);
                $knnLog = $latestLog;
            } else {
                // Jika tidak ada log sebelumnya, buat yang baru
                $knnLog = KnnClassification::create([
                    'barang_id' => $barang->id,
                    'nilai_k' => $knnResult['k_value'],
                    'neighbors' => $knnResult['neighbors'],
                ]);
            }
        } else {
            // Jika tidak ada barang, buat log tanpa barang_id
            $knnLog = KnnClassification::create([
                'barang_id' => null,
                'nilai_k' => $knnResult['k_value'],
                'neighbors' => $knnResult['neighbors'],
            ]);
        }

        // Set session dengan ID dari log yang baru dibuat atau diupdate
        if ($knnLog) {
            session(['latest_knn_log_id' => $knnLog->id]);
        }
    }
}
