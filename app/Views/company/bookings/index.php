<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Booking Inquiries<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2" data-aos="fade-up">
  <p style="color:#6b7280;margin:0;"><?= count($bookings) ?> booking(s) found</p>
  <form action="<?= base_url('company/bookings') ?>" method="get" class="d-flex gap-2 align-items-center">
    <select name="project_id" class="form-select" style="width:200px;" onchange="this.form.submit()">
      <option value="">All Projects</option>
      <?php foreach ($projects as $proj): ?>
        <option value="<?= $proj['id'] ?>" <?= $filter_project == $proj['id'] ? 'selected' : '' ?>><?= esc($proj['project_name']) ?></option>
      <?php endforeach; ?>
    </select>
    <?php if ($filter_project): ?>
      <a href="<?= base_url('company/bookings') ?>" class="btn btn-secondary btn-sm">Clear</a>
    <?php endif; ?>
  </form>
</div>

<div class="table-wrapper" data-aos="fade-up" data-aos-delay="60">
  <div class="card-header-bar">
    <h5><i class="fas fa-address-book me-2 text-primary"></i> Lead Inquiries</h5>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Customer</th>
        <th>Contact</th>
        <th>Project</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($bookings)): ?>
        <?php foreach ($bookings as $i => $booking): ?>
        <tr data-aos="fade-up" data-aos-delay="<?= $i * 30 ?>">
          <td style="color:#94a3b8;font-size:12px;white-space:nowrap;"><?= date('d M Y\nh:i A', strtotime($booking['created_at'])) ?></td>
          <td>
            <div style="font-weight:600;"><?= esc($booking['customer_name']) ?></div>
          </td>
          <td>
            <a href="mailto:<?= esc($booking['customer_email']) ?>" style="color:#0f766e;text-decoration:none;font-size:13px;display:block;"><i class="fas fa-envelope me-1"></i><?= esc($booking['customer_email']) ?></a>
            <a href="tel:<?= esc($booking['customer_phone']) ?>" style="color:#16a34a;text-decoration:none;font-size:13px;display:block;"><i class="fas fa-phone me-1"></i><?= esc($booking['customer_phone']) ?></a>
          </td>
          <td><span class="badge bg-secondary"><?= esc($booking['project_name']) ?></span></td>
          <td>
            <?php
              $b = ['New'=>'#dbeafe,#1e3a8a', 'Contacted'=>'#fef9c3,#713f12', 'Closed'=>'#dcfce7,#14532d', 'Cancelled'=>'#fee2e2,#7f1d1d'];
              [$bg, $fg] = explode(',', $b[$booking['status']] ?? '#f3f4f6,#374151');
            ?>
            <span class="badge" style="background:<?= $bg ?>;color:<?= $fg ?>;"><?= $booking['status'] ?></span>
          </td>
          <td>
            <form action="<?= base_url('company/bookings/update-status/'.$booking['id']) ?>" method="post" class="d-inline-flex gap-1">
              <?= csrf_field() ?>
              <select name="status" class="form-select form-select-sm" style="width:120px;" onchange="this.form.submit()">
                <option value="New"       <?= $booking['status']=='New'       ? 'selected':'' ?>>New</option>
                <option value="Contacted" <?= $booking['status']=='Contacted' ? 'selected':'' ?>>Contacted</option>
                <option value="Closed"    <?= $booking['status']=='Closed'    ? 'selected':'' ?>>Closed</option>
                <option value="Cancelled" <?= $booking['status']=='Cancelled' ? 'selected':'' ?>>Cancelled</option>
              </select>
            </form>
            <a href="<?= base_url('company/bookings/delete/'.$booking['id']) ?>" class="btn btn-danger btn-sm ms-1" onclick="return confirm('Delete this booking?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center py-5">
            <i class="fas fa-inbox fa-2x mb-3" style="color:#d1d5db;display:block;"></i>
            <span style="color:#9ca3af;">No booking inquiries found.</span>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
