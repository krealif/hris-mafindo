@props([
    'title' => 'Title',
    'content' => null,
])

<div class="datagrid-item">
  <div class="datagrid-title">{{ $title }}</div>
  <div class="datagrid-content">{{ empty($content) ? '-' : $content }}</div>
</div>
