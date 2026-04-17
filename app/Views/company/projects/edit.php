<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Edit Project<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
  .pac-container { z-index: 10000 !important; }
</style>
<div class="d-flex justify-content-end mb-4" data-aos="fade-up">
  <a href="<?= base_url('company/projects') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Portfolio</a>
</div>

<div class="card p-4" data-aos="fade-up" data-aos-delay="50">
  <form action="<?= base_url('company/projects/update/'.$project['id']) ?>" method="post">
    <?= csrf_field() ?>

    <?php if(session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <div class="row">
      <!-- Core Details -->
      <div class="col-md-6" style="border-right:1px solid #e4e9f0;padding-right:28px;">
        <div class="form-section-title">Core Details</div>

        <div class="mb-3">
          <label class="form-label">Project Type <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(cannot change)</span></label>
          <?php
            $typeName = '';
            foreach ($project_types as $t) { if ($t['id'] == $project['project_type_id']) $typeName = $t['type_name']; }
          ?>
          <input type="text" class="form-control" value="<?= esc($typeName) ?>" readonly style="background:#f8fafc;">
        </div>

        <div class="mb-3">
          <label class="form-label">Project Name</label>
          <input type="text" name="project_name" class="form-control" value="<?= esc($project['project_name']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Location</label>
          <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;"><i class="fas fa-map-marker-alt" style="color:#0f766e;"></i></span>
            <input type="text" name="address" id="locationField" class="form-control" style="border-radius:0 !important;border-left:none !important;" value="<?= esc(old('address', $project['address'])) ?>" required placeholder="Search or pick on map" onclick="openMapModal()" onkeyup="handleLocationTyping(event)">
            <button class="btn btn-outline-secondary" type="button" style="border-radius:0 8px 8px 0;" onclick="openMapModal()"><i class="fas fa-crosshairs"></i> Pick</button>
          </div>
          <input type="hidden" name="latitude" id="latField" value="<?= esc($project['latitude']) ?>">
          <input type="hidden" name="longitude" id="lngField" value="<?= esc($project['longitude']) ?>">
          
          <!-- Small Preview Map -->
          <div id="formMapPreview" class="mt-2" style="height:120px; border-radius:10px; border:1px solid #e4e9f0; background:#f8fafc; overflow:hidden;"></div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Price Range (Start)</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;">₹</span>
              <input type="number" step="0.01" name="price_start" class="form-control" style="border-left:none !important;border-radius:0 8px 8px 0 !important;" value="<?= esc($project['price_start']) ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Price Range (End)</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;">₹</span>
              <input type="number" step="0.01" name="price_end" class="form-control" style="border-left:none !important;border-radius:0 8px 8px 0 !important;" value="<?= esc($project['price_end']) ?>">
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="Active"   <?= $project['status']=='Active'   ? 'selected':'' ?>>Active (Visible)</option>
            <option value="Inactive" <?= $project['status']=='Inactive' ? 'selected':'' ?>>Inactive (Hidden)</option>
          </select>
        </div>
      </div>

      <!-- Dynamic Fields -->
      <div class="col-md-6" style="padding-left:28px;">
        <div class="form-section-title">Dynamic Features</div>
        <?php if (empty($dynamic_fields)): ?>
          <p class="text-muted" style="font-size:13px;">No custom fields configured for this project type.</p>
        <?php else: ?>
          <?php foreach ($dynamic_fields as $field): ?>
            <?php
              $inputName = 'field_'.$field['id'];
              $val = $field['existing_value'] ?? '';
              $req = $field['is_mandatory'] ? 'required' : '';
            ?>
            <div class="mb-3">
              <label class="form-label">
                <?= esc($field['field_name']) ?>
                <?php if ($field['is_mandatory']): ?><span class="text-danger ms-1">*</span><?php endif; ?>
              </label>

              <?php if ($field['field_type'] == 'Text'): ?>
                <input type="text" name="<?= $inputName ?>" class="form-control" value="<?= esc($val) ?>" <?= $req ?>>
              <?php elseif ($field['field_type'] == 'Number'): ?>
                <input type="number" step="any" name="<?= $inputName ?>" class="form-control" value="<?= esc($val) ?>" <?= $req ?>>
              <?php elseif ($field['field_type'] == 'Checkbox'): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="<?= $inputName ?>" value="1" <?= $val=='Yes' ? 'checked' : '' ?>>
                  <label class="form-check-label" style="font-size:13.5px;">Yes</label>
                </div>
              <?php elseif ($field['field_type'] == 'Dropdown'): ?>
                <select name="<?= $inputName ?>" class="form-select" <?= $req ?>>
                  <option value="">— Select —</option>
                  <?php foreach (explode(',', $field['options_json']) as $opt): ?>
                    <?php $o = trim($opt); ?>
                    <option value="<?= esc($o) ?>" <?= $val==$o ? 'selected' : '' ?>><?= esc($o) ?></option>
                  <?php endforeach; ?>
                </select>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <hr style="margin:24px 0;">
    <div class="d-flex justify-content-end">
      <button type="submit" class="btn btn-warning px-5"><i class="fas fa-save me-2"></i> Update Project</button>
    </div>
  </form>
</div>

<!-- Apartment Units Management Section -->
<?php if ($is_apartment): ?>
<div class="card p-4 mt-4" data-aos="fade-up">
  <div class="card-header-bar mb-4 px-0 pt-0" style="border-bottom:none; display:flex; justify-content:space-between; align-items:center;">
    <h5 class="mb-0"><i class="fas fa-th-list me-2 text-primary"></i> Units Management (Inventory)</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUnitModal"><i class="fas fa-plus me-1"></i> Add Unit</button>
  </div>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Unit</th>
          <th>Size</th>
          <th>Area</th>
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
              <td><div style="font-weight:600;"><?= esc($unit['unit_name']) ?></div></td>
              <td style="font-size:13px;"><?= $unit['bedrooms'] ?> BR / <?= $unit['bathrooms'] ?> BA</td>
              <td style="font-size:13px;"><?= number_format($unit['area']) ?> sq ft</td>
              <td style="font-weight:600;color:#0f766e;">₹<?= number_format($unit['price']) ?></td>
              <td>
                <?php
                  $st = ['Available'=>'#dcfce7,#14532d', 'Sold'=>'#fee2e2,#7f1d1d', 'Booked'=>'#fef9c3,#713f12'];
                  [$bg, $fg] = explode(',', $st[$unit['status']] ?? '#f3f4f6,#374151');
                ?>
                <span class="badge" style="background:<?= $bg ?>;color:<?= $fg ?>;font-weight:500;"><?= $unit['status'] ?></span>
              </td>
              <td>
                <div class="d-flex gap-1 overflow-auto pb-1" style="max-width:150px;">
                  <?php foreach ($unit['images'] as $img): ?>
                    <div class="position-relative shadow-sm" style="flex:0 0 40px;">
                      <img src="<?= base_url('uploads/units/'.$img['image_path']) ?>" style="width:40px;height:40px;border-radius:6px;object-fit:cover;">
                      <a href="<?= base_url('company/projects/units/delete-image/'.$img['id']) ?>" class="position-absolute top-0 start-0 badge bg-danger p-1 rounded-circle" onclick="return confirm('Remove image?')" style="transform:translate(-30%, -30%); border:2px solid white;"><i class="fas fa-times" style="font-size:7px;"></i></a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </td>
              <td>
                <a href="<?= base_url('company/projects/units/delete/'.$unit['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Delete this unit?')"><i class="fas fa-trash-alt"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted py-5">
            <i class="fas fa-folder-open fa-3x mb-3 d-block" style="opacity:0.2;"></i>
            No units added yet. Click "Add Unit" to start.
          </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg" style="border-radius:20px; border:none;">
      <div class="modal-header border-0 pb-0" style="background:white;">
        <h5 class="modal-title" style="color:#0f766e; font-weight:700;">Add New Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('company/projects/units/store/'.$project['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label fw-600">Unit Name / Number</label>
            <input type="text" name="unit_name" class="form-control rounded-8" placeholder="e.g. A-101" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-600">Bedrooms</label>
              <input type="number" name="bedrooms" class="form-control rounded-8" value="2">
            </div>
            <div class="col-6">
              <label class="form-label fw-600">Bathrooms</label>
              <input type="number" name="bathrooms" class="form-control rounded-8" value="1">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-600">Area (sq ft)</label>
              <input type="number" name="area" class="form-control rounded-8" placeholder="0">
            </div>
            <div class="col-6">
              <label class="form-label fw-600">Price (₹)</label>
              <input type="number" name="price" class="form-control rounded-8" placeholder="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Current Status</label>
            <select name="status" class="form-select rounded-8">
              <option value="Available">Available</option>
              <option value="Sold">Sold</option>
              <option value="Booked">Booked</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Unit Gallery</label>
            <input type="file" name="images[]" class="form-control rounded-8" multiple accept="image/*">
            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i> You can select multiple images.</small>
          </div>
          <div class="mb-0">
            <label class="form-label fw-600">Additional Description</label>
            <textarea name="description" class="form-control rounded-8" rows="3" placeholder="Detail standard features, finishes..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-5">Save Inventory</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Map Selection Modal -->
<div class="modal fade" id="mapModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:20px; overflow:hidden; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
      <div class="modal-header" style="background:#0f766e; color:white; border:none; padding:20px 25px;">
        <h5 class="modal-title fw-700"><i class="fas fa-map-marker-alt me-2"></i> Choose Project Location</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Search Bar -->
        <div class="p-3" style="background:#f8fafc; border-bottom:1px solid #e4e9f0;">
          <div class="input-group">
            <input type="text" id="mapSearchInput" class="form-control" placeholder="Type a city or area and press Search..." style="font-size:14px;">
            <button class="btn btn-success px-4" type="button" onclick="doSearch()"><i class="fas fa-search me-1"></i> Search</button>
          </div>
          <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i> Or click anywhere on the map to drop a pin. Drag the pin to adjust.</small>
        </div>
        <!-- Map Container -->
        <div id="map" style="height:430px; width:100%;"></div>
      </div>
      <div class="modal-footer bg-light" style="padding:15px 25px;">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmLocBtn" class="btn btn-success rounded-pill px-5" onclick="confirmLocation()" disabled><i class="fas fa-check-circle me-1"></i> Confirm Location</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let map, marker, autocomplete, gMapPreview;
let selectedLat = <?= !empty($project['latitude']) ? esc($project['latitude']) : '20.5937' ?>;
let selectedLng = <?= !empty($project['longitude']) ? esc($project['longitude']) : '78.9629' ?>;
let selectedAddr = <?= json_encode($project['address']) ?>;

function openMapModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('mapModal')).show();
}

document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('mapModal');
  const input = document.getElementById('mapSearchInput');

  function initMapComponents() {
    if (typeof google === 'undefined') return;

    // Initialize Autocomplete
    autocomplete = new google.maps.places.Autocomplete(input, {
      componentRestrictions: { country: "in" },
      fields: ["geometry", "formatted_address"]
    });

    autocomplete.addListener('place_changed', function () {
      const place = autocomplete.getPlace();
      if (!place.geometry) return;

      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }
      
      marker.setPosition(place.geometry.location);
      updateLocationData(place.geometry.location.lat(), place.geometry.location.lng(), place.formatted_address);
    });
  }

  if (typeof google !== 'undefined') {
      initMapComponents();
  } else {
      window.addEventListener('load', initMapComponents);
  }

  modalEl.addEventListener('shown.bs.modal', function () {
    if (typeof google === 'undefined') return;

    if (!map) {
      map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: selectedLat, lng: selectedLng },
        zoom: (selectedLat != 20.5937) ? 15 : 5,
        mapTypeControl: false,
        streetViewControl: false
      });

      marker = new google.maps.Marker({
        position: { lat: selectedLat, lng: selectedLng },
        map: map,
        draggable: true
      });

      map.addListener('click', function (e) {
        marker.setPosition(e.latLng);
        reverseGeocode(e.latLng);
      });

      marker.addListener('dragend', function () {
        reverseGeocode(marker.getPosition());
      });

      input.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
              e.preventDefault();
              google.maps.event.trigger(autocomplete, 'place_changed');
          }
      });
      
      if (selectedAddr) {
        input.value = selectedAddr;
        document.getElementById('confirmLocBtn').disabled = false;
      }
    } else {
      google.maps.event.trigger(map, 'resize');
      map.setCenter({ lat: selectedLat, lng: selectedLng });
    }
  });

  if (selectedLat != 20.5937 && selectedLng != 78.9629) {
    const pDiv = document.getElementById('formMapPreview');
    if (pDiv) pDiv.style.display = 'block';
    updateFormPreviewMap(selectedLat, selectedLng);
  }
});

