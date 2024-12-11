@extends('layouts.auth', [
    'title' => 'Masuk',
])

@section('content')
  <div class="alert alert-success" role="alert">
    <h4 class="alert-title h3">Berhasil!</h4>
    <div class="text-body">Akun Anda telah berhasil didaftarkan. Harap tunggu persetujuan dari admin sebelum Anda dapat mengakses akun ini. Anda akan menerima pemberitahuan setelah
      akun Anda disetujui dan diaktifkan.</div>
  </div>
@endsection
