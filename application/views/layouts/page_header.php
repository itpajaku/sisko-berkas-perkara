<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-9">
        <h4 class="fw-semibold mb-8"><?= $page_name ?></h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) : ?>
              <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['name'] ?></a>
              </li>
            <?php endforeach; ?>
          </ol>
        </nav>
      </div>
      <div class="col-3">
        <div class="text-center mb-n5">
          <img src="/assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4" />
        </div>
      </div>
    </div>
  </div>
</div>