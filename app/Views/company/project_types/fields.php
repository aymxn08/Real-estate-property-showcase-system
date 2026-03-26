<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Fields: <?= esc($type['type_name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row g-4" data-aos="fade-up">
  <!-- Existing Fields -->
  <div class="col-lg-7">
    <div class="table-wrapper">
      <div class="card-header-bar">
        <h5><i class="fas fa-list me-2 text-primary"></i> Configured Fields</h5>
        <a href="<?= base_url('company/project-types') ?>" class="btn btn-secondary btn-sm">Back</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>Field Name</th>
            <th>Input Type</th>
            <th>Required</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($fields)): ?>
            <?php foreach ($fields as $field): ?>
            <tr>
              <td style="font-weight:600;"><?= esc($field['field_name']) ?></td>
              <td><span class="badge bg-secondary"><?= esc($field['field_type']) ?></span>
                <?php if ($field['field_type'] == 'Dropdown' && $field['options_json']): ?>
                  <div style="font-size:11px;color:#94a3b8;margin-top:3px;"><?= esc($field['options_json']) ?></div>
                <?php endif; ?>
              </td>
              <td><?= $field['is_mandatory'] ? '<span style="color:#dc2626;font-weight:600;">Yes</span>' : '<span style="color:#94a3b8;">No</span>' ?></td>
              <td>
                <a href="<?= base_url('company/project-types/fields/'.$type['id'].'/delete/'.$field['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this field?')">
                  <i class="fas fa-times"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center py-4" style="color:#9ca3af;">No fields configured yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Field Form -->
  <div class="col-lg-5" data-aos="fade-up" data-aos-delay="80">
    <div class="card p-4">
      <div class="form-section-title">Add New Field</div>

      <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('company/project-types/fields/'.$type['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Field Label / Name</label>
          <input type="text" name="field_name" class="form-control" placeholder="e.g. Total Bedrooms" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Input Type</label>
          <select name="field_type" id="fieldType" class="form-select" onchange="toggleOptions()" required>
            <option value="Text">Text Input</option>
            <option value="Number">Number</option>
            <option value="Dropdown">Dropdown (Select list)</option>
            <option value="Checkbox">Checkbox (Yes/No)</option>
          </select>
        </div>
        <div class="mb-3" id="optionsGroup" style="display:none;">
          <label class="form-label">Dropdown Options</label>
          <input type="text" name="options_json" class="form-control" placeholder="East, West, North, South">
          <div class="form-text text-muted">Separate options using commas.</div>
        </div>
        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" name="is_mandatory" id="isMandatory" value="1">
          <label class="form-check-label" for="isMandatory" style="font-size:13.5px;color:#4b5563;">Make this field mandatory</label>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus me-2"></i> Add Field</button>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleOptions() {
  const t = document.getElementById('fieldType').value;
  document.getElementById('optionsGroup').style.display = t === 'Dropdown' ? 'block' : 'none';
}
</script>
<?= $this->endSection() ?>
