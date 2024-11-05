function copyLink() {
    // Ambil elemen input
    var linkInput = document.getElementById("linkInput");

    // Pilih teks di input
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // Untuk mobile

    // Salin teks ke clipboard
    document.execCommand("copy");

    // Tampilkan pesan berhasil disalin
    var copyMessage = document.getElementById("copyMessage");
    copyMessage.style.display = "block";

    // Sembunyikan pesan setelah beberapa detik
    setTimeout(function() {
        copyMessage.style.display = "none";
    }, 2000);
}