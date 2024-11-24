@props([
  'status' => 'success',
])

<span {{ $attributes->class([
  'badge text-white',
  'bg-blue' => $status == 'menunggu',
  'bg-cyan' => $status == 'diproses',
  'bg-green' => $status == 'selesai',
  'bg-red' => $status == 'ditolak'
  ]) }}>{{ strtoupper($status) }}</span>
