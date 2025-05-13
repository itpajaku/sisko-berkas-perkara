<div class="container-fluid">
    <?= $this->load->view("layouts/page_header", [
        "breadcrumbs" => [
            ["name" => "Berkas Gugatan", "url" => "/berkas_gugatan"],
            ["name" => "Create Berkas Gugatan", "url" => "/berkas_gugatan/create"]
        ],
        "page_name" => "Berkas Gugatan",
    ], true) ?>
</div>
<section class="py-3 py-md-5">
    <div class="container bg-primary-subtle">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">

                <div class="card widget-card bsb-timeline-8 border-light shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title widget-card-title mb-3">Recent Transactions</h5>

                        <ul class="timeline">
                            <li class="timeline-item">
                                <div class="timeline-body">
                                    <div class="timeline-meta">
                                        <span>32 minutes</span>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <h6 class="mb-1">Amount received in the PayPal gateway.</h6>
                                        <span class="text-secondary fs-7">User: William Lucas</span>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="timeline-body">
                                    <div class="timeline-meta">
                                        <span>49 minutes</span>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <h6 class="mb-1">New sale recorded in the Bootstrap admin templates.</h6>
                                        <span class="text-secondary fs-7">Product: Console</span>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="timeline-body">
                                    <div class="timeline-meta">
                                        <span>2 hours</span>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <h6 class="mb-1">User registered in the discount campaign.</h6>
                                        <span class="text-secondary fs-7">Country: United States</span>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="timeline-body">
                                    <div class="timeline-meta">
                                        <span>19 hours</span>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <h6 class="mb-1">Ticket created about the SSL certificate of the domain.</h6>
                                        <span class="text-secondary fs-7">Issue: Technical</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>

</script>