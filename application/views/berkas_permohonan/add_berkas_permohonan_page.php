<div class="container-fluid">
  <?= $this->load->view("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Berkas Gugatan", "url" => "/berkas_permohonan"],
      ["name" => "Create Berkas Gugatan", "url" => "/berkas_permohonan/create"]
    ],
    "page_name" => "Berkas Gugatan",
  ], true) ?>

  <div class="card">
    <div class="card-body pb-0">
      <h4 class="card-title">Form Register Berkas Permohonan</h4>
      <p class="card-subtitle mb-0">
        Form dengan tanda <mark>
          <code>*</code>
        </mark> wajib diisi.
      </p>
    </div>
    <form
      hx-post="/berkas_permohonan/fetch_form"
      hx-target="#form-berkas-gugatan"
      hx-on::before-request="$('#btn-cari-perkara').attr('disabled', true).html('<i class=\'ti ti-loader ti-pulse\'></i> Mohon Tunggu...')"
      hx-on::after-request="$('#btn-cari-perkara').attr('disabled', false).html('<i class=\'ti ti-search\'></i> Cari')"
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
    <div id="form-berkas-gugatan"></div>
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