<?php
include('../includes/conn.php');

// Team Leaders - Fetch this data BEFORE defining the navigation array
$teamLeadersResult = $conn->query("SELECT id, name FROM team_leaders");
$teamLeaders = [];
if ($teamLeadersResult) {
  while ($row = $teamLeadersResult->fetch_assoc()) {
    $teamLeaders[] = $row;
  }
  // No need to close $teamLeadersResult, it's already consumed.
}

$requestUri = $_SERVER['REQUEST_URI'];

// Define your navigation items in an associative array
$navItems = [
  [
    'label' => 'Dashboard',
    'icon' => 'dashboard',
    'href' => 'index.php',
    'type' => 'link'
  ],
  [
    'label' => 'Overview',
    'icon' => 'overview',
    'href' => 'overview.php',
    'type' => 'link'
  ],
  [
    'label' => 'Teams Statistics',
    'icon' => 'table_view',
    'href' => '#teamsSubmenu', // Target for collapse
    'type' => 'collapse',
    'id' => 'teamsSubmenu',
    'submenu_items' => $teamLeaders // Add the fetched team leaders here
  ],
  // You can add more main navigation items here
];
?>


<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2 d-print-none" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="index.php">
      <img src="assets/img/logo.png" class="navbar-brand-img" width="64" height="26" alt="main_logo">
      <span class="ms-1 text-sm text-uppercase text-dark"><strong>BT|EE Credits</strong></span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <?php foreach ($navItems as $item): ?>
        <?php
        // Determine if the current item is active
        $isActive = false;
        if ($item['type'] === 'link') {
          // Compare the href with the current request URI
          // We use basename() to compare just the filename part for simple links
          // For example, if $requestUri is "/myproject/index.php" and href is "index.php"
          if (strpos($requestUri, $item['href']) !== false && basename($requestUri) === $item['href']) {
            $isActive = true;
          }
        } elseif ($item['type'] === 'collapse') {
          // For collapse items, check if any submenu item's href is active
          // This is a basic check; you might need more complex logic
          foreach ($item['submenu_items'] as $subItem) {
            // Construct the full expected URI for comparison
            $expectedSubUri = 'team.php?id=' . $subItem['id'];
            if (strpos($requestUri, $expectedSubUri) !== false) {
              $isActive = true; // Mark parent as active if any child is active
              break;
            }
          }
        }

        $activeClass = $isActive ? ' active bg-dark text-white' : ' text-dark';
        ?>

        <li class="nav-item">
          <?php if ($item['type'] === 'link'): ?>
            <a class="nav-link<?= $activeClass ?>" href="<?= htmlspecialchars($item['href']) ?>">
              <i class="material-symbols-rounded opacity-5"><?= htmlspecialchars($item['icon']) ?></i>
              <span class="nav-link-text ms-1"><?= htmlspecialchars($item['label']) ?></span>
            </a>
          <?php elseif ($item['type'] === 'collapse'): ?>
            <a class="nav-link<?= $activeClass ?>" data-bs-toggle="collapse" href="#<?= htmlspecialchars($item['id']) ?>" role="button" aria-expanded="<?= $isActive ? 'true' : 'false' ?>" aria-controls="<?= htmlspecialchars($item['id']) ?>">
              <i class="material-symbols-rounded opacity-5"><?= htmlspecialchars($item['icon']) ?></i>
              <span class="nav-link-text ms-1"><?= htmlspecialchars($item['label']) ?></span>
              <i class="material-symbols-rounded float-end opacity-5">expand_more</i>
            </a>
            <div class="collapse ps-4 <?= $isActive ? 'show' : '' ?>" id="<?= htmlspecialchars($item['id']) ?>">
              <ul class="navbar-nav">
                <?php foreach ($item['submenu_items'] as $subItem): ?>
                  <?php
                  $subItemHref = "team.php?id=" . $subItem['id'];
                  $isSubItemActive = (strpos($requestUri, $subItemHref) !== false);
                  $subItemActiveClass = $isSubItemActive ? ' active bg-dark text-white' : ' text-dark';
                  ?>
                  <li class="nav-item">
                    <a class="nav-link<?= $subItemActiveClass ?>" href="<?= htmlspecialchars($subItemHref) ?>">
                      <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                      <span><?= htmlspecialchars($subItem['name']) ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="">
          <i class="material-symbols-rounded opacity-5">manage_accounts</i>
          <span class="nav-link-text ms-1">Profile</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    <div class="mx-3">
      <a class="btn bg-gradient-dark w-100" href="#" type="button">Sign Out</a>
    </div>
  </div>
</aside>