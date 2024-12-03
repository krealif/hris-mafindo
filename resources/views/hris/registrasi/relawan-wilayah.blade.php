<script>
  function createDynamicList(key, data) {
    return () => ({
      key,
      rows: data ?? [],

      // Actions
      add() {
        this.rows.push({});
      },
      del(index) {
        this.rows.splice(index, 1);
      },
    });
  }

  document.addEventListener('alpine:init', () => {
    const dataPendidikan = {{ Js::from(old('pendidikan', $detail?->pendidikan)) }}
    Alpine.data('pendidikan', createDynamicList('pendidikan', dataPendidikan))




  })
</script>
