<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
  <ul id="sidebarnav">
    <?php foreach (App\Services\MenuService::getMenu() as $i => $section) { ?>
      <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu"><?= $section->menu_section->header ?></span>
      </li>
      <?php foreach ($section->menu_section->menu as $j => $menu) { ?>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= base_url($menu->link) ?>" aria-expanded="false">
            <span>
              <i class="<?= $menu->icon ?>"></i>
            </span>
            <span class="hide-menu"><?= $menu->title ?></span>
          </a>
        </li>
      <?php } ?>
    <?php } ?>
  </ul>
  <!-- <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
    <div class="d-flex">
      <div class="unlimited-access-title me-3">
        <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
        <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
      </div>
      <div class="unlimited-access-img">
        <img src="/assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
      </div>
    </div>
  </div> -->
</nav>