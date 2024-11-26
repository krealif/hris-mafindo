<div class="row">
  <div class="col-12 col-md-4 mb-4 mb-md-0">
    <div class="card">
      <div id="list-example" class="list-group list-group-flush position-sticky top-0">
        <a class="list-group-item list-group-item-action px-3 py-2 active" href="#list-item-1">Item 1</a>
        <a class="list-group-item list-group-item-action px-3 py-2" href="#list-item-2">Item 2</a>
        <a class="list-group-item list-group-item-action px-3 py-2" href="#list-item-3">Item 3</a>
        <a class="list-group-item list-group-item-action px-3 py-2" href="#list-item-4">Item 4</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-8">
    <form method="POST" class="vstack gap-2">
      @csrf
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Informasi Dasar</h2>
        </div>
        <div class="card-body">
          <x-form.input name="gender" type="text" placeholder="gender" required />
          <x-form.input name="pekerjaan" type="text" placeholder="pekerjaan" required />
        </div>
      </div>
      <div class="card shadow position-sticky bottom-0">
        <div class="card-body">
          <button class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
