$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Event listener for the "Pilih" button
    $(document).on("click", ".pilih-barang", function () {
        $(this).hide();
        $(this).siblings(".kuantitas-container").show();
    });

    // Event listener for the "Batal" button
    $(document).on("click", ".batal-checkout", function () {
        // Find the nearest parent card
        const parentCard = $(this).closest(".item-card");

        // Within that card, hide the quantity container
        parentCard.find(".kuantitas-container").hide();

        // And show the "Pilih" button again
        parentCard.find(".pilih-barang").show();
    });

    // Event listener for the "Tambah" button
    $(document).on("click", ".tambah-keranjang", function () {
        const itemCard = $(this).closest(".item-card");
        const barangId = itemCard.data("id");
        const kuantitas = itemCard.find(".kuantitas-input").val();

        // Call the AJAX endpoint
        $.ajax({
            url: "/tambah-ke-keranjang",
            type: "POST",
            data: {
                id: barangId,
                kuantitas: kuantitas,
            },
            success: function (response) {
                $("#total-barang").text(response.total_banyak_barang);
                $("#total-harga").text(
                    new Intl.NumberFormat("id-ID").format(response.total_harga)
                );
            },
            error: function (xhr, status, error) {
                console.error("Gagal menambahkan barang ke keranjang:", error);
            },
        });
    });

    // Event listener for the "Hapus" button (inside the card)
    $(document).on("click", ".batal-checkout", function () {
        const parentCard = $(this).closest(".item-card");
        const barangId = parentCard.data("id");

        // Send AJAX request to remove the item from the session
        $.ajax({
            url: "/hapus-dari-keranjang",
            type: "POST",
            data: {
                id: barangId,
            },
            success: function (response) {
                console.log(response);

                // Update the frontend
                parentCard.find(".kuantitas-container").hide();
                parentCard.find(".pilih-barang").show();

                // Update the total counts in the UI
                $("#total-barang").text(response.total_banyak_barang);
                $("#total-harga").text(
                    new Intl.NumberFormat("id-ID").format(response.total_harga)
                );
            },
            error: function (xhr, status, error) {
                console.error("Gagal membatalkan barang:", error);
            },
        });
    });

    // Event listener for the "Hapus Keranjang" button
    $(document).on("click", ".hapus-keranjang", function () {
        swal({
            title: "Apakah Anda yakin?",
            text: "Ini akan menghapus semua barang dari keranjang!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "/hapus-keranjang",
                    type: "POST",
                    data: {},
                    success: function (response) {
                        swal("Keranjang berhasil dihapus!", {
                            icon: "success",
                        });
                        $("#total-barang").text(response.total_banyak_barang);
                        $("#total-harga").text(
                            new Intl.NumberFormat("id-ID").format(
                                response.total_harga
                            )
                        );
                        $(".kuantitas-container").hide();
                        $(".pilih-barang").show();
                    },
                    error: function (xhr, status, error) {
                        console.error("Gagal menghapus keranjang:", error);
                    },
                });
            }
        });
    });
});
