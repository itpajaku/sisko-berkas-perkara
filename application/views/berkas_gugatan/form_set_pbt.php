<form
  hx-patch="<?= base_url("/berkas_gugatan/" . App\Libraries\Hashid::encode($berkas->id)) ?>"
  hx-target="#pbt-picker-result"
  hx-on::before-request="$('#buttonSimpanPBT').attr('disabled', true).html('Menyimpan...')"
  hx-on::after-request="$('#buttonSimpanPBT').attr('disabled', false).html('Simpan')">
  <div class="modal-body">
    <div class="m-2">
      <div class="input-group">
        <div class="input-group-text">
          <input type="text" id="datepicker" class="form-control form-block" name="tanggal_pbt" placeholder="Tanggal PBT" required>
          <div class="ti ti-calendar"></div>
        </div>
      </div>
      <div id="pbt-picker-result"></div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
    <button type="submit" id="buttonSimpanPBT" class="btn btn-primary">Simpan</button>
  </div>
</form>

<script>
  $("#datepicker").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
</script>