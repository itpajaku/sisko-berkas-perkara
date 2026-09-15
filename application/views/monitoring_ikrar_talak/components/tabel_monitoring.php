<?php

use Carbon\Carbon;

/**
 * Komponen Tabel Monitoring Ikrar Talak
 *
 * @var \Illuminate\Support\Collection $data
 * @var array $berkas_gugatan
 * @var int $offset
 */
$berkas_gugatan = $berkas_gugatan ?? [];
?>
<?php if ($data->isEmpty()): ?>
	<div class="alert alert-info">
		<i class="ti ti-info-circle me-1"></i>
		Tidak ada data ikrar talak pada periode ini.
	</div>
<?php else: ?>
	<div class="table-responsive my-3">
		<table class="table table-bordered table-hover table-striped border-primary border-2 rounded-top align-middle">
			<thead class="text-center table-light">
				<tr>
					<th width="40">No</th>
					<th>Nomor Perkara</th>
					<th>Para Pihak</th>
					<th class="text-center" style="width: 140px;">Status Register</th>
					<th>Tanggal Putus</th>
					<th>Tanggal BHT</th>
					<th>Penetapan Sidang Ikrar</th>
					<th>Sidang Pertama</th>
					<th>Status / Tgl Ikrar</th>
					<th>Majelis Hakim</th>
					<th width="100">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $index => $item): ?>
					<?php
					$tglPutus = $item->perkara->perkara_putusan->tanggal_putusan ?? null;
					$tglBht = $item->perkara->perkara_putusan->tanggal_bht ?? null;
					$isSudahIkrar = !empty($item->tgl_ikrar_talak);
					$isRegistered = isset($berkas_gugatan[$item->perkara_id]);
					$bgItem = $berkas_gugatan[$item->perkara_id] ?? null;
					?>
					<tr>
						<td class="text-center"><?= ($offset ?? 0) + $index + 1 ?></td>
						<td class="fw-semibold text-nowrap"><?= $item->perkara->nomor_perkara ?? '-' ?></td>
						<td>
							<small class="d-block text-muted">P: <?= htmlspecialchars($item->perkara->pihak1_text ?? '-') ?></small>
							<small class="d-block text-muted">T: <?= htmlspecialchars($item->perkara->pihak2_text ?? '-') ?></small>
						</td>
						<td class="text-center text-nowrap">
							<?php if ($isRegistered): ?>
								<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" title="Sudah tercatat di Register Berkas Gugatan">
									<i class="ti ti-circle-check me-1"></i>Teregistrasi (G)
								</span>
								<?php if ($bgItem): ?>
									<div class="mt-1">
										<a href="<?= site_url('berkas_gugatan/' . \App\Libraries\Hashid::encode($bgItem->id)) ?>" class="text-primary text-decoration-none" style="font-size: 0.72rem;" target="_blank">
											<i class="ti ti-folder me-1"></i>Buka Berkas
										</a>
									</div>
								<?php endif; ?>
							<?php else: ?>
								<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" title="Belum tercatat di Register Berkas Gugatan">
									<i class="ti ti-alert-triangle me-1"></i>Belum Register
								</span>
							<?php endif; ?>
						</td>
						<td class="text-center text-nowrap">
							<?= $tglPutus ? Carbon::parse($tglPutus)->translatedFormat('d M Y') : '-' ?>
						</td>
						<td class="text-center text-nowrap">
							<?= $tglBht ? Carbon::parse($tglBht)->translatedFormat('d M Y') : '-' ?>
						</td>
						<td class="text-center text-nowrap">
							<?= $item->tanggal_penetapan_sidang_ikrar ? Carbon::parse($item->tanggal_penetapan_sidang_ikrar)->translatedFormat('d M Y') : '-' ?>
						</td>
						<td class="text-center text-nowrap">
							<?= $item->tanggal_sidang_pertama ? Carbon::parse($item->tanggal_sidang_pertama)->translatedFormat('d M Y') : '-' ?>
						</td>
						<td class="text-center text-nowrap">
							<?php if ($isSudahIkrar): ?>
								<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
									<i class="ti ti-check me-1"></i><?= Carbon::parse($item->tgl_ikrar_talak)->translatedFormat('d M Y') ?>
								</span>
							<?php else: ?>
								<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
									<i class="ti ti-clock me-1"></i>Belum Ikrar
								</span>
							<?php endif; ?>
						</td>
						<td>
							<div class="small fw-semibold"><?= htmlspecialchars($item->majelis_hakim_nama ?? '-') ?></div>
							<?php if (!empty($item->panitera_pengganti_text)): ?>
								<div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($item->panitera_pengganti_text) ?></div>
							<?php endif; ?>
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
