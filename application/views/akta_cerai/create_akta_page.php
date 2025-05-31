<div class="container-lg">
    <?= App\Libraries\Templ::component("layouts/page_header", [
        "page_name" => "Berkas Akta Cerai",
        "breadcrumbs" => [
            [
                'url' => '/akta_cerai',
                'name' => "Berkas Akta"
            ],
            [
                'url' => '/akta_cerai/create',
                'name' => "Tambah Data Baru"
            ]
        ]
    ]) ?>

    <div class="card border shadow">
        <div class="card-body">
            <div class="card-title">Form Berkas Akta Cerai</div>
            <form
                hx-post="/akta_cerai/fetch_form"
                hx-target="#form-berkas-akta"
                class="form-horizontal r-separator">
                <div class="form-group p-3 mb-3 text-bg-light">
                    <div class="row">
                        <label for="inputNomorPerkara" class="col-sm-3 text-end  col-form-label">Nomor Perkara</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                class="form-control"
                                id="inputNomorPerkara"
                                placeholder="123/Pdt.x...."
                                name="nomor_perkara" />
                            <button type="submit" id="btn-cari-perkara" class="btn btn-primary mt-3">
                                <i class="ti ti-search"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="form-berkas-akta"></div>
        </div>
    </div>
</div>

<script>
    window, addEventListener("load", function() {
        const nomorPerkaraList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '/perkara/suggest?query=%QUERY',
                wildcard: '%QUERY',
            }
        });

        $('#inputNomorPerkara').typeahead({
            hint: false,
            highlight: true
        }, {
            name: 'perkara',
            display: 'nomor_perkara',
            source: nomorPerkaraList,
        });
    })
</script>