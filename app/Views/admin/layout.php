<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Harxa Tech | Super Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="logo-wrapper">
      <div class="logo-icon"><i class="fas fa-building"></i></div>
      <div class="brand-text">
        <span class="brand-name">Harxa Tech</span>
        <span class="brand-role">Super Admin</span>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">Main</div>
    <a href="<?= base_url('super-admin/dashboard') ?>" class="<?= uri_string() == 'super-admin/dashboard' ? 'active' : '' ?>">
      <i class="fas fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= base_url('super-admin/companies') ?>" class="<?= uri_string() == 'super-admin/companies' ? 'active' : '' ?>">
      <i class="fas fa-city"></i> Companies
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="<?= base_url('super-admin/logout') ?>">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</aside>

<!-- Main Content -->
<div class="main-content">
  <div class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
      <div class="topbar-left">
        <h2><?= $this->renderSection('pageTitle') ?: 'Dashboard' ?></h2>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge" style="background:#dcfce7;color:#14532d;padding:7px 14px;border-radius:20px;font-size:12px;">
        <i class="fas fa-shield-alt me-1"></i> Super Admin
      </span>
    </div>
  </div>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" data-aos="fade-down">
      <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" data-aos="fade-down">
      <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 550, once: true, offset: 40 });
  function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebarOverlay').classList.add('open'); }
  function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('open'); }
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
