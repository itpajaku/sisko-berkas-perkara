  <div class="modal-content" id="dynamic-modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalTitleId">
        Tambah Ekspedisi Baru
      </h5>
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <form
        hx-target="#dynamic-modal-content"
        hx-swap="outerHTML"
        hx-post="<?= base_url("/pengaturan/ekspedisi/tambah") ?>">
        <input
          type="hidden"
          name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>" />
        <!-- Posisi -->
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label">Posisi</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ti ti-map-pin"></i>
              </span>
              <input
                type="text"
                name="posisi"
                value="<?= set_value('posisi'); ?>"
                class="form-control <?= form_error('posisi') ? 'is-invalid' : ''; ?>"
                placeholder="Masukkan posisi">
              <div class="invalid-feedback">
                <?= form_error('posisi'); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Keterangan -->
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label">Keterangan</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ti ti-note"></i>
              </span>
              <textarea
                name="keterangan"
                class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
                rows="3"
                placeholder="Keterangan posisi"><?= set_value('keterangan'); ?></textarea>
              <div class="invalid-feedback">
                <?= form_error('keterangan'); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label">Status</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ti ti-toggle-right"></i>
              </span>
              <select
                name="status"
                class="form-select <?= form_error('status') ? 'is-invalid' : ''; ?>">
                <option value="">-- Pilih --</option>
                <option value="1" <?= set_select('status', '1'); ?>>Aktif</option>
                <option value="0" <?= set_select('status', '0'); ?>>Nonaktif</option>
              </select>
              <div class="invalid-feedback">
                <?= form_error('status'); ?>
              </div>
            </div>
          </div>
        </div>
        <?= $alert ?? null ?>

        <!-- Action -->
        <div class="row">
          <div class="col-sm-9 offset-sm-3">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy"></i> Simpan
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>