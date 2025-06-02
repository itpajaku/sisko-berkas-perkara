<form
    hx-patch="<?= base_url("/berkas_gugatan/" . $this->hash->encode($berkas->id)) ?>/sinkron_bht"
    hx-target="#pbt-picker-result"
    hx-on::before-request="$('#buttonSimpanPBT').attr('disabled', true).html('Menyimpan...')"
    hx-on::after-request="$('#buttonSimpanPBT').attr('disabled', false).html('Simpan')">
    <input type="hidden" name="tanggal_pbt" value="<?= $berkas->tanggal_pbt ?>">
    <div class="modal-header">
        <h5 class="modal-title">Set Tanggal BHT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="m-2">
            <div class="input-group">
                <input type="text" id="datepicker" class="form-control form-block" name="tanggal_bht" placeholder="Tanggal BHT" required>
                <div class="input-group-text">
                    <div class="ti ti-calendar"></div>
                </div>
            </div>
            <div class="input-group">
                <div class="form-check py-2">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" checked="true" name="sinkron_sipp">
                    <label class="form-check-label" for="flexCheckChecked">
                        Sinkron BHT ke SIPP
                    </label>
                </div>
                <p class="text-primary">Sinkron BHT ke SIPP akan mengisi tanggal BHT ke perkara <?= $berkas->nomor_perkara ?> secara otomatis</p>
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