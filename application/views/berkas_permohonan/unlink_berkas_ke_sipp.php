   <a href="javascript:void(0)"
     data-bs-toggle="popover"
     class="text-danger"
     title="Data register ini akan di sinkronkand dengan arsip di SIPP"
     hx-patch="<?= base_url("/berkas_permohonan/$berkas->hash_id/unsinkron") ?>"
     hx-swap="none"
     hx-vals='<?= json_encode(["en_class_name" => $this->encryption->encrypt(class_basename($berkas))]) ?>'
     hx-confirm="Setelah diputus, berkas ini akan dianggap masih berjalan.">
     <i class="ti ti-unlink"></i>
     Hapus sinkronisasi arsip
   </a>