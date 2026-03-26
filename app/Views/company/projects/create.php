<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>New Project<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
  .pac-container { z-index: 10000 !important; }
</style>
<div class="d-flex justify-content-end mb-4" data-aos="fade-up">
  <a href="<?= base_url('company/projects') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Portfolio</a>
</div>

<div class="card p-4" data-aos="fade-up" data-aos-delay="50">
  <form action="<?= base_url('company/projects/store') ?>" method="post">
    <?= csrf_field() ?>

    <?php if(session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row">
      <!-- Core Details -->
      <div class="col-md-6" style="border-right:1px solid #e4e9f0;padding-right:28px;">
        <div class="form-section-title">Core Details</div>

        <div class="mb-3">
          <label class="form-label">Project Type</label>
          <select name="project_type_id" id="projectTypeId" class="form-select" required>
            <option value="">— Select Project Type —</option>
            <?php foreach ($project_types as $type): ?>
              <option value="<?= $type['id'] ?>" <?= old('project_type_id') == $type['id'] ? 'selected' : '' ?>><?= esc($type['type_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Project Name</label>
          <input type="text" name="project_name" class="form-control" value="<?= old('project_name') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Location</label>
          <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;"><i class="fas fa-map-marker-alt" style="color:#0f766e;"></i></span>
            <input type="text" name="address" id="locationField" class="form-control" style="border-radius:0 !important;border-left:none !important;" value="<?= old('address') ?>" required placeholder="Search or pick on map" onclick="openMapModal()" onkeyup="handleLocationTyping(event)">
            <button class="btn btn-outline-secondary" type="button" style="border-radius:0 8px 8px 0;" onclick="openMapModal()"><i class="fas fa-crosshairs"></i> Pick</button>
          </div>
          <input type="hidden" name="latitude" id="latField" value="<?= old('latitude') ?>">
          <input type="hidden" name="longitude" id="lngField" value="<?= old('longitude') ?>">
          
          <!-- Small Preview Map -->
          <div id="formMapPreview" class="mt-2" style="height:120px; border-radius:10px; border:1px solid #e4e9f0; background:#f8fafc; display:none; overflow:hidden;"></div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Price Range (Start)</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;">₹</span>
              <input type="number" step="0.01" name="price_start" class="form-control" style="border-left:none !important;border-radius:0 8px 8px 0 !important;" value="<?= old('price_start') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Price Range (End)</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e4e9f0;background:#f8fafc;">₹</span>
              <input type="number" step="0.01" name="price_end" class="form-control" style="border-left:none !important;border-radius:0 8px 8px 0 !important;" value="<?= old('price_end') ?>">
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="Active">Active (Visible)</option>
            <option value="Inactive">Inactive (Hidden)</option>
          </select>
        </div>
      </div>

      <!-- Dynamic Fields -->
      <div class="col-md-6" style="padding-left:28px;">
        <div class="form-section-title">Dynamic Features</div>
        <p class="text-muted" style="font-size:13px;" id="dynamicHelperText">Select a Project Type first to see the custom fields for it.</p>
        <div id="unitsHint" class="alert alert-info py-2 px-3 mt-3" style="display:none; font-size:12.5px; border-radius:10px; border:none; background:#ecfdf5; color:#065f46;">
          <i class="fas fa-info-circle me-1"></i> <strong>Note:</strong> You will be able to manage individual units/flats and upload images for them <strong>after saving</strong> this project.
        </div>
        <div id="dynamicFieldsContainer"></div>
      </div>
    </div>

    <hr style="margin:24px 0;">
    <div class="d-flex justify-content-end">
      <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i> Save Project</button>
    </div>
  </form>
</div>

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
let map = null, marker = null, previewMap = null, previewMarker = null;
let mapInitialized = false;
let selectedLat = 20.5937;
let selectedLng = 78.9629;
let selectedAddr = <?= json_encode(old('address', '')) ?>;

// Open modal and init map
function openMapModal() {
  const modalEl = document.getElementById('mapModal');
  bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('mapModal');

  modalEl.addEventListener('shown.bs.modal', function () {
    if (!mapInitialized) {
      mapInitialized = true;
      map = L.map('map').setView([selectedLat, selectedLng], selectedAddr ? 13 : 5);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
      }).addTo(map);

      marker = L.marker([selectedLat, selectedLng], { draggable: true }).addTo(map);

      // Click on map
      map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
      });

      // Drag marker
      marker.on('dragend', function () {
        const pos = marker.getLatLng();
        reverseGeocode(pos.lat, pos.lng);
      });

      // Enter key on search
      document.getElementById('mapSearchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
      });
    } else {
      map.invalidateSize();
    }
  });
});

