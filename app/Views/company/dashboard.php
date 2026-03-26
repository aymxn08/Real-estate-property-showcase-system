<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Banner with Ken Burns -->
<div class="dashboard-hero" data-aos="fade-up">
  <div class="dashboard-hero-bg" style="background: linear-gradient(135deg, #0b1e37 0%, #0a3d35 100%);"></div>
  <div class="dashboard-hero-overlay"></div>
  <div class="dashboard-hero-content">
    <h2><i class="fas fa-home me-2"></i> Welcome back, <?= esc(session()->get('company_name')) ?>!</h2>
    <p>Here's a snapshot of your real estate portfolio performance today.</p>
  </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
  <div class="col-6 col-xl-4" data-aos="fade-up" data-aos-delay="0">
    <div class="stat-card gradient-teal">
      <div class="stat-icon"><i class="fas fa-city"></i></div>
      <div class="stat-value"><?= $total_projects ?></div>
      <div class="stat-label">Total Projects</div>
      <i class="fas fa-city stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-xl-4" data-aos="fade-up" data-aos-delay="80">
    <div class="stat-card gradient-blue">
      <div class="stat-icon"><i class="fas fa-address-book"></i></div>
      <div class="stat-value"><?= $total_bookings ?></div>
      <div class="stat-label">Total Bookings</div>
      <i class="fas fa-address-book stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-xl-4" data-aos="fade-up" data-aos-delay="160">
    <div class="stat-card gradient-gold">
      <div class="stat-icon"><i class="fas fa-bell"></i></div>
      <div class="stat-value"><?= $new_bookings ?></div>
      <div class="stat-label">New Bookings (Action Needed)</div>
      <i class="fas fa-bell stat-bg"></i>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
  <div class="col-md-6" data-aos="fade-up" data-aos-delay="50">
    <div class="card p-4">
      <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-bottom:16px;">Quick Actions</div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('company/projects/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Project</a>
        <a href="<?= base_url('company/project-types') ?>" class="btn btn-secondary"><i class="fas fa-layer-group"></i> Manage Types</a>
        <a href="<?= base_url('company/bookings') ?>" class="btn btn-warning"><i class="fas fa-address-book"></i> View Bookings</a>
      </div>
    </div>
  </div>
  <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
    <div class="card p-4">
      <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-bottom:16px;">Company Health</div>
      <div style="font-size:13.5px;color:#4b5563;">
        <?php if($total_projects == 0): ?>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-info-circle text-warning"></i> No projects yet. Start by creating a Project Type, then add your first Project!
          </div>
        <?php elseif($new_bookings > 0): ?>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i> You have <strong><?= $new_bookings ?></strong> new booking(s) that need attention.
          </div>
        <?php else: ?>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-check-circle" style="color:#16a34a;"></i> All bookings are up to date. Great work!
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
