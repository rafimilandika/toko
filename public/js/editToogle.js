// main.js (gabungan dari search.js dan editToogle.js)

$(function () {
    // Ajax Setup
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Variabel dan Fungsi
    const editTabelBtn = $("#edit-tabel-btn");
    const tabelContainer = $(".tabel");

    // Fungsi untuk memperbarui tampilan tabel
    function updateTabelMode() {
        const modeSaatIni = localStorage.getItem("mode_tabel");
        if (modeSaatIni === "edit") {
            $(".edit-kolom").show();
            $(".pilih-kolom").hide();
            $(".edit-kolom-header").show();
            $(".pilih-kolom-header").hide();
            editTabelBtn.text("SELESAI");
            $("a[href='/tambah']").hide();
        } else {
            $(".edit-kolom").hide();
            $(".pilih-kolom").show();
            $(".edit-kolom-header").hide();
            $(".pilih-kolom-header").show();
            editTabelBtn.text("EDIT");
            $("a[href='/tambah']").show();
        }
    }

    // Panggil fungsi saat halaman dimuat
    updateTabelMode();

    // Event Listener untuk Tombol Edit
    $(document).on("click", "#edit-tabel-btn", function () {
        if ($(this).text() === "EDIT") {
            localStorage.setItem("mode_tabel", "edit");
        } else {
            localStorage.setItem("mode_tabel", "pilih");
        }
        updateTabelMode();
    });

    // Event Listener untuk Live Search
    $("#search").on("keyup", function () {
        const search = $(this).val();
        $.ajax({
            url: "/search",
            type: "POST",
            data: {
                search: search,
            },
            success: function (data) {
                tabelContainer.html(data);
                // Panggil fungsi update tabel setelah konten baru dimuat
                updateTabelMode();
            },
            error: function (xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
            },
        });
    });

    // ... (Tambahkan semua event listener lainnya di sini, seperti .pilih-barang, .batal-checkout, dll.)
    // Pastikan semua event listener menggunakan $(document).on()
});
