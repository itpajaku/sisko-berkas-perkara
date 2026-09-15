<?php
/**
 * @var string $breadcrumb
 * @var array|\Illuminate\Support\Collection $berkas
 * @var array|\Illuminate\Support\Collection $jurusita
 * @var string $filterStatus
 * @var string $filterTahun
 * @var string|null $filterJurusita
 * @var string|null $filterQuery
 */
$berkas = $berkas ?? [];
$jurusita = $jurusita ?? [];
$filterStatus = $filterStatus ?? 'belum';
$filterTahun = $filterTahun ?? date("Y");
$filterJurusita = $filterJurusita ?? null;
$filterQuery = $filterQuery ?? null;
$breadcrumb = $breadcrumb ?? '';
?>
<div class="container-fluid">
  <?= $breadcrumb ?>

  <!-- Toolbar & Filter -->
  <div class="card shadow-sm mb-4">
    <div class="card-body py-3">
      <form action="<?= base_url('berkas_pbt') ?>" method="GET" class="row g-2 align-items-center">
        <!-- Status Filter -->
        <div class="col-md-3">
          <label class="form-label text-muted small mb-1 fw-semibold">Status Berkas PBT</label>
          <select name="status" class="form-select form-select-sm">
            <option value="belum" <?= ($filterStatus === 'belum') ? 'selected' : '' ?>>
              🔴 Belum Dikirim / Belum Upload
            </option>
            <option value="sudah" <?= ($filterStatus === 'sudah') ? 'selected' : '' ?>>
              🟢 Sudah Upload / Diberitahukan
            </option>
            <option value="semua" <?= ($filterStatus === 'semua') ? 'selected' : '' ?>>
              ⚪ Semua Status Verstek
            </option>
          </select>
        </div>

        <!-- Tahun Filter -->
        <div class="col-md-2">
          <label class="form-label text-muted small mb-1 fw-semibold">Tahun Daftar</label>
          <select name="tahun" class="form-select form-select-sm">
            <?php
            $currentYear = (int) date("Y");
            for ($y = $currentYear; $y >= $currentYear - 3; $y--) { ?>
              <option value="<?= $y ?>" <?= ($filterTahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php } ?>
            <option value="all" <?= ($filterTahun === 'all') ? 'selected' : '' ?>>Semua Tahun</option>
          </select>
        </div>

        <!-- Jurusita Filter -->
        <div class="col-md-3">
          <label class="form-label text-muted small mb-1 fw-semibold">Jurusita</label>
          <select name="j" class="form-select form-select-sm">
            <option value="">-- Semua Jurusita --</option>
            <?php foreach ($jurusita as $j) : ?>
              <option value="<?= $j->id ?>" <?= ($filterJurusita == $j->id) ? "selected" : "" ?>><?= htmlspecialchars($j->nama) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Search Input -->
        <div class="col-md-2">
          <label class="form-label text-muted small mb-1 fw-semibold">Nomor Perkara</label>
          <input type="text" class="form-control form-select-sm" placeholder="Contoh: 123" name="q" value="<?= htmlspecialchars($filterQuery ?? '') ?>">
        </div>

        <!-- Action Buttons -->
        <div class="col-md-2 d-flex align-items-end gap-2" style="padding-top: 24px;">
          <button type="submit" class="btn btn-primary btn-sm flex-fill d-flex align-items-center justify-content-center">
            <i class="ti ti-search me-1"></i> Filter
          </button>
          <a href="<?= base_url('berkas_pbt') ?>" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
            <i class="ti ti-rotate"></i>
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary Info -->
  <div class="d-flex mb-3 justify-content-between align-items-center">
    <div>
      <span class="badge bg-primary fs-3 px-3 py-2">
        Total Ditemukan: <?= count($berkas) ?> Perkara
      </span>
      <?php if ($filterStatus === 'belum') : ?>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-2">
          Menampilkan Perkara Verstek Belum Ada PBT / Relaas Belum Upload
        </span>
      <?php elseif ($filterStatus === 'sudah') : ?>
        <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">
          Menampilkan Perkara Verstek dengan PBT Terupload / Sudah Diberitahukan
        </span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Empty State -->
  <?php if (count($berkas) === 0) : ?>
    <div class="card shadow-sm border-0">
      <div class="card-body text-center py-5">
        <div class="mb-3 text-muted">
          <i class="ti ti-file-check fs-1 text-success opacity-75"></i>
        </div>
        <h5 class="fw-semibold text-dark">Tidak ada data perkara untuk filter ini</h5>
        <p class="text-muted mb-3">
          <?php if ($filterStatus === 'belum') : ?>
            Semua berkas perkara putusan verstek sudah memiliki pemberitahuan isi putusan (PBT) yang diunggah atau diberitahukan.
          <?php else : ?>
            Tidak ditemukan data perkara yang cocok dengan kriteria pencarian yang Anda pilih.
          <?php endif; ?>
        </p>
        <a href="<?= base_url('berkas_pbt') ?>" class="btn btn-sm btn-outline-primary">
          <i class="ti ti-rotate me-1"></i> Reset ke Default
        </a>
      </div>
    </div>
  <?php endif; ?>

  <!-- Case Cards List -->
  <?php foreach ($berkas as $b) : ?>
    <?php
    // Evaluasi status PBT untuk Tergugat (pihak = 2)
    $pbtTergugatList = $b->pemberitahuan_putusan ? $b->pemberitahuan_putusan->where('pihak', 2) : collect();
    $pbtPenggugatList = $b->pemberitahuan_putusan ? $b->pemberitahuan_putusan->where('pihak', 1) : collect();

    $tergugatSudahPbt = false;
    $tanggalPbtTergugat = null;
    $dokumenRelaasTergugat = null;
    $ketKetemuTergugat = null;

    foreach ($pbtTergugatList as $pbtT) {
      if (!empty($pbtT->tanggal_pemberitahuan_putusan)) {
        $tergugatSudahPbt = true;
        $tanggalPbtTergugat = $pbtT->tanggal_pemberitahuan_putusan;
      }
      if (!empty($pbtT->dokumen)) {
        $dokumenRelaasTergugat = $pbtT->dokumen;
      } elseif (!empty($pbtT->file_dokumen)) {
        $dokumenRelaasTergugat = $pbtT->file_dokumen;
      }
      if (!empty($pbtT->ket_ketemu)) {
        $ketKetemuTergugat = $pbtT->ket_ketemu;
      }
    }

    $isLocalPbt = !empty($b->register_berkas_gugatan?->tanggal_pbt);
    $localPbtDate = $b->register_berkas_gugatan?->tanggal_pbt ?? null;
    $isBht = !empty($b->perkara_putusan?->tanggal_bht);

    $isPbtSelesai = $tergugatSudahPbt || !empty($dokumenRelaasTergugat) || $isLocalPbt || $isBht;
    ?>
    <div class="card <?= $isPbtSelesai ? 'border-success' : 'border-danger' ?> card-darker-hover shadow-sm mb-3">
      <div class="card-body">
        <!-- Card Header -->
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="mb-1 fw-bold text-primary">
              <i class="ti ti-mail<?= $isPbtSelesai ? '-check' : '-off' ?> me-1"></i>
              Perkara Nomor: <?= htmlspecialchars($b->nomor_perkara) ?>
            </h6>
            <small class="text-muted">
              Pendaftaran: <?= tanggal_indo($b->tanggal_pendaftaran, false) ?> | Jenis: <strong><?= htmlspecialchars($b->jenis_perkara_nama) ?></strong>
            </small>
          </div>

          <!-- Badges -->
          <div class="d-flex flex-wrap gap-1 align-items-center">
            <?php if ($b->prodeo == 1) : ?>
              <span class="badge bg-info-subtle text-info border border-info-subtle">
                <i class="ti ti-user-check me-1"></i> Prodeo
              </span>
            <?php endif; ?>

            <?php if (!empty($dokumenRelaasTergugat)) : ?>
              <span class="badge bg-success" title="File relaas PBT telah terunggah ke SIPP: <?= htmlspecialchars($dokumenRelaasTergugat) ?>">
                <i class="ti ti-file-check me-1"></i> Relaas PBT Terupload
              </span>
            <?php endif; ?>

            <?php if ($tergugatSudahPbt && !empty($tanggalPbtTergugat)) : ?>
              <span class="badge bg-primary" title="Tanggal pemberitahuan putusan ke Tergugat">
                <i class="ti ti-calendar-check me-1"></i> PBT SIPP: <?= tanggal_indo($tanggalPbtTergugat, false) ?>
              </span>
            <?php endif; ?>

            <?php if ($isLocalPbt) : ?>
              <span class="badge bg-info" title="Tercatat di Register Berkas Gugatan">
                <i class="ti ti-check me-1"></i> PBT Sisko: <?= tanggal_indo($localPbtDate, false) ?>
              </span>
            <?php endif; ?>

            <?php if ($isBht) : ?>
              <span class="badge bg-secondary" title="Sudah Berkekuatan Hukum Tetap">
                <i class="ti ti-gavel me-1"></i> Sudah BHT
              </span>
            <?php endif; ?>

            <?php if (!$isPbtSelesai) : ?>
              <span class="badge bg-danger">
                <i class="ti ti-alert-circle me-1"></i> Belum Dikirim
              </span>
            <?php endif; ?>

            <!-- Link ke SIPP -->
            <button
              type="button"
              class="badge bg-dark border-0 py-2 px-2"
              style="cursor: pointer;"
              onclick="window.open( '<?= sipp_url('perkara_detil_agama/' . base64_encode(App\Libraries\AccessLegacyEn::encode($b->perkara_id))) ?>', 'Snopzer', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0'); return false;">
              <i class="ti ti-send me-1"></i> Buka di SIPP
            </button>
          </div>
        </div>

        <!-- Detail Perkara & Pihak -->
        <div class="row g-2 mb-3 bg-light-subtle p-2 rounded">
          <div class="col-md-3">
            <small class="text-muted d-block">Penggugat / Pemohon</small>
            <div class="fw-semibold text-truncate" title="<?= htmlspecialchars($b->pihak1_text ?? '-') ?>">
              <?= htmlspecialchars($b->pihak1_text ?? '-') ?>
            </div>
          </div>

          <div class="col-md-3">
            <small class="text-muted d-block">Tergugat / Termohon</small>
            <div class="fw-semibold text-truncate text-danger" title="<?= htmlspecialchars($b->pihak2_text ?? '-') ?>">
              <?= htmlspecialchars($b->pihak2_text ?? '-') ?>
            </div>
          </div>

          <div class="col-md-2">
            <small class="text-muted d-block">Tanggal Putusan</small>
            <div><?= !empty($b->perkara_putusan?->tanggal_putusan) ? tanggal_indo($b->perkara_putusan->tanggal_putusan, false) : '-' ?></div>
          </div>

          <div class="col-md-2">
            <small class="text-muted d-block">Tahapan Terakhir</small>
            <div class="text-truncate" title="<?= htmlspecialchars($b->proses_terakhir_text ?? '-') ?>">
              <?= htmlspecialchars($b->proses_terakhir_text ?? '-') ?>
            </div>
          </div>

          <div class="col-md-2">
            <small class="text-muted d-block">Jurusita</small>
            <?php if (count($b->perkara_jurusita) > 0) : ?>
              <?php foreach ($b->perkara_jurusita as $jrs) : ?>
                <div class="fw-semibold"><?= htmlspecialchars($jrs->jurusita_nama ?? '-') ?></div>
              <?php endforeach; ?>
            <?php else : ?>
              <div class="text-muted">-</div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Detail Relaas PBT / Pemberitahuan Putusan -->
        <div class="border rounded p-3 mb-3 bg-white">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-dark fw-bold">
              <i class="ti ti-notes me-1 text-primary"></i> Status & Dokumen Pemberitahuan Putusan (PBT) di SIPP
            </h6>
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <div class="p-2 border rounded bg-light-subtle h-100">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="badge bg-secondary-subtle text-secondary mb-1">Pihak 1 (Penggugat)</span>
                  <small class="text-muted">Hadir saat putusan</small>
                </div>
                <div class="small mt-1">
                  <?php if ($pbtPenggugatList->count() > 0 && !empty($pbtPenggugatList->first()->tanggal_pemberitahuan_putusan)) : ?>
                    <i class="ti ti-check text-success me-1"></i>
                    PBT: <?= tanggal_indo($pbtPenggugatList->first()->tanggal_pemberitahuan_putusan, false) ?>
                  <?php else : ?>
                    <span class="text-muted"><i class="ti ti-info-circle me-1"></i>Tidak memerlukan PBT (Hadir putusan)</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="p-2 border rounded <?= $isPbtSelesai ? 'border-success bg-success-subtle' : 'border-warning bg-warning-subtle' ?> h-100">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="badge <?= $isPbtSelesai ? 'bg-success' : 'bg-danger' ?> mb-1">Pihak 2 (Tergugat - Verstek)</span>
                  <span class="small fw-semibold <?= $isPbtSelesai ? 'text-success' : 'text-danger' ?>">
                    <?= $isPbtSelesai ? 'Sudah Ada PBT / Upload' : 'Wajib PBT / Belum Ada' ?>
                  </span>
                </div>
                <div class="small mt-1">
                  <?php if (!empty($tanggalPbtTergugat)) : ?>
                    <div><i class="ti ti-calendar text-primary me-1"></i> Tanggal PBT: <strong><?= tanggal_indo($tanggalPbtTergugat, false) ?></strong></div>
                  <?php endif; ?>

                  <?php if (!empty($ketKetemuTergugat)) : ?>
                    <div><i class="ti ti-user-check text-muted me-1"></i> Keterangan: <?= htmlspecialchars($ketKetemuTergugat) ?></div>
                  <?php endif; ?>

                  <?php if (!empty($dokumenRelaasTergugat)) : ?>
                    <div>
                      <i class="ti ti-file-text text-success me-1"></i>
                      File Relaas: <code><?= htmlspecialchars($dokumenRelaasTergugat) ?></code>
                    </div>
                  <?php elseif ($isPbtSelesai) : ?>
                    <div class="text-success"><i class="ti ti-check-double me-1"></i> PBT sudah tercatat/selesai.</div>
                  <?php else : ?>
                    <div class="text-danger"><i class="ti ti-alert-triangle me-1"></i> Belum ada tanggal pemberitahuan dan belum ada file relaas yang diunggah di SIPP.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Biaya Panggilan / PBT (Instrumen Keuangan) -->
        <div class="table-responsive">
          <table class="table table-bordered table-sm table-hover mb-0">
            <thead class="table-light">
              <tr class="small">
                <th width="40" class="text-center">No</th>
                <th>Transaksi Biaya Panggilan / PBT</th>
                <th>Tanggal Transaksi</th>
                <th>Pihak Dituju</th>
                <th class="text-end">Jumlah Biaya</th>
              </tr>
            </thead>
            <tbody class="small">
              <?php foreach ($b->perkara_transaksi as $n => $pt) : ?>
                <tr>
                  <td class="text-center"><?= $n + 1 ?></td>
                  <td><?= htmlspecialchars($pt->uraian ?? '-') ?></td>
                  <td><?= !empty($pt->tanggal_transaksi) ? tanggal_indo($pt->tanggal_transaksi, false) : '-' ?></td>
                  <td><?= htmlspecialchars($pt->detail_pihak->nama ?? '-') ?></td>
                  <td class="text-end fw-semibold">Rp <?= number_format($pt->jumlah ?? 0, 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (count($b->perkara_transaksi) == 0) : ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-2">
                    <i class="ti ti-info-circle me-1"></i> Tidak ada catatan transaksi biaya pemberitahuan putusan (Kode 29).
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
