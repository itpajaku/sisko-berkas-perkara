<form
    hx-post="<?= base_url("/berkas_permohonan") ?>"
    hx-target="#submit-result"
    hx-on::before-request="$('#btn-submit').attr('disabled', true).html('<i class=\'ti ti-loader ti-pulse\'></i> Mohon Tunggu...')"
    hx-on::after-request="$('#btn-submit').attr('disabled', false).html('<i class=\'ti ti-device-floppy\'></i> Simpan')"
    class="form-horizontal r-separator">
    <input type="hidden" name="perkara_id" value="<?= App\Libraries\Hashid::encode($berkas->perkara_id)  ?>" />
    <input type="hidden" name="nomor_perkara" value="<?= $berkas->nomor_perkara ?>" />
    <div class="form-group p-3 ">
        <div class="row">
            <label for="inputJenisPerkara" class="col-sm-3 text-end  col-form-label">Jenis Perkara</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input
                        type="text"
                        name="jenis_perkara"
                        class="form-control"
                        id="inputJenisPerkara"
                        value="<?= $berkas->jenis_perkara_nama ?>"
                        placeholder="First Name Here" />
                    <span class="input-group-text">
                        <i class="ti ti-tag"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3">
        <div class="row">
            <label for="inputTanggalPendaftaran" class="col-sm-3 text-end  col-form-label">Tanggal Pendaftaran</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input
                        type="text"
                        value="<?= $berkas->tanggal_pendaftaran ?>"
                        class="form-control"
                        name="tanggal_pendaftaran"
                        id="inputTanggalPendaftaran"
                        placeholder="Last Name Here" />
                    <span class="input-group-text">
                        <i class="ti ti-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3">
        <div class="row">
            <label for="inputParaPihak" class="col-sm-3 text-end  col-form-label">Para Pihak</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input
                        type="text"
                        value="<?= $berkas->pihak1_text ?>, <?= $berkas->pihak2_text ?>"
                        class="form-control"
                        name="para_pihak"
                        id="inputParaPihak"
                        placeholder="Last Name Here" />
                    <span class="input-group-text">
                        <i class="ti ti-user"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$penetapan) { ?>
        <div class="form-group px-3">
            <?= $this->load->view("components/exception_alert", ["message" => "Perkara ini belum ditetapkan"], true) ?>
        </div>
    <?php } ?>
    <div class="form-group p-3 text-bg-light">
        <div class="row">
            <label for="inputKetuaMajelis" class="col-sm-3 text-end  col-form-label">Majelis Hakim</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="majelis_hakim" class="form-control" id="inputKetuaMajelis" placeholder="Isi disini" value="<?= $penetapan->majelis_hakim_nama ?? null ?>" />
                    <div class="input-group-text">
                        <i class="ti ti-user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3 text-bg-light">
        <div class="row">
            <label for="inputPaniteraPenggannti" class="col-sm-3 text-end  col-form-label">Panitera</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="panitera" class="form-control" id="inputPaniteraPenggannti" placeholder="Isi disini" value="<?= str_replace("Panitera Pengganti: ", "", $penetapan->panitera_pengganti_text ?? "")  ?>" />
                    <div class="input-group-text">
                        <ti class="ti ti-user"></ti>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3 text-bg-light">
        <div class="row">
            <label for="inputJurusita" class="col-sm-3 text-end  col-form-label">Jurusita</label>
            <div class="col-sm-9">
                <div class="input-group">

                    <input
                        type="text"
                        name="jurusita"
                        class="form-control"
                        id="inputJurusita"
                        placeholder="Isi disini"
                        value="<?= $berkas->jurusita ?>" />
                    <div class="input-group-text">
                        <ti class="ti-user ti"></ti>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3">
        <div class="row">
            <label for="inputTanggalPutusan" class="col-sm-3 text-end  col-form-label">Tanggal Putusan</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text"
                        name="tanggal_putusan"
                        class="form-control form-control-datepicker"
                        id="inputTanggalPutusan"
                        placeholder="Isi disini"
                        value="<?= $berkas->tanggal_putusan ?? null ?>" />
                    <span class="input-group-text">
                        <i class="ti ti-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group p-3">
        <div class="row">
            <label for="inputKeterangan" class="col-sm-3 text-end  col-form-label">Keterangan</label>
            <div class="col-sm-9">
                <textarea
                    type="text"
                    name="keterangan"
                    class="form-control form-control-datepicker"
                    id="inputKeterangan"
                    placeholder="Isi disini">
                <?= $berkas->keterangan ?>
                </textarea>
            </div>
        </div>
    </div>

    <div class="p-3">
        <div id="submit-result"></div>
        <div class="form-group text-end">
            <button
                id="btn-submit"
                type="submit"
                class="btn btn-primary">
                <i class="ti ti-device-floppy"></i>
                Simpan
            </button>
        </div>
    </div>
</form>

<script>
    $(".form-control-datepicker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });
</script>