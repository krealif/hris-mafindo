<x-mail::message>
  # Halo {{ $name }},

  Kami telah memeriksa data registrasi Anda dan menemukan bahwa beberapa informasi perlu direvisi agar sesuai dengan ketentuan kami.

  <x-mail::panel>
    **Alasan Revisi**
    <br>
    {{ $message }}
  </x-mail::panel>

  Untuk melanjutkan proses registrasi, silakan klik tombol di bawah ini untuk memperbaiki data Anda. Kami mohon untuk segera melakukan pembaruan pada data tersebut agar proses
  registrasi Anda dapat segera diproses lebih lanjut.

  <x-mail::button :url="url('registrasi/form')">
    Perbaiki Data
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a> Terima kasih atas perhatian dan
  kerjasamanya.
</x-mail::message>
