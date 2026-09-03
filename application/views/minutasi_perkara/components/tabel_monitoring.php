<?php

use Carbon\Carbon;
?>
<?php if ($data->isEmpty()): ?>
	<div class="alert alert-info">
		<i class="ti ti-info-circle me-1"></i>
		Tidak ada perkara yang belum diminutasi pada bulan ini.
	</div>
<?php else: ?>
	<div class="table-responsive my-3">
		<table class="table table-bordered table-hover table-striped border-primary border-2 rounded-top">
			<thead class="text-center table-light">
				<tr>
					<th width="50">No</th>
					<th>Nomor Perkara</th>
					<th>Jenis Perkara</th>
					<th>Tanggal Putusan</th>
					<th>Hakim</th>
					<th>Panitera</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $index => $item): ?>
					<tr>
						<td class="text-center"><?= ($offset ?? 0) + $index + 1 ?></td>
						<td><?= $item->perkara->nomor_perkara ?? '-' ?></td>
						<td><?= $item->perkara->jenis_perkara_nama ?? '-' ?></td>
						<td class="text-center">
							<?= $item->tanggal_putusan
								? Carbon::parse($item->tanggal_putusan)->translatedFormat('d F Y')
								: '-'
							?>
						</td>
						<td><?= htmlspecialchars($item->perkara->perkara_penetapan->majelis_hakim_nama ?? '-') ?></td>
						<td><?= htmlspecialchars($item->perkara->perkara_penetapan->panitera_pengganti_text ?? '-') ?></td>
						<td class="text-center">
							<?php
							$sipp_link = sipp_url("perkara_detil_agama/" . base64_encode(\App\Libraries\AccessLegacyEn::encode($item->perkara_id)));
							?>
							<a href="javascript:void(0)"
							   class="btn btn-sm btn-primary"
							   onclick="window.open('<?= $sipp_link ?>', 'SIPP', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0'); return false;">
								<i class="ti ti-send"></i> Buka di SIPP
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
