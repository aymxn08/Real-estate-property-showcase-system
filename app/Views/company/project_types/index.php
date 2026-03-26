<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Project Types<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
  <p style="color:#6b7280;margin:0;"><?= count($types) ?> type(s) configured</p>
  <a href="<?= base_url('company/project-types/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Type</a>
</div>

<div class="table-wrapper" data-aos="fade-up" data-aos-delay="60">
  <div class="card-header-bar">
    <h5><i class="fas fa-layer-group me-2 text-primary"></i> Project Types</h5>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Type Name</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($types)): ?>
        <?php foreach ($types as $i => $type): ?>
        <tr data-aos="fade-up" data-aos-delay="<?= $i * 30 ?>">
          <td style="color:#94a3b8;font-size:12px;">#<?= $type['id'] ?></td>
          <td style="font-weight:600;"><?= esc($type['type_name']) ?></td>
          <td style="color:#94a3b8;font-size:12px;"><?= date('d M Y', strtotime($type['created_at'])) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= base_url('company/project-types/fields/'.$type['id']) ?>" class="btn btn-info btn-sm"><i class="fas fa-sliders-h me-1"></i> Fields</a>
              <a href="<?= base_url('company/project-types/edit/'.$type['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
              <a href="<?= base_url('company/project-types/delete/'.$type['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this type? All associated projects will be deleted!')"><i class="fas fa-trash"></i></a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center py-5">
            <i class="fas fa-layer-group fa-2x mb-3" style="color:#d1d5db;display:block;"></i>
            <span style="color:#9ca3af;">No project types yet. Create one to get started.</span>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
