   <a href="javascript:void(0)"
     data-bs-toggle="popover"
     class="text-danger"
     title="Data register ini akan di sinkronkand dengan arsip di SIPP"
     hx-patch="<?= base_url("/akta_cerai/$berkas->hash_id/unsinkron") ?>"
     hx-swap="none"
     hx-confirm="Setelah diputus, berkas ini akan dianggap masih berjalan.">
     <i class="ti ti-unlink"></i>
     Hapus sinkronisasi arsip
   </a>