<x-mail::message>
  # Halo {{ $name }},

  Kami telah memeriksa permohonan surat **"{{ $title }}"** Anda dan menemukan bahwa beberapa informasi perlu direvisi agar sesuai dengan ketentuan kami.

  <x-mail::panel>
    **Alasan Revisi**
    <br>
    {{ $message }}
  </x-mail::panel>

  Untuk melanjutkan proses permohonan surat, silakan klik tombol di bawah ini untuk memperbaiki permohonan. Kami mohon untuk segera melakukan pembaruan agar proses permohonan surat
  Anda dapat segera diproses lebih lanjut.

  <x-mail::button :url="route('surat.show', $id)">
    Perbaiki Permohonan
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a> Terima kasih atas perhatian dan
  kerjasamanya.
</x-mail::message>
