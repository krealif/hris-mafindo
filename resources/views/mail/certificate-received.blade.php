<x-mail::message>
  # Halo {{ $name }},

  Kami ingin menginformasikan bahwa sertifikat Anda untuk kegiatan **"{{ $eventName }}"** kini telah terbit. Terima kasih atas partisipasi Anda dalam kegiatan ini. Anda dapat
  mengunduh sertifikat melalui tautan di bawah ini.

  <x-mail::button :url="url(Storage::url($certificateUrl))">
    Lihat Sertifikat
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a>. Terima kasih atas perhatian dan kerjasamanya.

</x-mail::message>
