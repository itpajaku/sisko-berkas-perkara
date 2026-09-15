<?php
use Carbon\Carbon;

/**
 * Komponen Tabel Perkara Dicabut
 *
 * @var array $data
 * @var array $berkas_gugatan
 * @var array $berkas_permohonan
 * @var array $mediasi_map
 * @var int $offset
 * @var string $bulan_label
 * @var int $total_data
 */
?>

<?php if (empty($data) || count($data) === 0): ?>
	<div class="alert alert-warning text-center my-4 py-4" role="alert">
		<i class="ti ti-info-circle fs-6 d-block mb-2 text-warning"></i>
		<span class="fw-semibold">Tidak ada perkara dicabut</span> yang ditemukan untuk kriteria filter dan periode ini.
	</div>
<?php else: ?>
	<div class="d-flex justify-content-between align-items-center mb-2">
		<small class="text-muted">Menampilkan <strong><?= count($data) ?></strong> dari total <strong><?= number_format($total_data ?? count($data), 0, ',', '.') ?></strong> perkara</small>
	</div>
	<div class="table-responsive">
		<table class="table table-hover align-middle w-100 mb-0">
			<thead class="table-light">
				<tr>
					<th class="text-center" style="width: 50px;">No</th>
					<th>Nomor Perkara & Jenis</th>
					<th class="text-center" style="width: 175px;">Hasil Mediasi</th>
					<th class="text-center" style="width: 155px;">Status Register</th>
					<th class="text-center" style="width: 125px;">Tanggal Daftar</th>
					<th class="text-center" style="width: 135px;">Tanggal Putus Cabut</th>
					<th>Majelis Hakim & PP</th>
					<th class="text-center" style="width: 105px;">Amar Putusan</th>
					<th class="text-center" style="width: 105px;">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $index => $item): ?>
					<?php
					$perkara = $item->perkara;
					$tglDaftar = $perkara->tanggal_pendaftaran ?? null;
					$tglPutus = $item->tanggal_putusan ?? null;
					$penetapan = $perkara->perkara_penetapan ?? null;
					$majelisHakim = $penetapan->majelis_hakim_nama ?? '-';
					$panitera = $penetapan->panitera_pengganti_text ?? '';
					$isGugatan = stripos($perkara->nomor_perkara ?? '', 'Pdt.G') !== false;
					$isRegisteredGugatan = isset($berkas_gugatan[$item->perkara_id]);
					$isRegisteredPermohonan = isset($berkas_permohonan[$item->perkara_id]);
					$uniqueAmarId = "amar-content-" . ($item->perkara_id ?? $index);
					$tglPutusFormatted = $tglPutus ? Carbon::parse($tglPutus)->translatedFormat('d M Y') : '-';

					// Data Hasil Mediasi dari batch query SIPP
					$dataMediasi = $mediasi_map[$item->perkara_id] ?? null;
					if (!$dataMediasi && isset($perkara->perkara_mediasi)) {
						$m = $perkara->perkara_mediasi;
						$dataMediasi = [
							'label' => $m->status_mediasi->nama ?? ($m->hasil_mediasi ?? 'Pernah Mediasi'),
							'type' => 'info',
							'icon' => 'ti ti-scale',
							'keterangan' => $m->keterangan_mediasi ?? '',
						];
					}
					?>
					<tr>
						<td class="text-center"><?= ($offset ?? 0) + $index + 1 ?></td>
						<td>
							<div class="fw-semibold text-nowrap text-dark"><?= htmlspecialchars($perkara->nomor_perkara ?? '-') ?></div>
							<div class="d-flex gap-1 mt-1">
								<span class="badge <?= $isGugatan ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-info-subtle text-info border border-info-subtle' ?> py-1 px-2" style="font-size: 0.72rem;">
									<?= $isGugatan ? 'Gugatan' : 'Permohonan' ?>
								</span>
								<?php if (!empty($perkara->jenis_perkara_nama)): ?>
									<span class="badge bg-light text-muted border py-1 px-2" style="font-size: 0.72rem;">
										<?= htmlspecialchars($perkara->jenis_perkara_nama) ?>
									</span>
								<?php endif; ?>
							</div>
						</td>
						<td class="text-center text-nowrap">
							<?php if (!$isGugatan): ?>
								<span class="badge bg-light text-muted border py-1 px-2" style="font-size: 0.75rem;" title="Perkara permohonan / voluntir tidak melalui mediasi">
									-
								</span>
							<?php elseif (empty($dataMediasi)): ?>
								<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2" style="font-size: 0.75rem;" title="Dicabut tanpa/sebelum proses mediasi di SIPP">
									<i class="ti ti-minus me-1"></i>Tanpa Mediasi
								</span>
							<?php else: ?>
								<?php
								$tipe = $dataMediasi['type'] ?? 'info';
								$badgeClass = match($tipe) {
									'success' => 'bg-success-subtle text-success border border-success-subtle',
									'danger'  => 'bg-danger-subtle text-danger border border-danger-subtle',
									'warning' => 'bg-warning-subtle text-warning border border-warning-subtle',
									'primary' => 'bg-primary-subtle text-primary border border-primary-subtle',
									default   => 'bg-info-subtle text-info border border-info-subtle',
								};
								$iconClass = $dataMediasi['icon'] ?? 'ti ti-scale';
								$tooltip = !empty($dataMediasi['keterangan']) ? $dataMediasi['keterangan'] : $dataMediasi['label'];
								?>
								<span class="badge <?= $badgeClass ?> py-1 px-2" style="font-size: 0.75rem;" title="<?= htmlspecialchars($tooltip) ?>">
									<i class="<?= $iconClass ?> me-1"></i><?= htmlspecialchars($dataMediasi['label']) ?>
								</span>
							<?php endif; ?>
						</td>
						<td class="text-center text-nowrap">
							<?php if ($isGugatan): ?>
								<?php if ($isRegisteredGugatan): ?>
									<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" title="Sudah tercatat di Register Berkas Gugatan">
										<i class="ti ti-circle-check me-1"></i>Teregistrasi (G)
									</span>
								<?php else: ?>
									<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" title="Belum tercatat di Register Berkas Gugatan">
										<i class="ti ti-alert-triangle me-1"></i>Belum Register
									</span>
								<?php endif; ?>
							<?php else: ?>
								<?php if ($isRegisteredPermohonan): ?>
									<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" title="Sudah tercatat di Register Berkas Permohonan">
										<i class="ti ti-circle-check me-1"></i>Teregistrasi (P)
									</span>
								<?php else: ?>
									<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" title="Belum tercatat di Register Berkas Permohonan">
										<i class="ti ti-alert-triangle me-1"></i>Belum Register
									</span>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td class="text-center text-nowrap text-muted" style="font-size: 0.85rem;">
							<?= $tglDaftar ? Carbon::parse($tglDaftar)->translatedFormat('d M Y') : '-' ?>
						</td>
						<td class="text-center text-nowrap">
							<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
								<i class="ti ti-file-off me-1"></i><?= $tglPutusFormatted ?>
							</span>
						</td>
						<td>
							<div class="small fw-semibold text-dark"><?= nl2br(htmlspecialchars($majelisHakim)) ?></div>
							<?php if (!empty($panitera)): ?>
								<div class="text-muted mt-1" style="font-size: 0.75rem;">
									<i class="ti ti-user me-1"></i>PP: <?= htmlspecialchars($panitera) ?>
								</div>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<!-- Hidden amar content container -->
							<div id="<?= $uniqueAmarId ?>" style="display: none;">
								<?= !empty($item->amar_putusan) ? $item->amar_putusan : '<em class="text-muted">Tidak ada amar putusan.</em>' ?>
							</div>
							<button type="button"
									class="btn btn-sm btn-outline-info"
									onclick="showAmarModal('<?= htmlspecialchars($perkara->nomor_perkara ?? '', ENT_QUOTES) ?>', '<?= $tglPutusFormatted ?>', '<?= $uniqueAmarId ?>')">
								<i class="ti ti-file-text"></i> Amar
							</button>
						</td>
						<td class="text-center">
							<?php
							$sipp_link = sipp_url("perkara_detil_agama/" . base64_encode(\App\Libraries\AccessLegacyEn::encode($item->perkara_id)));
							?>
							<a href="javascript:void(0)"
							   class="btn btn-sm btn-primary"
							   onclick="window.open('<?= $sipp_link ?>', 'SIPP', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0'); return false;">
								<i class="ti ti-send"></i> Buka SIPP
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div hx-boost="true">
		<?= $this->pagination->create_links(); ?>
	</div>
<?php endif; ?>
