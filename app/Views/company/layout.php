<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Dashboard | Real Estate SaaS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="logo-wrapper">
      <div class="logo-icon"><i class="fas fa-home"></i></div>
      <div class="brand-text">
        <span class="brand-name" title="<?= esc(session()->get('company_name')) ?>"><?= esc(session()->get('company_name') ?? 'Company') ?></span>
        <span class="brand-role">Partner Portal</span>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">Overview</div>
    <a href="<?= base_url('company/dashboard') ?>" class="<?= uri_string() == 'company/dashboard' ? 'active' : '' ?>">
      <i class="fas fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= base_url('company/profile') ?>" class="<?= uri_string() == 'company/profile' ? 'active' : '' ?>">
      <i class="fas fa-building"></i> Company Info
    </a>

    <div class="nav-label">Projects</div>
    <a href="<?= base_url('company/project-types') ?>" class="<?= uri_string() == 'company/project-types' || strpos(uri_string(), 'company/project-types') === 0 ? 'active' : '' ?>">
      <i class="fas fa-layer-group"></i> Project Types
    </a>
    <a href="<?= base_url('company/projects/create') ?>" class="<?= uri_string() == 'company/projects/create' ? 'active' : '' ?>">
      <i class="fas fa-plus-circle"></i> New Project
    </a>
    <a href="<?= base_url('company/projects') ?>" class="<?= uri_string() == 'company/projects' ? 'active' : '' ?>">
      <i class="fas fa-city"></i> Portfolio
    </a>

    <div class="nav-label">CRM</div>
    <a href="<?= base_url('company/enquiries') ?>" class="<?= uri_string() == 'company/enquiries' ? 'active' : '' ?>">
      <i class="fas fa-envelope-open-text"></i> Enquiries
    </a>
    <a href="<?= base_url('company/bookings') ?>" class="<?= uri_string() == 'company/bookings' ? 'active' : '' ?>">
      <i class="fas fa-address-book"></i> Bookings
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="<?= base_url('company/logout') ?>">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</aside>

<div class="main-content">
  <div class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
      <div class="topbar-left">
        <h2><?= $this->renderSection('pageTitle') ?: 'Dashboard' ?></h2>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge" style="background:#dbeafe;color:#1e3a8a;padding:7px 14px;border-radius:20px;font-size:12px;">
        <i class="fas fa-user-tie me-1"></i> <?= esc(session()->get('company_name') ?? '') ?>
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
  <style>
    .pac-container { z-index: 10000 !important; }
  </style>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVqq0MML96Rzal70xtUDWtO8W5RHM2WT8Y&libraries=places"></script>
<script>
  AOS.init({ duration: 550, once: true, offset: 40 });
  function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebarOverlay').classList.add('open'); }
  function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('open'); }
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