// Called by Search button
function doSearch() {
  const query = document.getElementById('mapSearchInput').value.trim();
  if (!query) return;
  fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query))
    .then(r => r.json())
    .then(data => {
      if (data && data.length > 0) {
        const lat = parseFloat(data[0].lat);
        const lon = parseFloat(data[0].lon);
        map.setView([lat, lon], 14);
        marker.setLatLng([lat, lon]);
        updateLocationData(lat, lon, data[0].display_name);
      } else {
        alert('Location not found. Please try a different search term.');
      }
    })
    .catch(() => alert('Search failed. Check your internet connection.'));
}

function reverseGeocode(lat, lng) {
  fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
    .then(r => r.json())
    .then(data => {
      if (data && data.display_name) {
        updateLocationData(lat, lng, data.display_name);
        document.getElementById('mapSearchInput').value = data.display_name;
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
  document.getElementById('formMapPreview').style.display = 'block';
  updateFormPreviewMap(selectedLat, selectedLng);
  bootstrap.Modal.getInstance(document.getElementById('mapModal')).hide();
}

function updateFormPreviewMap(lat, lng) {
  if (!lat || !lng) return;
  const div = document.getElementById('formMapPreview');
  if (!previewMap) {
    previewMap = L.map(div, {
      zoomControl: false, attributionControl: false,
      dragging: false, touchZoom: false, scrollWheelZoom: false,
      doubleClickZoom: false, boxZoom: false
    }).setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(previewMap);
    previewMarker = L.marker([lat, lng]).addTo(previewMap);
  } else {
    previewMap.setView([lat, lng], 15);
    previewMarker.setLatLng([lat, lng]);
  }
}

function handleLocationTyping(e) {
  if (e.key === 'Enter') { e.preventDefault(); openMapModal(); }
}

document.addEventListener('DOMContentLoaded', function () {
  const typeSelect = document.getElementById('projectTypeId');
  const container  = document.getElementById('dynamicFieldsContainer');
  const helper     = document.getElementById('dynamicHelperText');

  typeSelect.addEventListener('change', function () {
    const id = this.value;
    container.innerHTML = '';
    const unitsHint = document.getElementById('unitsHint');
    unitsHint.style.display = 'none';

    if (!id) { helper.style.display = 'block'; return; }
    helper.style.display = 'none';
    
    // Check if type name suggests units management
    const typeText = typeSelect.options[typeSelect.selectedIndex].text;
    const unitKeywords = ['Apartment', 'Flat', 'Unit', 'Room', 'Building', 'Complex', 'Residential'];
    if (unitKeywords.some(kw => typeText.toLowerCase().includes(kw.toLowerCase()))) {
        unitsHint.style.display = 'block';
    }

    container.innerHTML = '<div class="text-muted" style="font-size:13px;"><div class="spinner-border spinner-border-sm me-2"></div> Loading fields…</div>';

    fetch('<?= base_url('company/projects/get-fields/') ?>' + id)
      .then(r => r.json())
      .then(res => {
        container.innerHTML = '';
        if (res.status === 'success' && res.data.length > 0) {
          res.data.forEach(f => container.appendChild(buildField(f)));
        } else if (res.status === 'success') {
          container.innerHTML = '<p class="text-muted" style="font-size:13px;">No custom fields configured for this type.</p>';
        } else {
          container.innerHTML = '<div class="alert alert-danger">Failed to load fields.</div>';
        }
      }).catch(() => { container.innerHTML = '<div class="alert alert-danger">Error loading fields.</div>'; });
  });

  if (typeSelect.value) typeSelect.dispatchEvent(new Event('change'));

  function buildField(field) {
    const wrap = document.createElement('div'); wrap.className = 'mb-3';
    const name = 'field_' + field.id;
    const req  = field.is_mandatory == 1 ? 'required' : '';
    let labelHtml = `<label class="form-label">${field.field_name}${field.is_mandatory?'<span class="text-danger ms-1">*</span>':''}</label>`;
    let inputHtml = '';

    if (field.field_type === 'Text')
      inputHtml = `<input type="text" name="${name}" class="form-control" ${req}>`;
    else if (field.field_type === 'Number')
      inputHtml = `<input type="number" name="${name}" class="form-control" step="any" ${req}>`;
    else if (field.field_type === 'Checkbox')
      inputHtml = `<div class="form-check"><input class="form-check-input" type="checkbox" name="${name}" value="1" id="${name}"><label class="form-check-label" for="${name}">Yes</label></div>`;
    else if (field.field_type === 'Dropdown') {
      let opts = '<option value="">— Select —</option>';
      if (field.options_json) field.options_json.split(',').forEach(o => { let v=o.trim(); opts+=`<option value="${v}">${v}</option>`; });
      inputHtml = `<select name="${name}" class="form-select" ${req}>${opts}</select>`;
    }

    wrap.innerHTML = labelHtml + inputHtml;
    return wrap;
  }
});
</script>
<?= $this->endSection() ?>
