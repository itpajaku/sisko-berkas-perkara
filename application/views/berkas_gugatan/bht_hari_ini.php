<div class="container-fluid" style="max-width: max-content;">
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Berkas Gugatan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="javascript:void(0)">Perkara BHT Hari Ini</a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="/assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-shadow bg-info-subtle">
        <div class="card-body">
            <?= $this->session->flashdata("success_alert") ?>
            <?= $this->session->flashdata("error_alert") ?>
            <h4 class="card-title">Tabel Perkara</h4>
            <p class="card-subtitle mb-3">
                Perhitungan BHT hanya dilakukan setelah berkas perkara diresgiter dan ditentukan tanggal PBT nya
            </p>
            <div class="table-responsive">
                <table id="table-bht" class="table table-hover table-striped table-bordered text-nowrap align-middle">
                    <thead class="bg-white">
                        <tr>
                            <th>No</th>
                            <th>Perkara</th>
                            <th>Pendaftaran</th>
                            <th>Putusan</th>
                            <th>PBT</th>
                            <th>Selisih</th>
                            <th>Majelis</th>
                            <th>Panitera</th>
                            <th>Jurusita</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

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
    data-bs-backdrop="static"
    data-bs-keyboard="false"

    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div
        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
        role="document">
        <div class="modal-content" id="form-set-bht">
            <div class="text-center">
                <h5>Mohon Tunggu ...</h5>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener("load", () => {
        $("#table-bht").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url("berkas_gugatan/bht_datatable") ?>",
                method: "post"
            },
            ordering: false,
            columns: [{
                    "data": "no"
                },
                {
                    "data": "nomor_perkara"
                },
                {
                    "data": "tanggal_pendaftaran"
                },
                {
                    "data": "tanggal_putusan"
                },
                {
                    "data": "tanggal_pbt"
                },
                {
                    "data": "selisih"
                },
                {
                    "data": "majelis_hakim"
                },
                {
                    "data": "panitera"
                },
                {
                    "data": "jurusita"
                },
                {
                    "data": "aksi"
                }
            ],
            drawCallback: () => {
                htmx.process('#table-bht');
            }
        })
    })
</script>