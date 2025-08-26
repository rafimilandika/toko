$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    const isi_search = $(".search input");
    const tabelContainer = $(".tabel");
    isi_search.on("keyup", function () {
        const search = $(this).val();
        $.ajax({
            url: "/search",
            type: "POST",
            data: {
                search: search,
            },
            success: function (data) {
                tabelContainer.html(data);
            },
            error: function (xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
            },
        });
    });
});
