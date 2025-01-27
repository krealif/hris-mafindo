<x-mail::message>
  # Halo {{ $name }},

  Kami memberitahukan bahwa Anda telah menerima surat **"{{ $title }}"** yang diajukan oleh **{{ $sender }}**.

  Silakan klik tombol di bawah ini untuk melihat dan mengunduh surat tersebut.

  <x-mail::button :url="route('surat.show', $id)">
    Lihat Surat
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a>. Terima kasih atas perhatian dan kerjasamanya.
</x-mail::message>
