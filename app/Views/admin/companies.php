<?= $this->extend('admin/layout') ?>
<?= $this->section('pageTitle') ?>Companies<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="table-wrapper" data-aos="fade-up">
  <div class="card-header-bar">
    <h5><i class="fas fa-city me-2 text-primary"></i> Registered Companies</h5>
    <span class="badge bg-secondary"><?= count($companies) ?> total</span>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Company Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Registered</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($companies)): ?>
        <?php foreach ($companies as $i => $company): ?>
        <tr data-aos="fade-up" data-aos-delay="<?= $i * 30 ?>">
          <td style="color:#94a3b8;font-size:12px;">#<?= $company['id'] ?></td>
          <td>
            <div style="font-weight:600;"><?= esc($company['company_name']) ?></div>
            <?php if($company['address']): ?>
              <div style="font-size:12px;color:#94a3b8;"><i class="fas fa-map-marker-alt me-1"></i><?= esc($company['address']) ?></div>
            <?php endif; ?>
          </td>
          <td><a href="mailto:<?= esc($company['email']) ?>" style="color:#0f766e;text-decoration:none;"><?= esc($company['email']) ?></a></td>
          <td><?= esc($company['contact_number']) ?></td>
          <td>
            <?php if ($company['status'] == 'Approved'): ?>
              <span class="badge" style="background:#dcfce7;color:#14532d;"><i class="fas fa-check-circle me-1"></i>Approved</span>
            <?php elseif ($company['status'] == 'Pending'): ?>
              <span class="badge" style="background:#fef9c3;color:#713f12;"><i class="fas fa-clock me-1"></i>Pending</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#7f1d1d;"><i class="fas fa-ban me-1"></i>Suspended</span>
            <?php endif; ?>
          </td>
          <td style="color:#94a3b8;font-size:12px;"><?= date('d M Y', strtotime($company['created_at'])) ?></td>
          <td>
            <form action="<?= base_url('super-admin/companies/update-status/'.$company['id']) ?>" method="post" class="d-inline">
              <?= csrf_field() ?>
              <select name="status" class="form-select form-select-sm d-inline-block" style="width:130px;" onchange="this.form.submit()">
                <option value="Pending"   <?= $company['status'] == 'Pending'   ? 'selected' : '' ?>>Pending</option>
                <option value="Approved"  <?= $company['status'] == 'Approved'  ? 'selected' : '' ?>>Approve</option>
                <option value="Suspended" <?= $company['status'] == 'Suspended' ? 'selected' : '' ?>>Suspend</option>
              </select>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center py-5">
            <i class="fas fa-city fa-2x mb-3" style="color:#d1d5db;display:block;"></i>
            <span style="color:#9ca3af;">No companies have registered yet.</span>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
