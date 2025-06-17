 <div class="card w-100">
   <div class="card-body">
     <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
       <div class="mb-3 mb-sm-0">
         <h4 class="card-title fw-semibold">Berkas Diregister</h4>
         <p class="card-subtitle mb-0">Per Bulan Ini</p>
       </div>
       <select class="form-select w-auto">
         <option value="1">March 2024</option>
         <option value="2">April 2024</option>
         <option value="3">May 2024</option>
         <option value="4">June 2024</option>
       </select>
     </div>
     <div class="row align-items-center">
       <div class="col-md-8">
         <div id="chart" class="mx-n6"></div>
       </div>
       <div class="col-md-4">
         <div class="hstack mb-4 pb-1">
           <div class="p-8 bg-primary-subtle rounded-1 me-3 d-flex align-items-center justify-content-center">
             <i class="ti ti-grid-dots text-primary fs-6"></i>
           </div>
           <div>
             <h4 class="mb-0 fs-7 fw-semibold">533</h4>
             <p class="fs-3 mb-0">Total Berkas</p>
           </div>
         </div>
         <div>
           <div class="d-flex align-items-baseline mb-4">
             <span class="round-8 text-bg-primary rounded-circle me-6"></span>
             <div>
               <p class="fs-3 mb-1">Berkas Gugatan</p>
               <h6 class="fs-5 fw-semibold mb-0">$48,820</h6>
             </div>
           </div>
           <div class="d-flex align-items-baseline mb-4 pb-1">
             <span class="round-8 text-bg-secondary rounded-circle me-6"></span>
             <div>
               <p class="fs-3 mb-1">Berkas Permohonan</p>
               <h6 class="fs-5 fw-semibold mb-0">$26,498</h6>
             </div>
           </div>
           <div>
             <button class="btn btn-primary w-100">
               View Full Report
             </button>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 <script>
   window.addEventListener("load", function() {
     var chart = {
       series: [{
           name: "Eanings this month",
           data: [1.5, 2.7, 2.2, 3.6, 1.5, 1.0],
         },
         {
           name: "Expense this month",
           data: [-1.8, -1.1, -2.5, -1.5, -0.6, -1.8],
         },
       ],
       chart: {
         toolbar: {
           show: false,
         },
         type: "bar",
         fontFamily: "inherit",
         foreColor: "#adb0bb",
         height: 310,
         stacked: true,
       },
       colors: ["var(--bs-primary)", "var(--bs-secondary)"],
       plotOptions: {
         bar: {
           horizontal: false,
           barHeight: "60%",
           columnWidth: "20%",
           borderRadius: [6],
           borderRadiusApplication: "end",
           borderRadiusWhenStacked: "all",
         },
       },
       dataLabels: {
         enabled: false,
       },
       legend: {
         show: false,
       },
       grid: {
         borderColor: "rgba(0,0,0,0.1)",
         strokeDashArray: 3,
         xaxis: {
           lines: {
             show: false,
           },
         },
       },
       yaxis: {
         min: -5,
         max: 5,
         title: {
           // text: 'Age',
         },
       },
       xaxis: {
         axisBorder: {
           show: false,
         },
         categories: [
           "Januari",
           "Februari",
           "Maret",
           "April",
           "Mei",
           "Juni",
           "Juli",
         ],
       },
       yaxis: {
         tickAmount: 4,
       },
       tooltip: {
         theme: "dark",
       },
     };

     var chart = new ApexCharts(document.querySelector("#chart"), chart);
     chart.render();
   })
 </script>