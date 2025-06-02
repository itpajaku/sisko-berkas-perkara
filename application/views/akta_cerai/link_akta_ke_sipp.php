   <a href="javascript:void(0)"
     data-bs-toggle="popover"
     title="Data Akta ini akan di sinkronkan dengan arsip di SIPP"
     hx-patch="<?= base_url("/akta_cerai/$berkas->hash_id/sinkron") ?>"
     hx-swap="none"
     hx-confirm="Pastikan arsip perkara ini sudah di scan dan di upload di sipp.">
     <i class="ti ti-link"></i>
     Sinkronkan arsip
   </a>