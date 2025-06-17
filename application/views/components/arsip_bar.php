 <div class="card w-100">
   <div class="card-body">
     <div>
       <h4 class="card-title fw-semibold mb-1">
         Pengarsipan
       </h4>
       <p class="card-subtitle">Bulan Ini</p>
       <div id="salary" class="mb-7 pb-8 mx-n4"></div>
       <div class="d-flex align-items-center justify-content-between">
         <div class="d-flex align-items-center">
           <div class="bg-primary-subtle rounded me-8 p-8 d-flex align-items-center justify-content-center">
             <i class="ti ti-grid-dots text-primary fs-6"></i>
           </div>
           <div>
             <p class="fs-3 mb-0 fw-normal">Akta</p>
             <h6 class="fw-semibold text-dark fs-4 mb-0">
               $36,358
             </h6>
           </div>
         </div>
         <div class="d-flex align-items-center">
           <div class="text-bg-light rounded me-8 p-8 d-flex align-items-center justify-content-center">
             <i class="ti ti-grid-dots text-muted fs-6"></i>
           </div>
           <div>
             <p class="fs-3 mb-0 fw-normal">Berkas</p>
             <h6 class="fw-semibold text-dark fs-4 mb-0">
               $5,296
             </h6>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 <script>
   window.addEventListener("load", function() {
     var salary = {
       series: [{
         name: "Employee Salary",
         data: [20, 15, 30, 25, 10, 15],
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
       colors: [
         "var(--bs-primary-bg-subtle)",
         "var(--bs-primary-bg-subtle)",
         "var(--bs-primary)",
         "var(--bs-primary-bg-subtle)",
         "var(--bs-primary-bg-subtle)",
         "var(--bs-primary-bg-subtle)",
       ],
       plotOptions: {
         bar: {
           borderRadius: 4,
           columnWidth: "45%",
           distributed: true,
           endingShape: "rounded",
         },
       },

       dataLabels: {
         enabled: false,
       },
       legend: {
         show: false,
       },
       grid: {
         yaxis: {
           lines: {
             show: false,
           },
         },
         xaxis: {
           lines: {
             show: false,
           },
         },
       },
       xaxis: {
         categories: [
           ["Apr"],
           ["May"],
           ["June"],
           ["July"],
           ["Aug"],
           ["Sept"]
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

     var chart = new ApexCharts(document.querySelector("#salary"), salary);
     chart.render();
   })
 </script>