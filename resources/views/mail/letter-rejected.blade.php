<x-mail::message>
  # Halo {{ $name }},

  Kami telah memeriksa permohonan surat **"{{ $title }}"** Anda dan dengan sangat menyesal memberitahukan bahwa permohonan Anda **DITOLAK**.

  <x-mail::panel>
    **Alasan Penolakan**
    <br>
    {{ $message }}
  </x-mail::panel>

  <x-mail::button :url="route('surat.show', $id)">
    Tinjau Permohonan
  </x-mail::button>

  Jika Anda mengalami kendala atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami di alamat email <a
    href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a> Terima kasih atas perhatian dan
  kerjasamanya.
</x-mail::message>
