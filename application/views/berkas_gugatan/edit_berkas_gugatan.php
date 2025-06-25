<?php

use App\Libraries\Hashid;
?>
<div class="container-fluid">
    <?= $this->load->view("layouts/page_header", [
        "breadcrumbs" => [
            ["name" => "Berkas Gugatan", "url" => "/berkas_gugatan"],
            ["name" => "Create Berkas Gugatan", "url" => "/berkas_gugatan/create"]
        ],
        "page_name" => "Berkas Gugatan",
    ], true) ?>

    <div class="card">
        <div class="card-body pb-0">
            <h4 class="card-title">Form Registet Berkas Gugatan</h4>
            <p class="card-subtitle mb-0">
                Form dengan tanda <mark>
                    <code>*</code>
                </mark> wajib diisi.
            </p>
        </div>
        <form
            hx-patch="<?= base_url("/berkas_gugatan/" . Hashid::encode($berkas->id)) ?>"
            hx-target="#submit-result"
            hx-on::before-request="$('#btn-submit').attr('disabled', true).html('<i class=\'ti ti-loader ti-pulse\'></i> Mohon Tunggu...')"
            hx-on::after-request="$('#btn-submit').attr('disabled', false).html('<i class=\'ti ti-device-floppy\'></i> Simpan')"
            class="form-horizontal r-separator">
            <div class="form-group p-3 text-bg-light">
                <div class="row">
                    <label for="inputNomorPerkara" class="col-sm-3 text-end  col-form-label">Nomor Perkara</label>
                    <div class="col-sm-9">
                        <input
                            type="text"
                            class="form-control"
                            id="inputNomorPerkara"
                            placeholder="123/Pdt.x...."
                            name="nomor_perkara"
                            disabled
                            value="<?= $berkas->nomor_perkara ?>" />
                    </div>
                </div>
            </div>
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
                                value="<?= $berkas->jenis_perkara ?>"
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
                                value="<?= $berkas->para_pihak ?>"
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
            <?php if (!$berkas) { ?>
                <div class="form-group px-3">
                    <?= $this->load->view("components/exception_alert", ["message" => "Perkara ini belum ditetapkan"], true) ?>
                </div>
            <?php } ?>
            <div class="form-group p-3 text-bg-light">
                <div class="row">
                    <label for="inputKetuaMajelis" class="col-sm-3 text-end  col-form-label">Majelis Hakim</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" name="majelis_hakim" class="form-control" id="inputKetuaMajelis" placeholder="Isi disini" value="<?= $berkas->majelis_hakim ?? null ?>" />
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
                            <input type="text" name="panitera" class="form-control" id="inputPaniteraPenggannti" placeholder="Isi disini" value="<?= $berkas->panitera  ?>" />
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
            <?php if (!$berkas) { ?>
                <div class="form-group px-3">
                    <?= $this->load->view("components/exception_alert", ["message" => "Perkara ini belum diputus"], true) ?>
                </div>
            <?php } ?>
            <div class="form-group p-3">
                <div class="row">
                    <label for="inputTanggalPutusan" class="col-sm-3 text-end  col-form-label">Tanggal Putusan</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" name="tanggal_putusan" class="form-control form-control-datepicker" id="inputTanggalPutusan" placeholder="Isi disini" value="<?= $berkas->tanggal_putusan ?? null ?>" />
                            <span class="input-group-text">
                                <i class="ti ti-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group p-3">
                <div class="row">
                    <label for="inputTanggalPbt" class="col-sm-3 text-end  col-form-label">Tanggal Pemberitahuan</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input
                                type="text"
                                name="tanggal_pbt"
                                class="form-control form-control-datepicker"
                                id="inputTanggalPbt"
                                value="<?= $berkas->tanggal_pbt ?>"
                                placeholder="Isi disini" />


                            <div class="input-group-text">
                                <i class="ti ti-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group p-3">
                <div class="row">
                    <label for="inputTanggalBht" class="col-sm-3 text-end  col-form-label">Tanggal BHT</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" name="tanggal_bht" class="form-control form-control-datepicker" id="inputTanggalBht" placeholder="Isi disini" value="<?= $berkas->tanggal_bht ?? null ?>" />
                            <div class="input-group-text">
                                <div class="ti ti-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group p-3">
                <div class="row">
                    <label for="inputTanggalTerima" class="col-sm-3 text-end  col-form-label">Tanggal Diterima Meja 3</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" required name="tanggal_terima" class="form-control form-control-datepicker" id="inputTanggalTerima" placeholder="Isi disini" value="<?= $berkas->tanggal_terima  ?>" />
                            <div class="input-group-text">
                                <div class="ti ti-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group p-3">
                <div class="row">
                    <label for="inputKeterangan" class="col-sm-3 text-end  col-form-label">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea type="text" name="keterangan" class="form-control form-control-datepicker" id="inputKeterangan" placeholder="Isi disini"><?= $berkas->keterangan ?></textarea>
                    </div>
                </div>
            </div>

            <div class="p-3">
                <div id="submit-result"></div>
                <div class="form-group text-end">
                    <a
                        type="button"
                        href="<?= base_url("berkas_gugatan") ?>"
                        class="btn bg-danger-subtle text-warning ms-6">
                        <i class="ti ti-arrow-left"></i>
                        Kembali
                    </a>
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
    </div>
</div>

<script>
    window.addEventListener("load", function() {
        $(".form-control-datepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
        })
    })
</script>