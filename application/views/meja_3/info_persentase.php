 <?php

  use App\Libraries\Templ;
  ?>
 <div class="row">
   <?php foreach ($infolist as $info) { ?>
     <div class="col-lg-2 col-md-4 col-sm-6">
       <?= $info->showComponent() ?>
     </div>
   <?php } ?>
 </div>