$(document).on("htmx:confirm", function (e) {
  if (!e.detail.question) return
  e.preventDefault()

  Swal.fire({
    title: "Apa anda yakin?",
    text: e.detail.question,
    showCancelButton: true,
    icon: "warning",
    confirmButtonText: "Ya, Saya mengerti"
  }).then(function (result) {
    if (result.isConfirmed) {
      e.detail.issueRequest(true);
    }
  })
})

$(document).ready(function () {
  $(".datepicker").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true,
  });

  $(".mt-datepicker").bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
    format: "YYYY-MM-DD"
  });

  htmx.on("htmx:beforeRequest", function () {
    Swal.fire({
      title: "Mohon Tunggu ... ",
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
      showConfirmButton: false
    })
  })

  htmx.on("htmx:afterRequest", function () {
    Swal.close();
  })
})

$(document).on("htmx:toastr", (evt) => {
  const notifFunc = {
    "info": toastr.info,
    "error": toastr.error,
    "success": toastr.success
  }

  try {
    toast = notifFunc[evt.detail.level]
    toast(evt.detail.message,
      "Notifikasi", {
      positionClass: "toastr toast-bottom-right",
      containerId: "toast-bottom-right",
    })
  } catch (error) {
    console.error("Kesalahan pada toast : ", error);
  }
})

let sseSource;

document.addEventListener("sse:connect", function (evt) {
  sseSource = evt.detail.source;
});

window.addEventListener("beforeunload", () => {
  if (sseSource) {
    sseSource.close();
  }
});