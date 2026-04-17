<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Project Details: <?= esc($project['project_name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
  <a href="<?= base_url('company/projects') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Portfolio</a>
  <div class="d-flex gap-2">
    <a href="<?= base_url('company/projects/edit/'.$project['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i> Edit Project</a>
    <a href="<?= base_url('company/bookings?project_id='.$project['id']) ?>" class="btn btn-info btn-sm text-white"><i class="fas fa-address-book me-1"></i> View Bookings</a>
  </div>
</div>

<div class="row g-4">
  <!-- Left Column: Details & Map -->
  <div class="col-lg-8" data-aos="fade-up">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
          <h4 class="mb-1" style="color:#1e293b;font-weight:700;"><?= esc($project['project_name']) ?></h4>
          <span class="badge bg-primary-subtle text-primary mb-2"><?= esc($project_types[array_search($project['project_type_id'], array_column($project_types, 'id'))]['type_name'] ?? 'Unknown Type') ?></span>
          <div class="info-card">
            <div class="info-label">Address</div>
            <div class="info-value">
              <i class="fas fa-map-marker-alt me-1 text-primary"></i> 
              <span id="displayAddress"><?= esc($project['address']) ?></span>
            </div>
          </div>
        </div>
        <div class="text-end">
          <div style="font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#94a3b8;font-weight:600;">Price Range</div>
          <div style="font-size:22px;color:#0f766e;font-weight:800;">
            ₹<?= number_format($project['price_start'], 0) ?> – ₹<?= number_format($project['price_end'], 0) ?>
          </div>
        </div>
      </div>

      <!-- Google Map Preview -->
      <div id="projectMap" style="height:350px; border-radius:12px; background:#f1f5f9; position:relative; overflow:hidden;">
        <?php if(!$project['latitude'] || !$project['longitude']): ?>
          <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
            <i class="fas fa-map-marked-alt fa-3x mb-2 opacity-25"></i>
            <p>Location coordinates not set.</p>
          </div>
        <?php endif; ?>
      </div>

      <div class="mt-4">
        <h6 class="form-section-title" style="margin-bottom:15px; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">Dynamic Features</h6>
        <div class="row g-3">
          <?php if (empty($dynamic_fields)): ?>
            <div class="col-12"><p class="text-muted">No custom features defined.</p></div>
          <?php else: ?>
            <?php foreach ($dynamic_fields as $field): ?>
              <div class="col-md-6 col-lg-4">
                <div class="p-3 bg-light rounded" style="border-left:3px solid #0f766e;">
                  <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600;"><?= esc($field['field_name']) ?></div>
                  <div style="font-weight:600; color:#1e293b;"><?= esc($field['existing_value'] ?: '—') ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Info -->
  <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card p-4 h-100 shadow-sm" style="border-radius:15px; border:none;">
      <h5 class="mb-4" style="color:#1e293b; font-weight:700;">Location Details</h5>
      <div class="mb-4">
        <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Full Address</label>
        <div style="color:#334155; font-size:15px; line-height:1.6;">
          <i class="fas fa-map-marker-alt text-primary me-2"></i><?= esc($project['address']) ?>
        </div>
      </div>
      
      <h6 class="form-section-title mb-4">Project Overview</h6>
      
      <div class="mb-4">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Status</span>
          <span class="badge" style="background:<?= $project['status']=='Active'?'#dcfce7':'#fee2e2' ?>;color:<?= $project['status']=='Active'?'#14532d':'#7f1d1d' ?>;">
            <?= $project['status'] ?>
          </span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Project Type</span>
          <span style="font-weight:600; color:#1e293b;"><?= esc($project_types[array_search($project['project_type_id'], array_column($project_types, 'id'))]['type_name'] ?? 'Unknown') ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Loaded Inventory</span>
          <span style="font-weight:600;"><?= count($units ?? []) ?> Unit(s)</span>
        </div>
      </div>

      <div class="alert alert-info py-3 px-3 d-flex align-items-start gap-3 shadow-sm" style="border-radius:12px; border:none; background:#eff6ff; color:#1e40af;">
        <i class="fas fa-info-circle mt-1"></i>
        <div style="font-size:13px;">
          <strong>Quick Tip:</strong> Use the "Units Management" section below to track individual inventory and availability.
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Apartment Units Management Section -->
<?php if ($is_apartment): ?>
<div class="card p-4 mt-4" data-aos="fade-up">
  <div class="card-header-bar mb-4 px-0 pt-0" style="border-bottom:none; display:flex; justify-content:space-between; align-items:center;">
    <h5 class="mb-0"><i class="fas fa-th-list me-2 text-primary"></i> Units Management (Inventory)</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUnitModal"><i class="fas fa-plus me-1"></i> Add New Unit</button>
  </div>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="bg-light">
        <tr>
          <th>Unit Name</th>
          <th>Config (BR/BA)</th>
          <th>Area (sq ft)</th>
          <th>Price</th>
          <th>Status</th>
          <th>Images</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($units)): ?>
          <?php foreach($units as $unit): ?>
            <tr>
              <td><div style="font-weight:600; color:#111827;"><?= esc($unit['unit_name']) ?></div></td>
              <td><span style="color:#6b7280;"><?= $unit['bedrooms'] ?> BR / <?= $unit['bathrooms'] ?> BA</span></td>
              <td><?= number_format($unit['area']) ?></td>
              <td><span style="font-weight:600; color:#0f766e;">₹<?= number_format($unit['price']) ?></span></td>
              <td>
                <?php
                  $s = ['Available'=>'#dcfce7,#14532d', 'Sold'=>'#fee2e2,#7f1d1d', 'Booked'=>'#fef9c3,#713f12'];
                  [$bg, $fg] = explode(',', $s[$unit['status']] ?? '#f3f4f6,#374151');
                ?>
                <span class="badge" style="background:<?= $bg ?>;color:<?= $fg ?>; border-radius:20px;"><?= $unit['status'] ?></span>
              </td>
              <td>
                <div class="d-flex gap-1 overflow-auto" style="max-width:180px; padding-bottom:5px;">
                  <?php foreach ($unit['images'] as $img): ?>
                    <div class="position-relative">
                      <img src="<?= base_url('uploads/units/'.$img['image_path']) ?>" style="width:45px;height:45px;border-radius:6px;object-fit:cover; border:1px solid #e2e8f0;">
                      <a href="<?= base_url('company/projects/units/delete-image/'.$img['id']) ?>" class="position-absolute" style="top:-5px; right:-5px; width:18px; height:18px; background:#ef4444; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px;" onclick="return confirm('Remove image?')"><i class="fas fa-times"></i></a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </td>
              <td>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('company/projects/units/delete/'.$unit['id']) ?>" class="btn btn-outline-danger btn-sm" style="border-radius:8px;" onclick="return confirm('Delete this unit?')"><i class="fas fa-trash"></i></a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted py-5">
            <i class="fas fa-boxes fa-2x mb-3 opacity-25"></i>
            <p>No units added to this project yet.</p>
          </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:20px;border:none; box-shadow:0 15px 50px rgba(0,0,0,0.15);">
      <div class="modal-header" style="background:#0f766e;color:white; border-radius:20px 20px 0 0; padding:20px;">
        <h5 class="modal-title"><i class="fas fa-plus-circle me-1"></i> Add New Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('company/projects/units/store/'.$project['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label">Unit Name / Number</label>
            <input type="text" name="unit_name" class="form-control" placeholder="e.g. A-101" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col">
              <label class="form-label">Bedrooms</label>
              <input type="number" name="bedrooms" class="form-control" value="2">
            </div>
            <div class="col">
              <label class="form-label">Bathrooms</label>
              <input type="number" name="bathrooms" class="form-control" value="1">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col">
              <label class="form-label">Area (sq ft)</label>
              <input type="number" name="area" class="form-control" placeholder="Size">
            </div>
            <div class="col">
              <label class="form-label">Price (₹)</label>
              <input type="number" name="price" class="form-control" placeholder="Price">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="Available">Available</option>
              <option value="Sold">Sold</option>
              <option value="Booked">Booked</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Images <small class="text-muted">(Max 5)</small></label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
          </div>
          <div class="mb-0">
            <label class="form-label">Internal Notes</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Floor height, view details etc..."></textarea>
          </div>
        </div>
        <div class="modal-footer p-3 border-0">
          <button type="button" class="btn btn-secondary" style="border-radius:10px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;">Save Unit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function initMap() {
    const lat = <?= $project['latitude'] ?? 'null' ?>;
    const lng = <?= $project['longitude'] ?? 'null' ?>;

    if (lat !== null && lng !== null) {
        const ll = { lat: parseFloat(lat), lng: parseFloat(lng) };
        const map = new google.maps.Map(document.getElementById('projectMap'), {
            center: ll,
            zoom: 15,
            disableDefaultUI: true,
            gestureHandling: 'none',
            zoomControl: false,
            draggable: false
        });
        new google.maps.Marker({ position: ll, map: map });
    }
}
window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
