@props([
    'title' => 'Title',
    'content' => null,
])

<div class="datagrid-item">
  <div class="datagrid-title fs-4">{{ $title }}</div>
  <div class="datagrid-content">{{ empty($content) ? '-' : $content }}</div>
</div>
