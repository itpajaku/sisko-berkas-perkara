<button
    hx-get="<?= base_url("berkas_gugatan/fetch_form_bht/" . App\Libraries\Hashid::encode($berkas->id)) ?>"
    hx-target="#form-set-bht"
    data-bs-toggle="modal"
    data-bs-target="#modalId"
    class="btn btn-sm btn-success">
    <i class="ti ti-plus"></i>
    Tambah BHT
</button>