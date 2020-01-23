<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $urlaplikasi;?>index.php?=home">
        <div class="sidebar-brand-icon">
          <i class="fa fa-shopping-basket" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">AKHSAN MART</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $urlaplikasi;?>index.php?=home">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        MENU APP
      </div>

      <!-- Nav Item - Data Barang -->
      <li class="nav-item <?php if($current_page=='index.php?=barang' or $current_page=='index.php?=update_barang'){echo 'active';}?>">
        <a class="nav-link" href="<?php echo $urlaplikasi;?>index.php?=barang" >
          <i class="fa fa-database"></i>
          <span>Data Barang</span></a>
      </li>

      <!-- Nav Item - Data Transaksi -->
      <li class="nav-item <?php if($current_page=='index.php?=transaksi' or $current_page=='index.php?=update_transaksi'){echo 'active';}?>">
        <a class="nav-link" href="<?php echo $urlaplikasi;?>index.php?=transaksi">
          <i class="fa fa-table"></i>
          <span>Data Transaksi</span></a>
      </li>

      <!-- Nav Item - Analisis FP-Growth -->
      <li class="nav-item <?php if($current_page=='index.php?=analisa'){echo 'active';}?>">
        <a class="nav-link" href="<?php echo $urlaplikasi;?>index.php?=analisa">
          <i class="fa fa-calculator" aria-hidden="true"></i>
          <span>Analisis FP-Growth</span></a>
      </li>

      <!-- Nav Item - Logout -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $urlaplikasi;?>logout.php">
          <i class="fa fa-arrow-circle-left"></i>
          <span>Logout</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->