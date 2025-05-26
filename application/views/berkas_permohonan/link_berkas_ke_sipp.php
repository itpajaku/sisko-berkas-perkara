   <a href="javascript:void(0)"
     data-bs-toggle="popover"
     title="Data register ini akan di sinkronkand dengan arsip di SIPP"
     hx-patch="<?= base_url("/berkas_permohonan/$berkas->hash_id/sinkron") ?>"
     hx-swap="none"
     hx-vals='<?= json_encode(["en_class_name" => $this->encryption->encrypt(class_basename($berkas))]) ?>'
     hx-confirm="Pastikan arsip perkara ini sudah di scan dan di upload di sipp.">
     <i class="ti ti-link"></i>
     Sinkronkan arsip
   </a>