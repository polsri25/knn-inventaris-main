crud.field("gudang_id")
    .onChange(function (field) {
        let gudangId = field.value;

        // Kosongkan opsi jenis barang sebelumnya
        crud.field("jenisbarang_id").input.innerHTML =
            '<option value="">Pilih jenis barang...</option>';

        if (gudangId) {
            // Ambil data jenis barang dari server
            fetch(`/get-jenis-barang/${gudangId}`)
                .then((response) => response.json())
                .then((data) => {
                    data.forEach((jenis) => {
                        let option = document.createElement("option");
                        option.value = jenis.id;
                        option.textContent = jenis.nama;
                        crud.field("jenisbarang_id").input.appendChild(option);
                    });
                })
                .catch((error) => console.error("Error:", error));
        }
    })
    .change(); // Trigger untuk auto-load saat halaman dimuat
