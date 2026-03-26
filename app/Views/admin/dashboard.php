<?= $this->extend('admin/layout') ?>
<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="dashboard-hero" data-aos="fade-up">
  <div class="dashboard-hero-bg"></div>
  <div class="dashboard-hero-overlay"></div>
  <div class="dashboard-hero-content">
    <h2><i class="fas fa-shield-alt me-2"></i> Super Admin Dashboard</h2>
    <p>Platform-wide overview — manage all registered real estate companies and system health.</p>
  </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
  <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="0">
    <div class="stat-card gradient-teal">
      <div class="stat-icon"><i class="fas fa-city"></i></div>
      <div class="stat-value"><?= $total_companies ?></div>
      <div class="stat-label">Total Companies</div>
      <i class="fas fa-city stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="80">
    <div class="stat-card gradient-gold">
      <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
      <div class="stat-value"><?= $pending_companies ?></div>
      <div class="stat-label">Pending Approvals</div>
      <i class="fas fa-hourglass-half stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="160">
    <div class="stat-card gradient-blue">
      <div class="stat-icon"><i class="fas fa-home"></i></div>
      <div class="stat-value"><?= $total_projects ?></div>
      <div class="stat-label">Total Projects</div>
      <i class="fas fa-home stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="240">
    <div class="stat-card gradient-purple">
      <div class="stat-icon"><i class="fas fa-address-book"></i></div>
      <div class="stat-value"><?= $total_bookings ?></div>
      <div class="stat-label">Total Bookings</div>
      <i class="fas fa-address-book stat-bg"></i>
    </div>
  </div>
</div>

<!-- Quick Action -->
<div class="card p-4" data-aos="fade-up" data-aos-delay="100">
  <div class="card-header-bar mb-0 px-0 pt-0" style="border:none;">
    <h5><i class="fas fa-bolt me-2 text-warning"></i> Quick Actions</h5>
  </div>
  <hr style="margin:14px 0 18px;">
  <div class="d-flex gap-3 flex-wrap">
    <a href="<?= base_url('super-admin/companies') ?>" class="btn btn-primary">
      <i class="fas fa-city"></i> View All Companies
    </a>
  </div>
</div>

<?= $this->endSection() ?>
