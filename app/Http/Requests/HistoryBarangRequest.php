<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoryBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:5|max:255'
            'gudang_id' => 'required|exists:App\Models\Gudang,id',
            'jenisbarang_id' => 'required|exists:App\Models\JenisBarang,id',
            'jumlah' => 'required|integer|min:1',
            // 'tipe' => 'required|in:keluar,masuk',
            // 'prioritas' => [
            //     'required_if:tipe,masuk',
            //     'nullable',
            //     'in:tinggi,sedang,rendah',
            // ],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
