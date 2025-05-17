<div class="container-fluid">
    <?= $this->load->view("layouts/page_header", [
        "breadcrumbs" => [
            ["name" => "Berkas Gugatan", "url" => "/berkas_gugatan"],
            ["name" => "Create Berkas Gugatan", "url" => "/berkas_gugatan/create"]
        ],
        "page_name" => "Berkas Gugatan",
    ], true) ?>
    <div class="card shadow-lg border-lg widget-card bsb-timeline-8">
        <div class="card-body p-4">
            <h5 class="card-title widget-card-title mb-3">Ekspedisi Berkas Nomor <?= $berkas->nomor_perkara ?></h5>
            <div class="container">
                <div class="row justify-content-end">
                    <button
                        data-bs-toggle="modal"
                        data-bs-target="#modalId"
                        class="btn btn-success col-2">
                        <i class="ti ti-plus"></i>
                        Tambah kspedisi
                    </button>
                </div>
            </div>
            <ul class="timeline">
                <?php foreach ($berkas->berkas_ekspedisi as $ekspedisi) { ?>
                    <li class="timeline-item">
                        <div class="timeline-body">
                            <div class="timeline-meta">
                                <span><?= $ekspedisi->save_time->diffForHumans() . " oleh : " . $ekspedisi->created_by ?></span>
                            </div>
                            <div class="timeline-content timeline-indicator">
                                <h5 class="mb-1">Diterima oleh : <?= $ekspedisi->posisi_ekspedisi->posisi ?>.</h5>
                                <h6 class="text-primary"> <?= $ekspedisi->posisi_ekspedisi->keterangan ?></h6>
                                <span class="text-secondary fs-7"> <?= $ekspedisi->save_time->format("d F Y") ?> |
                                    <a
                                        class="text-danger"
                                        href="javascript:void(0)"
                                        hx-delete="<?= base_url("/berkas_gugatan/" . $this->hash->encode($berkas->id) . "/ekspedisi") ?>"
                                        hx-confirm="Data yang dihapus tidak bisa dikembalikan."
                                        hx-vals='<?= json_encode([
                                                        "save_point" => $ekspedisi->save_point,
                                                        "save_time" => $ekspedisi->save_time,
                                                        "berkas_type" => class_basename($berkas)
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
</div>

<div
    class="modal fade"
    id="modalId"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Tambah Ekspedisi Berkas
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form
                hx-post="<?= base_url("/berkas_gugatan/" . $this->hash->encode($berkas->id) . "/ekspedisi") ?>"
                hx-target="#post-result">
                <input type="hidden" name="berkas_type" value="<?= class_basename($berkas) ?>">
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

<script>
    document.addEventListener("htmx:confirm", function(e) {
        if (!e.detail.question) return

        e.preventDefault()

        Swal.fire({
            title: "Apa anda yakin?",
            text: e.detail.question,
            icon: "warning",
            showCancelButton: true,
            // reverseButtons: true,
            confirmButtonText: "Ya, Saya mengerti"
        }).then(function(result) {
            if (result.isConfirmed) {
                e.detail.issueRequest(true);
            }
        })
    })
</script>