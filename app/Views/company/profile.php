<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Company Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row g-4" data-aos="fade-up">
  <div class="col-lg-8">
    <div class="card p-4">
      <div class="form-section-title">Business Information</div>

      <form action="<?= base_url('company/profile/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row mb-3">
          <div class="col-md-8">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control" value="<?= esc($company['company_name']) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="<?= esc($company['contact_number']) ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(cannot be changed)</span></label>
          <input type="email" class="form-control" value="<?= esc($company['email']) ?>" readonly style="background:#f8fafc;">
        </div>

        <div class="mb-3">
          <label class="form-label">Company Logo</label>
          <?php if ($company['logo']): ?>
            <div class="mb-2">
              <img src="<?= base_url('uploads/logos/'.$company['logo']) ?>" alt="Logo" style="height:54px;object-fit:contain;border-radius:8px;border:1px solid #e4e9f0;padding:4px;">
            </div>
          <?php endif; ?>
          <input type="file" name="logo" class="form-control" accept="image/*">
          <div class="form-text text-muted">Leave empty to keep current logo. Max 2MB.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">About the Company</label>
          <textarea name="about" class="form-control" rows="4"><?= esc($company['about']) ?></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label">Office Address</label>
          <textarea name="address" class="form-control" rows="2"><?= esc($company['address']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i> Save Changes</button>
      </form>
    </div>
  </div>

  <div class="col-lg-4" data-aos="fade-up" data-aos-delay="80">
    <div class="card p-4">
      <div class="form-section-title">Account Status</div>
      <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#0f766e,#14b8a6);border-radius:12px;display:flex;align-items:center;justify-content:center;">
          <i class="fas fa-building" style="color:white;font-size:18px;"></i>
        </div>
        <div>
          <div style="font-weight:700;"><?= esc($company['company_name']) ?></div>
          <span class="badge" style="background:#dcfce7;color:#14532d;">Approved Partner</span>
        </div>
      </div>
      <div style="font-size:12.5px;color:#94a3b8;">
        <div class="mb-2"><i class="fas fa-calendar me-1"></i> Member since <?= date('F Y', strtotime($company['created_at'])) ?></div>
        <div><i class="fas fa-envelope me-1"></i> <?= esc($company['email']) ?></div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
