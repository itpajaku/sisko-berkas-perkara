 <div class="modal-content" id="dynamic-modal-content">
   <div class="modal-header">
     <h5 class="modal-title" id="modalTitleId">
       Detail Perkara Belum Arsip
     </h5>
     <button
       type="button"
       class="btn-close"
       data-bs-dismiss="modal"
       aria-label="Close"></button>
   </div>
   <div class="modal-body p-0">
     <div class="card shadow-sm mb-0">
       <div class="card-body">
         <div class="d-flex justify-content-between align-items-start">
           <div>
             <h4 class="mb-1">
               <i class="ti ti-gavel me-2"></i>
               Detail Perkara
             </h4>
             <span class="text-muted">Nomor Perkara: <strong><?= $perkara->nomor_perkara ?></strong></span>
           </div>
           <span class="badge bg-success">
             <i class="ti ti-circle-check me-1"></i>
             <?= selisih_hari($perkara->perkara_putusan->tanggal_bht, date("Y-m-d"))  ?> Hari
           </span>
         </div>
         <hr>
         <div class="row">
           <div class="col-md-4 ">
             <div class="text-muted mb-1">
               <i class="ti ti-file-description me-1"></i>
               Jenis Perkara
             </div>
             <div class="fw-semibold"><?= $perkara->jenis_perkara_nama ?></div>
           </div>

           <div class="col-md-4 ">
             <div class="text-muted mb-1">
               <i class="ti ti-building-bank me-1"></i>
               Majelis Hakim
             </div>
             <div class="fw-semibold"><?= $perkara->perkara_penetapan->majelis_hakim_nama ?></div>
             <div class="text-muted mb-1">
               <i class="ti ti-building-bank me-1"></i>
               Panitera
             </div>
             <div class="fw-semibold"><?= $perkara->perkara_penetapan->panitera_pengganti_text ?></div>
           </div>

           <div class="col-md-4 ">
             <div class="text-muted ">
               <i class="ti ti-calendar-event me-1"></i>
               Tanggal Daftar
             </div>
             <div class="fw-semibold"><?= tanggal_indo($perkara->tanggal_pendaftaran, false) ?></div>
           </div>
         </div>
       </div>
     </div>
     <div class="card shadow-sm">
       <div class="card-body">
         <div class="row">
           <!-- PARA PIHAK -->
           <div class="col-md-6">
             <h5>
               <i class="ti ti-users me-2"></i>
               Para Pihak
             </h5>

             <div>
               <div class="text-muted">Penggugat</div>
               <div class="fw-semibold">PT Maju Jaya Sejahtera</div>
             </div>

             <div>
               <div class="text-muted">Tergugat</div>
               <div class="fw-semibold">CV Sukses Mandiri</div>
             </div>
           </div>

           <!-- JADWAL & KETERANGAN -->
           <div class="col-md-6">
             <h5>
               <i class="ti ti-info-circle me-2"></i>
               Informasi Tambahan
             </h5>

             <div>
               <div class="text-muted">
                 <i class="ti ti-clock me-1"></i>
                 Tanggal BHT
               </div>
               <div class="fw-semibold"><?= tanggal_indo($perkara->perkara_putusan->tanggal_bht, false) ?></div>
             </div>

             <div>
               <div class="text-muted">
                 <i class="ti ti-notes me-1"></i>
                 Keterangan
               </div>
               <div class="fw-semibold">
                 <?= $perkara->proses_terakhir_text ?>
               </div>
             </div>
             <?php if ($perkara->perkara_putusan->putusan_verstek == "Y") { ?>
               <div>
                 <div class="text-muted">
                   <i class="ti ti-notes me-1"></i>
                   Pemberitahuan
                 </div>
                 <div class="fw-semibold">
                   <?php foreach ($perkara->pemberitahuan_putusan as $pemberitahuan) { ?>
                     <?php
                      if ($pemberitahuan->tanggal_pemberitahuan_putusan) {
                        echo  tanggal_indo($pemberitahuan->tanggal_pemberitahuan_putusan, false) . "<br>Keterangan : " . $pemberitahuan->ket_ketemu;
                      }
                      ?>
                   <?php } ?>
                 </div>
               </div>
             <?php   } ?>
           </div>
         </div>
       </div>

     </div>
     <div class="row text-center mb-3">
       <div class="col-6">
         <div class="text-muted mb-1">
           <i class="ti ti-calendar me-1"></i>
           Tanggal Diterima Berkas
         </div>
         <div class="fw-semibold"><?php
                                  if (isset($berkas)) {
                                    if ($berkas->tanggal_diterima) {
                                      echo tanggal_indo($berkas->tanggal_diterima, false);
                                    } else {
                                      echo "Tanggal diterima belum diisi";
                                    }
                                  } else {
                                    echo "Berkas Belum Diregister";
                                  } ?></div>
       </div>
       <div class="col-6">
         <div class="text-muted mb-1">
           <i class="ti ti-file-description me-1"></i>
           Status
         </div>
         <div class="fw-semibold"><?php
                                  if (isset($berkas)) {
                                    if ($berkas->status) {
                                      echo "Diregister. Diterima.";
                                    } else {
                                      echo "Diregister. Belum Diterima.";
                                    }
                                  } else {
                                    echo "Berkas Belum Diregister";
                                  }
                                  ?></div>
       </div>
     </div>
     <table class="table table-bordered  border-primary">
       <thead class="bg-primary bg-opacity-25">
         <tr class="text-center">
           <th>No</th>
           <th>Ekspedisi Berkas</th>
           <th>Tanggal</th>
         </tr>
       </thead>
       <tbody>
         <?php if ($berkas) { ?>
           <?php foreach ($berkas->berkas_ekspedisi as $n => $ekspedisi) { ?>
             <tr>
               <td><?= ++$n ?></td>
               <td><?= $ekspedisi->posisi_ekspedisi->posisi ?></td>
               <td><?= tanggal_indo(date("Y-m-d", strtotime($ekspedisi->save_time)), false) ?> <details>
                   <?= $ekspedisi->save_time ?>
                 </details>
               </td>
             </tr>
           <?php } ?>
         <?php } else { ?>
           <tr>
             <td colspan="3" class="text-center">
               <em>Data Berkas Belum Diregister. Silahkan register menggunakan akun Panmud Gugatan</em>
           </tr>
         <?php } ?>
       </tbody>
     </table>
     <div
       class="alert alert-warning alert-dismissible fade show mx-3"
       role="alert">
       <button
         type="button"
         class="btn-close"
         data-bs-dismiss="alert"
         aria-label="Close"></button>
       <strong>Perhatian !</strong> Untuk menghapus perkara yang yang muncul di tabel ini. Silahkan input perkara ini di arsip SIPP, atau pastikan kolom nomor perkara sesuai dengan nomor perkara disini.
     </div>

     <script>
       var alertList = document.querySelectorAll(".alert");
       alertList.forEach(function(alert) {
         new bootstrap.Alert(alert);
       });
     </script>

   </div>
   <div class="modal-footer">
     <button
       type="button"
       class="btn btn-secondary"
       data-bs-dismiss="modal">
       Close
     </button>
   </div>
 </div>