function reverseGeocode(latLng) {
  if (typeof google === 'undefined') return;
  const geocoder = new google.maps.Geocoder();
  geocoder.geocode({ location: latLng }, function (results, status) {
    if (status === 'OK' && results[0]) {
      updateLocationData(latLng.lat(), latLng.lng(), results[0].formatted_address);
    }
  });
}

function updateLocationData(lat, lng, address) {
  selectedLat = lat;
  selectedLng = lng;
  selectedAddr = address;
  document.getElementById('mapSearchInput').value = address;
  document.getElementById('confirmLocBtn').disabled = false;
}

function confirmLocation() {
  document.getElementById('latField').value = selectedLat;
  document.getElementById('lngField').value = selectedLng;
  document.getElementById('locationField').value = selectedAddr;
  updateFormPreviewMap(selectedLat, selectedLng);
  bootstrap.Modal.getInstance(document.getElementById('mapModal')).hide();
}

function updateFormPreviewMap(lat, lng) {
  const ll = { lat: parseFloat(lat), lng: parseFloat(lng) };
  if (!gMapPreview) {
    gMapPreview = new google.maps.Map(document.getElementById('formMapPreview'), {
      center: ll,
      zoom: 15,
      disableDefaultUI: true,
      gestureHandling: 'none'
    });
    new google.maps.Marker({ position: ll, map: gMapPreview });
  } else {
    gMapPreview.setCenter(ll);
  }
}

function handleLocationTyping(e) {
  if (e.key === 'Enter') { e.preventDefault(); openMapModal(); }
}
</script>
<?= $this->endSection() ?>
