<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Projects Portfolio<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
  <p style="color:#6b7280;margin:0;"><?= count($projects) ?> project(s) in your portfolio</p>
  <a href="<?= base_url('company/projects/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Project</a>
</div>

<div class="table-wrapper" data-aos="fade-up" data-aos-delay="60">
  <div class="card-header-bar">
    <h5><i class="fas fa-city me-2 text-primary"></i> Project Portfolio</h5>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Project</th>
        <th>Type</th>
        <th>Location</th>
        <th>Price Range</th>
        <th>Units</th>
        <th>Status</th>
        <th>Bookings</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $i => $project): ?>
        <tr data-aos="fade-up" data-aos-delay="<?= $i * 25 ?>">
          <td>
            <div style="font-weight:600;"><?= esc($project['project_name']) ?></div>
          </td>
          <td><span class="badge bg-secondary"><?= esc($project['type_name']) ?></span></td>
          <td style="color:#6b7280;">
            <i class="fas fa-map-marker-alt me-1" style="color:#0f766e;"></i><?= esc($project['address']) ?>
            <?php if($project['latitude'] && $project['longitude']): ?>
              <a href="https://www.google.com/maps?q=<?= $project['latitude'] ?>,<?= $project['longitude'] ?>" target="_blank" class="ms-1" title="View on Map"><i class="fas fa-external-link-alt" style="font-size:10px;"></i></a>
            <?php endif; ?>
          </td>
          <td style="font-weight:600;color:#0f766e;">
            <?php if($project['price_start']): ?>
              ₹<?= number_format($project['price_start'], 0) ?> — ₹<?= number_format($project['price_end'], 0) ?>
            <?php else: ?>
              <span style="color:#d1d5db;">—</span>
            <?php endif; ?>
          </td>
          <td><?= $project['number_of_units'] ?? '—' ?></td>
          <td>
            <?php if ($project['status'] == 'Active'): ?>
              <span class="badge" style="background:#dcfce7;color:#14532d;"><i class="fas fa-check-circle me-1"></i>Active</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#7f1d1d;"><i class="fas fa-pause-circle me-1"></i>Inactive</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= base_url('company/bookings?project_id='.$project['id']) ?>" style="color:#0284c7;font-weight:600;text-decoration:none;font-size:13px;">
              <i class="fas fa-address-book me-1"></i><?= $project['total_bookings'] ?>
            </a>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= base_url('company/projects/view/'.$project['id']) ?>" class="btn btn-info btn-sm text-white" title="View Details"><i class="fas fa-eye"></i></a>
              <a href="<?= base_url('company/projects/edit/'.$project['id']) ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="<?= base_url('company/projects/delete/'.$project['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project? All bookings will also be removed!')" title="Delete"><i class="fas fa-trash"></i></a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center py-5">
            <i class="fas fa-city fa-2x mb-3" style="color:#d1d5db;display:block;"></i>
            <span style="color:#9ca3af;">No projects yet. Click "New Project" to get started.</span>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
