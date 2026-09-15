<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
  <ul id="sidebarnav">
    <?php foreach (App\Services\MenuService::getMenu() as $i => $section) { ?>
      <?php if (!empty($section->menu_section) && $section->menu_section->is_active == 1 && count($section->menu_section->menu) > 0) { ?>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu"><?= $section->menu_section->header ?></span>
        </li>
        <?php foreach ($section->menu_section->menu as $j => $menu) { ?>
          <li class="sidebar-item">
            <a class="sidebar-link" href="<?= base_url(ltrim($menu->link, '/')) ?>" aria-expanded="false">
              <span>
                <i class="<?= $menu->icon ?>"></i>
              </span>
              <span class="hide-menu"><?= $menu->title ?></span>
            </a>
          </li>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </ul>
</nav>
