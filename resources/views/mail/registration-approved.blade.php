<x-mail::message>
  # Halo {{ $name }},

  Kami dengan senang hati memberitahukan bahwa registrasi Anda sebagai {{ $type }} telah **DITERIMA**. Anda kini dapat login dan menggunakan fitur-fitur yang tersedia di
  sistem HRIS Mafindo.

  Silakan klik tombol di bawah ini untuk masuk ke akun Anda:

  <x-mail::button :url="url('login')">
    Masuk ke Akun
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a>. Terima kasih atas perhatian dan
  kerjasamanya.
</x-mail::message>
