 <div id="arsip-bar-comp">
   <div class="card">
     <div class="card-body">
       <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="card-title fw-semibold mb-1">
           Pengarsipan Tahun Ini
         </h4>
         <form action="">
           <select name="tahun" class="form-control form-select">
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
         </form>
       </div>
       <p class="card-subtitle">Bulan Ini</p>
       <div id="chart-arsip-bar" class="mb-2 pb-8 mx-n4"></div>
       <div class="d-flex align-items-center gap-4">
         <div class="d-flex align-items-center">
           <div class="bg-primary-subtle rounded me-8 p-8 d-flex align-items-center justify-content-center">
             <i class="ti ti-calendar text-primary fs-6"></i>
           </div>
           <div>
             <p class="fs-3 mb-0 fw-normal">Hari Ini</p>
             <h6 class="fw-semibold text-dark fs-4 mb-0">
               $36,358
             </h6>
           </div>
         </div>
         <div class="d-flex align-items-center">
           <div class="text-bg-light rounded me-8 p-8 d-flex align-items-center justify-content-center">
             <i class="ti ti-calendar text-muted fs-6"></i>
           </div>
           <div>
             <p class="fs-3 mb-0 fw-normal">Tahun Ini</p>
             <h6 class="fw-semibold text-dark fs-4 mb-0">
               $5,296
             </h6>
           </div>
         </div>
       </div>
     </div>
   </div>
   <script>
     document.addEventListener("DOMContentLoaded", function() {
       $("#arsip-bar-comp select[name='tahun']").on("change", function() {
         var tahun = $(this).val();
         $.ajax({
           url: "<?= base_url("charts/arsip_bar") ?>",
           data: {
             tahun: tahun,
           },
         }).
         done(function(response) {
           const series = [{
             name: "Arsip Masuk",
             data: response.chart,
           }];
           updateChart("#chart-arsip-bar", series);
         });
       });

       var data = {
         series: [{
           name: "Employee Salary",
           data: [17, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 17],
         }, ],
         chart: {
           toolbar: {
             show: false,
           },
           height: 260,
           type: "bar",
           fontFamily: "inherit",
           foreColor: "#adb0bb",
         },
         plotOptions: {
           bar: {
             borderRadius: 4,
             columnWidth: "45%",
             distributed: true,
             endingShape: "rounded",
           },
         },

         dataLabels: {
           enabled: true,
         },
         legend: {
           show: false,
         },
         grid: {
           yaxis: {
             lines: {
               show: true,
             },
           },
           xaxis: {
             lines: {
               show: true,
             },
           },
         },
         xaxis: {
           categories: [
             "Jan",
             "Feb",
             "Mar",
             "Apr",
             "Mei",
             "Jun",
             "Jul",
             "Aug",
             "Sep",
             "Okt",
             "Nov",
             "Dec",
           ],
           axisBorder: {
             show: false,
           },
           axisTicks: {
             show: false,
           },
         },
         yaxis: {
           labels: {
             show: false,
           },
         },
         tooltip: {
           theme: "dark",
         },
       };

       $.ajax({
         url: "<?= base_url("charts/arsip_bar") ?>",
         data: {
           tahun: $("#arsip-bar-comp select[name='tahun']").val(),
         },
       }).
       done(function(response) {
         data.series = [{
           name: "Arsip Masuk",
           data: response.chart,
         }, ];
         createChart("#chart-arsip-bar", data);
         $("#arsip-bar-comp > div > div > div.d-flex.align-items-center.gap-4 > div:nth-child(1) > div:nth-child(2) > h6").text(response.text.hari_ini.toLocaleString());
         $("#arsip-bar-comp > div > div > div.d-flex.align-items-center.gap-4 > div:nth-child(2) > div:nth-child(2) > h6").text(response.text.tahun_ini.toLocaleString());
       });
     })
   </script>
 </div>