 <h5 class="card-title mb-0">Progress</h5>
 <div class="progress my-3">
   <div id="progress-bar" class="progress-bar progress-bar-striped text-bg-primary progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
   </div>
 </div>
 <div
   hx-ext="sse"
   sse-connect="<?= base_url('sinkron/stream_log') ?>"
   sse-swap="message"
   hx-swap="afterbegin"
   sse-close="message_closed"
   hx-target="#log-area">
   <div
     id="log-area"
     style="height: 500px; overflow: scroll;"
     data-bs-spy="scroll"
     data-bs-root-margin="0px 0px -40%"
     data-bs-smooth-scroll="true"
     class="scrollspy bg-body-tertiary p-3 rounded"
     tabindex="0">
   </div>
 </div>