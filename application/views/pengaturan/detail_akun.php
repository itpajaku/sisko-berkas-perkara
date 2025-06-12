<?php

use App\Libraries\Hashid;
use App\Libraries\AuthData;

$group_id = Hashid::encode($selected_group->group_id);
?>
<div class="card">
  <div class="card-header text-bg-primary">
    <h5 class="mb-0 text-white">Form with view only</h5>
    <input type="hidden" id="hidden-selected-akun-id" name="selected_akun_id" value="<?= Hashid::encode($selected_group->group_id) ?>">
  </div>
  <div class="form-horizontal">
    <div class="form-body">
      <div class="card-body">
        <h5 class="card-title mb-0">Person Info</h5>
      </div>
      <hr class="m-0" />
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="form-label text-end col-md-4">User Group :</label>
              <div class="col-md-8">
                <p><?= $selected_group->group->name ?></p>
              </div>
            </div>
          </div>
          <!--/span-->
          <div class="col-md-6">
            <div class="form-group row">
              <label class="form-label text-end col-md-4">Description :</label>
              <div class="col-md-8">
                <p><?= $selected_group->group->description ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="form-label text-end col-md-4">Total Akun :</label>
              <div class="col-md-8">
                <p><?= $selected_group->group->users->count() . " " ?> Akun</p>
              </div>
            </div>
          </div>
          <!--/span-->
          <div class="col-md-6">
            <div class="form-group row">
              <label class="form-label text-end col-md-4">Atasan :</label>
              <div class="col-md-8">
                <p><?= $selected_group->group->parentGroup->name ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="3"> Tabel Akses Group Menu</th>
                  <th class="text-end">
                    <button
                      class="btn btn-primary btn-sm"
                      hx-get="<?= site_url("pengaturan/akun/" . Hashid::encode($selected_group->group_id) . "/form") ?>"
                      hx-target="#modalTambahAkses>.modal-dialog>.modal-content>.modal-body"
                      hx-swap="innerHTML"
                      hx-headers='{"Hx-Request-Component":true}'
                      data-bs-toggle="modal"
                      data-bs-target="#modalTambahAkses">

                      <i class="ti ti-plus"></i>
                      Tambah Akses
                    </button>
                    <button
                      hx-get="<?= base_url('/pengaturan/akun/' . $group_id . '/form_menu') ?>"
                      hx-target="#modalAddAccessMenu>.modal-dialog>.modal-content>.modal-body"
                      hx-headers='{"Hx-Request-Component":true}'
                      data-bs-toggle="modal"
                      data-bs-target="#modalAddAccessMenu"
                      class="btn btn-success btn-sm">
                      <i class="ti ti-plus"></i>
                      Tambah Akses Menu
                    </button>
                  </th>
                </tr>
                <tr class="bg-info-subtle text-center">
                  <th>No</th>
                  <th>Nama Section</th>
                  <th>Total Menu</th>
                  <th>Nama Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($selected_group->access_menu_section as $n => $access_section) {
                ?>
                  <tr>
                    <td><?= ++$n ?></td>
                    <td><?= $access_section->menu_section->header ?></td>
                    <td>
                      <ol>
                        <?php foreach ($allowed_menu->where('menu.section_id', $access_section->menu_section_id)->all() as $item) { ?>
                          <li>
                            <i class="<?= $item->menu->icon ?>"></i>
                            <?= $item->menu->title ?>
                          </li>
                        <?php } ?>
                      </ol>
                    </td>
                    <td>
                      <button
                        class="btn btn-danger btn-sm"
                        hx-delete="<?= site_url('pengaturan/akun/' . Hashid::encode($selected_group->group_id) . '/menu_section/' . Hashid::encode($access_section->menu_section_id)) ?>"
                        hx-swap="none"
                        hx-confirm="Apakah anda yakin ingin menghapus akses ini?">
                        <i class="ti ti-x"></i>
                        Hapus
                      </button>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <div class="card-body">
          <div class="row">
            <div class="col-md-offset-3 d-flex justify-content-start">
              <button
                data-bs-toggle="popover"
                data-bs-title="Popover title"
                type="button"
                disabled
                class="btn btn-primary">
                <i class="ti ti-edit fs-5"></i>
                Edit
              </button>
              <button
                hx-delete="<?= site_url('pengaturan/akun/' . Hashid::encode($selected_group->id)) ?>"
                hx-swap="none"
                hx-confirm="Apakah anda yakin ingin menghapus akun ini?"
                type="button"
                class="btn bg-danger-subtle text-danger ms-6">
                <i class="ti ti-trash"></i>
                Hapus Akun
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>