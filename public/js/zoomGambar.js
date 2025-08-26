const zoomGambarDiv = document.querySelector(".zoomGambar");
const zoomGambarImg = document.querySelector(".zoomGambar img");

function perbesarGambar(element) {
    const gambar = element.querySelector("img");

    const gambarURL = gambar.src;

    zoomGambarImg.src = gambarURL;

    zoomGambarDiv.style.display = "block";
}

function sembunyikanGambar() {
    zoomGambarDiv.style.display = "none";
    zoomGambarImg.src = "";
}
