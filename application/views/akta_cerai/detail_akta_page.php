<div class="container-fluid">
    <?= App\Libraries\Templ::component("layouts/page_header", [
        "breadcrumbs" => [
            ["name" => "Akta Cerai", "url" => "/akta_cerai"],
            ["name" => "Detail Akta Nomor $akta->nomor_akta_cerai", "url" => "/akta_cerai/$akta->hash_id"]
        ],
        "page_name" => "Berkas Gugatan",
    ], true) ?>
    <div class="card shadow border widget-card bsb-timeline-8">
        <div class="card-header text-bg-primary">
            <h5 class="mb-0 text-white">Detail Akta (view only)</h5>
        </div>
        <div class="form-horizontal">
            <div class="form-body">
                <div class="card-body">
                    <h5 class="card-title mb-0">Info Berkas</h5>
                    <?= $this->session->flashdata("success_alert") ?>
                    <?= $this->session->flashdata("error_alert") ?>
                </div>
                <hr class="m-0" />
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Nomor Perkara :</label>
                                <div class="col-md-8">
                                    <p><?= $akta->nomor_perkara ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Majelis :</label>
                                <div class="col-md-8">
                                    <?= $akta->majelis ?>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Jenis perkara :</label>
                                <div class="col-md-8">
                                    <p><?= $akta->jenis_perkara ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Panitera :</label>
                                <div class="col-md-8">
                                    <p><?= $akta->panitera ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Tanggal Daftar :</label>
                                <div class="col-md-8">
                                    <p>
                                        <?= tanggal_indo($akta->tanggal_pendaftaran)  ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Jurusita :</label>
                                <div class="col-md-8">
                                    <p>
                                        <?= $akta->jurusita ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Para Pihak :</label>
                                <div class="col-md-8">
                                    <?= $akta->para_pihak  ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <hr class="m-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Tanggal Putusan :</label>
                                <div class="col-md-8">
                                    <p>
                                        <?= tanggal_indo($akta->tanggal_putus, false)  ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Arsip Berkas :</label>
                                <div class="col-md-8">
                                    <p>
                                        <?= $akta->status ? "Diarsipkan" : "Belum Diarsipkan" ?> |
                                        <?php

                                        use App\Libraries\Hashid;

                                        if ($akta->status) {
                                            echo App\Libraries\Templ::component("akta_cerai/unlink_akta_ke_sipp", [
                                                "berkas" => $akta,
                                            ]);
                                        } else {
                                            echo App\Libraries\Templ::component("akta_cerai/link_akta_ke_sipp", [
                                                "berkas" => $akta,
                                            ]);
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Tanggal Diterima :</label>
                                <div class="col-md-8 text-start"> <?= tanggal_indo($akta->tanggal_diterima) ?></div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Tanggal Arsip :</label>
                                <div class="col-md-8">
                                    <?= tanggal_indo($akta->tanggal_arsip, false)   ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label text-end col-md-4">Keterangan :</label>
                                <div class="col-md-8"> <?= $akta->keterangan ?> </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex mx-5 mb-4 justify-content-between">
                    <div class="d-flex">
                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#modalId"
                            type="submit"
                            class="btn btn-primary">
                            <i class="ti ti-edit fs-5"></i>
                            Edit
                        </button>
                        <a href="<?= base_url("/akta_cerai/register") ?>" type="button" class="btn bg-warning-subtle text-danger ms-6">
                            <i class="ti ti-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                    <button
                        hx-delete="<?= base_url("/akta_cerai/$akta->hash_id") ?>"
                        hx-confirm="Apakah anda yakin ingin menghapus berkas ini? Data yang dihapus tidak bisa dikembalikan."
                        hx-swap="none"
                        type="button"
                        class="btn btn-danger">
                        <i class="ti ti-trash fs-5"></i>
                        Hapus
                    </button>
                </div>
                <hr class="m-0" />
                <div class="card-body py-4">
                    <h5 class="card-title widget-card-title mb-3">Ekspedisi Berkas Nomor <?= $akta->nomor_perkara ?></h5>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col">
                            <ul class="timeline">
                                <?php foreach ($akta->berkas_ekspedisi as $ekspedisi) { ?>
                                    <li class="timeline-item">
                                        <div class="timeline-body">
                                            <div class="timeline-meta">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex">
                                                        <?php if ($ekspedisi->status) { ?>
                                                            <span class="badge bg-success me-2">
                                                                <i class="ti ti-map-pin"></i>
                                                                Posisi Sekarang
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="badge bg-warning me-2">
                                                                <i class="ti ti-flag"></i>
                                                                Posisi Lalu
                                                            </span>
                                                        <?php } ?>
                                                        <span><?= $ekspedisi->save_time->diffForHumans()   ?></span>
                                                    </div>
                                                    <span class="text-end">Oleh :<?= $ekspedisi->created_by ?></span>
                                                </div>
                                            </div>
                                            <div class="timeline-content timeline-indicator">
                                                <h5 class="mb-1">Diterima oleh : <?= $ekspedisi->posisi_ekspedisi->posisi ?>.</h5>
                                                <h6 class="text-primary"> <?= $ekspedisi->posisi_ekspedisi->keterangan ?></h6>
                                                <span class="text-secondary fs-7"> <?= $ekspedisi->save_time->format("d F Y") ?> |
                                                    <a
                                                        class="text-danger"
                                                        href="javascript:void(0)"
                                                        hx-delete="<?= base_url("/berkas/" . Hashid::encode($akta->id) . "/ekspedisi") ?>"
                                                        hx-confirm="Data yang dihapus tidak bisa dikembalikan."
                                                        hx-vals='<?= json_encode([
                                                                        "save_point" => $ekspedisi->save_point,
                                                                        "save_time" => $ekspedisi->save_time->format("Y-m-d H:i:s"),
                                                                        "berkas_type" => class_basename($akta)
                                                                    ]) ?>'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    <button
                        data-bs-toggle="modal"
                        data-bs-target="#modalEkspedisi"
                        class="btn btn-success ms-2">
                        <i class="ti ti-plus"></i>
                        Tambah kspedisi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div
    class="modal fade"
    id="modalId"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Form Edit Akta
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form
                        hx-put="<?= base_url("/akta_cerai/$akta->hash_id") ?>"
                        hx-target="#submit-result"
                        hx-on::before-request="$('#btn-submit').attr('disabled', true).html('<i class=\'ti ti-loader ti-pulse\'></i> Mohon Tunggu...')"
                        hx-on::after-request="$('#btn-submit').attr('disabled', false).html('<i class=\'ti ti-device-floppy\'></i> Simpan')"
                        class="form-horizontal r-separator">
                        <input type="hidden" name="perkara_id" value="<?= App\Libraries\Hashid::encode($akta->perkara_id)  ?>" />
                        <input type="hidden" name="nomor_perkara" value="<?= $akta->nomor_perkara ?>" />
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
                                            value="<?= $akta->jenis_perkara ?>"
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
                                            value="<?= $akta->tanggal_pendaftaran ?>"
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
                                            value="<?= $akta->para_pihak ?>"
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
                        <div class="form-group p-3 text-bg-light">
                            <div class="row">
                                <label for="inputKetuaMajelis" class="col-sm-3 text-end  col-form-label">Majelis Hakim</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text"
                                            name="majelis_hakim"
                                            class="form-control"
                                            id="inputKetuaMajelis"
                                            placeholder="Isi disini"
                                            value="<?= $akta->majelis ?>" />
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
                                        <input type="text"
                                            name="panitera"
                                            class="form-control"
                                            id="inputPaniteraPenggannti"
                                            placeholder="Isi disini" value="<?= $akta->panitera  ?>" />
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
                                            value="<?= $akta->jurusita ?>" />
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
                                            value="<?= $akta->tanggal_putus ?? null ?>" />
                                        <span class="input-group-text">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?= App\Libraries\Templ::component("akta_cerai/input_akta", [
                            "form" => [
                                "aditional_attribute" => "required",
                                "id" => "inputTanggalBht",
                                "name" => "tanggal_bht",
                                "label" => "Tanggal BHT",
                                "placeholder" => "Tanggal Terbit Akta Cerai",
                                "icon" => "<i class=\"ti ti-calendar\"></i>",
                                "aditional_class" => "form-control-datepicker",
                                "value" => $akta->tanggal_bht ?? null
                            ]
                        ])
                        ?>
                        <?= App\Libraries\Templ::component("akta_cerai/input_akta", [
                            "form" => [
                                "aditional_attribute" => "required",
                                "id" => "inputTanggalPbt",
                                "name" => "tanggal_pbt",
                                "label" => "Tanggal PBT",
                                "placeholder" => "Tanggal Terbit Akta Cerai",
                                "icon" => "<i class=\"ti ti-calendar\"></i>",
                                "aditional_class" => "form-control-datepicker",
                                "value" => $akta->tanggal_pbt ?? null
                            ]
                        ])
                        ?>
                        <?= App\Libraries\Templ::component("akta_cerai/input_akta", [
                            "form" => [
                                "aditional_attribute" => "required",
                                "id" => "inputTanggalAkta",
                                "name" => "tanggal_akta",
                                "label" => "Tanggal Akta Cerai",
                                "placeholder" => "Tanggal Terbit Akta Cerai",
                                "icon" => "<i class=\"ti ti-calendar\"></i>",
                                "aditional_class" => "form-control-datepicker",
                                "value" => $akta->tanggal_akta ?? null
                            ]
                        ])
                        ?>
                        <?= App\Libraries\Templ::component("akta_cerai/input_akta", [
                            "form" => [
                                "aditional_attribute" => "required",
                                "id" => "inputNomorSeriAkta",
                                "name" => "nomor_seri",
                                "label" => "Nomor Seri",
                                "placeholder" => "J 0000",
                                "icon" => "<i class=\"ti ti-id\"></i>",
                                "value" => $akta->nomor_seri ?? null
                            ]
                        ])
                        ?>
                        <?= App\Libraries\Templ::component("akta_cerai/input_akta", [
                            "form" => [
                                "aditional_attribute" => "required",
                                "id" => "inputNomorAkta",
                                "name" => "nomor_akta",
                                "label" => "Nomor Akta Cerai",
                                "placeholder" => "Nomor awal saja",
                                "icon" => "<i class=\"ti ti-id\"></i>",
                                "value" => $akta->nomor_akta ?? null
                            ]
                        ])
                        ?>
                        <div class="form-group p-3">
                            <div class="row">
                                <label for="inputKeterangan" class="col-sm-3 text-end  col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea
                                        type="text"
                                        name="keterangan"
                                        class="form-control form-control-datepicker"
                                        id="inputKeterangan"
                                        placeholder="Isi disini"><?= $akta->keterangan ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group p-3">
                            <div class="row">
                                <label for="inputTangalDiterima" class="col-sm-3 text-end  col-form-label">Tanggal Diterima Meja 3</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text"
                                            name="tanggal_diterima"
                                            class="form-control form-control-datepicker"
                                            id="inputTangalDiterima"
                                            placeholder="Isi disini"
                                            value="<?= $akta->tanggal_diterima ?? null ?>" />
                                        <span class="input-group-text">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group p-3">
                            <div class="row">
                                <label for="selectStatus" class="col-sm-3 text-end  col-form-label">Status Digitalisasi</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <select name="status" id="selectStatus" class="form-select">
                                            <option <?= $akta->status ? "selected" : "" ?> value="1">Sudah</option>
                                            <option <?= !$akta->status ? "selected" : "" ?> value="0">Belum</option>
                                        </select>
                                        <span class="input-group-text">
                                            <i class="ti ti-link"></i>
                                        </span>
                                    </div>
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
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modalEkspedisi"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"

    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div
        class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Tambah Ekspedisi
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form
                    hx-post="<?= base_url("/berkas/" . App\Libraries\Hashid::encode($akta->id) . "/ekspedisi") ?>"
                    hx-target="#post-result">
                    <input type="hidden" name="berkas_type" value="<?= class_basename($akta) ?>">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div id="post-result"></div>
                            <div class="form-group">
                                <label for="select-posisi-ekspedisi">Pilih Penerima Berkas</label>
                                <select required name="posisi_ekspedisi" class="form-select" id="select-posisi-ekspedisi">
                                    <option value="" selected disabled>--- Pilih Disini ---</option>
                                    <?php foreach ($posisi_berkas as $pe) { ?>
                                        <option value="<?= $pe->id ?>"><?= $pe->posisi ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener("load", function() {
        $(".form-control-datepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
        });
    })
</script>