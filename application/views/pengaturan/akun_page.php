<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Pengaturan", "url" => "/pengaturan"],
      ["name" => "Akun", "url" => "/pengaturan/akun"],
    ],
    "page_name" => "Pengaturan",
  ], true) ?>
  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success " id="add-akun-btn">
      <i class="ti ti-plus"></i>
      Tambah Akun
    </button>
  </div>
  <div class=" card">
    <div class="card-header">
      Sesuaikan Akun SIPP yang akan digunakan
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="akun-table">
          <thead class="bg-primary-subtle">
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Desc</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            use App\Libraries\Hashid;

            foreach ($user_groups as $n => $user) { ?>
              <tr>
                <td><?= $n + 1 ?></td>
                <td><?= $user->group->name ?></td>
                <td><?= $user->group->description ?></td>
                <td>
                  <button
                    hx-get="<?= base_url("/pengaturan/akun/" . Hashid::encode($user->id)) ?>"
                    hx-headers='{"HX-Request-Component": true}'
                    hx-target="#detail-content"

                    data-bs-toggle="modal"
                    data-bs-target="#modalId"
                    class="btn bg-warning-subtle btn-sm"
                    data-id="<?= $user->groupid ?>">
                    <i class="ti ti-eye"></i> Detail
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<div
  class="modal fade"
  id="modalId"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Detail Akun
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body" id="detail-content">
        <h3 class="tetx-center">Mohon Tunggu ...</h3>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-dark"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  var modalId = document.getElementById('modalId');

  modalId.addEventListener('show.bs.modal', function(event) {
    // Button that triggered the modal
    let button = event.relatedTarget;
    // Extract info from data-bs-* attributes
    let recipient = button.getAttribute('data-bs-whatever');

    // Use above variables to manipulate the DOM
  });
</script>