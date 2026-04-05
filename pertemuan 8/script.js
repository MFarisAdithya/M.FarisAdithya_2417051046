const tombol = document.getElementById("tombol");
tombol.onclick = function () {
    alert("Tombol telah diklik!");
};

// const form = document.querySelector("form");

// form.addEventListener("submit", function (event) {
//     event.preventDefault();
//     const name = document.getElementById("name").value;
//     alert("Nama yang dimasukkan: " + name);
// });

// function ubah() {
//     document.getElementById("judul").textContent = "Judul telah diubah";

//     document.getElementById("paragraf").innerHTML =
//         "Paragraf ini telah diubah menggunakan <strong>innerHTML</strong>";
// }

// function ubahStyle() {
//     const element = document.getElementById("judul");
//     element.style.color = "red";
//     element.style.fontSize = "24px";
// }

function cekAngka(){
    let x = document.getElementById("angka").value;
    let hasil;

    if (isNaN(x)|| x < 1 || x > 10) {
        hasil = "Input tidak valid. Masukkan angka antara 1 dan 10.";
    } else {
        hasil = "Input valid. Angka yang dimasukkan: " + x;
    }
    document.getElementById("hasil").textContent = hasil;


}