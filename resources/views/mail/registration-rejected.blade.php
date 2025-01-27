<x-mail::message>
  # Halo {{ $name }},

  Kami dengan hormat ingin memberitahukan bahwa, setelah mempertimbangkan semua persyaratan yang ada, registrasi Anda sebagai {{ $type }} terpaksa harus kami **TOLAK**.
  Kami memahami bahwa keputusan ini mungkin mengecewakan, dan kami sangat menghargai minat serta usaha yang telah Anda tunjukkan dalam proses registrasi ini.

  <x-mail::panel>
    **Alasan Penolakan:**
    <br>
    {{ $message }}
  </x-mail::panel>

  Jika Anda memiliki pertanyaan atau membutuhkan klarifikasi lebih lanjut, Anda dapat menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a>. Terima kasih atas perhatian dan
  kerjasamanya.
</x-mail::message>
