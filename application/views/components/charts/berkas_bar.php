<?php

use Illuminate\Support\Str;
use App\Libraries\Templ;

$chart_id = Str::random(10);
$rerender = 0;
?>

<div class="card w-100" id="widget-berkas-bar">
  <div class="card-body">
    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
      <div class="mb-3 mb-sm-0">
        <h4 class="card-title fw-semibold">Berkas Diregister</h4>
        <p class="card-subtitle mb-0">Per tahun <?= isset($tahun) ? $tahun : date('Y') ?></p>
      </div>
      <form
        hx-target="#berkas-bar-content"
        hx-trigger="change"
        hx-indicator="#htmx-indicator-<?= $chart_id ?>"
        hx-post="<?= base_url("/widget/berkas_bar_chart") ?>">
        <select
          name="tahun"

          class="form-select w-auto">
          <?php foreach ((function () {
              $year = date("Y");
              $years = [];
              for ($i = $year - 1; $i <= $year; $i++) {
                array_unshift($years, $i);
              }
              return $years;
            })() as $year
          ) { ?>
            <option <?= isset($tahun) && $tahun == $year ? 'selected' : null ?> value="<?= $year ?>">Tahun <?= $year ?></option>
          <?php } ?>
        </select>
        <input type="hidden" name="chart_id" value="<?= $chart_id ?>" />
        <input type="hidden" name="rerender" value="<?= $rerender ?>" />
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      </form>
    </div>
    <div class="htmx-indicator" id="htmx-indicator-<?= $chart_id ?>">Mohon Tunggu ...</div>
    <div id="berkas-bar-content">
      <?= Templ::component("components/charts/berkas_bar_content", [
        "rerender" => $rerender,
        "chart_id" => $chart_id,
      ]) ?>
    </div>
  </div>

</div>