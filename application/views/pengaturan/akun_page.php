<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Pengaturan", "url" => "/pengaturan"],
      ["name" => "Akun", "url" => "/pengaturan/akun"],
    ],
    "page_name" => "Pengaturan",
  ], true) ?>
  <div class="d-flex justify-content-end mb-3">
    <button
      data-bs-toggle="modal"
      data-bs-target="#modalTambah"
      class="btn btn-success"
      id="add-akun-btn">
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
              <th>Ditambah</th>
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
                    hx-get="<?= base_url("/pengaturan/akun/" . Hashid::encode($user->group_id)) ?>"
                    hx-headers='{"HX-Request-Component": true}'
                    hx-target="#detail-content"
                    hx-trigger="htmx:refresh, click"
                    data-bs-toggle="modal"
                    data-bs-target="#modalId"
                    class="btn bg-warning-subtle btn-sm"
                    data-id="<?= $user->groupid ?>">
                    <i class="ti ti-eye"></i> Detail
                  </button>
                </td>
                <td>
                  <?= $user->created_at->format("d F Y") ?>
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


<div
  class="modal fade"
  id="modalTambah"
  tabindex="-1"
  data-bs-backdrop="static"
  data-bs-keyboard="false"

  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Tambah Akses Akun
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div
          class="alert alert-warning alert-dismissible fade show"
          role="alert">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"></button>
          <i class="ti ti-warning"></i>
          <strong>Perhatian!</strong> Akun SIPP dengan role terpilih akan bisa mengakses aplikasi ini.
        </div>

        <form
          hx-post="<?= base_url('pengaturan/akun') ?>"
          hx-target="#form-add-result">
          <div class=" form-group">
            <label for="groupuserselect">Pilih Grup User Sipp</label>
            <select name="group_id" id="groupuserselect" class="form-control-select form-select">
              <?php foreach ($sys_groups as $n => $sg) { ?>
                <option value="<?= App\Libraries\Hashid::encode($sg->groupid) ?>"><?= $sg->name ?></option>
              <?php }  ?>
            </select>
          </div>
          <div class="d-flex justify-content-end gap-3 my-3">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy"></i>
              Tambah</button>
            <button
              type="button"
              class="btn btn-dark"
              data-bs-dismiss="modal">
              <i class="ti ti-x"></i>
              Close
            </button>
          </div>
        </form>
        <div id="form-add-result"></div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modalTambahAkses"
  tabindex="-1"
  data-bs-keyboard="false"

  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Tambah Akses
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-target="#modalId"
          data-bs-toggle="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Mohon Tunggu ...</h5>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modalAddAccessMenu"
  tabindex="-1"
  data-bs-backdrop="static"
  data-bs-keyboard="false"

  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Tambah Akses ke Menu
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-target="#modalId"
          data-bs-toggle="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Mohon Tunggu ...</h5>
      </div>
    </div>
  </div>
</div>