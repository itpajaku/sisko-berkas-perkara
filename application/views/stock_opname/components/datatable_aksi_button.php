<?php

use App\Libraries\Hashid;
?>
<button
	class="btn btn-warning"
	data-bs-toggle="modal"
	data-bs-target="#dynamic-modal"
	hx-get="<?= base_url('stock_opname_atk/referensi/' . Hashid::encode($item->id)) ?>"
	hx-target="#dynamic-modal-content"
	hx-swap="outerHTML">
	Edit
	<i class="ti ti-edit"></i>
</button>