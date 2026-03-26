<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Registration | Real Estate SaaS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body>

<div class="auth-page">
  <!-- Left Panel -->
  <div class="auth-left">
    <div class="auth-orb auth-orb-1"></div>
    <div class="auth-orb auth-orb-2"></div>
    <div class="auth-orb auth-orb-3"></div>
    <div class="auth-left-content">
      <div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;">
        <div style="width:44px;height:44px;background:rgba(20,184,166,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
          <i class="fas fa-home" style="color:#14b8a6;font-size:18px;"></i>
        </div>
        <span style="color:#fff;font-size:18px;font-weight:700;">Real Estate SaaS</span>
      </div>
      <h1>Join Hundreds of Real Estate Companies</h1>
      <p>Registration is free. Once approved by our team, you'll get instant access to the full dashboard experience.</p>
      <ul class="auth-features">
        <li><i class="fas fa-rocket"></i> Instant setup after approval</li>
        <li><i class="fas fa-lock"></i> Fully secure & isolated data</li>
        <li><i class="fas fa-tools"></i> Customize project types & fields</li>
        <li><i class="fas fa-mobile-alt"></i> Works on desktop, tablet & mobile</li>
      </ul>
    </div>
  </div>

  <!-- Right Form -->
  <div class="auth-right">
    <div class="auth-form-box" style="max-width:420px;">
      <div class="form-title">Create Company Account</div>
      <div class="form-subtitle">Submit your details for review</div>

      <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <ul class="mb-0 ps-3">
            <?php foreach(session()->getFlashdata('errors') as $e): ?>
              <li><?= $e ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('company/register') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Company Name</label>
          <input type="text" name="company_name" class="form-control" value="<?= old('company_name') ?>" placeholder="Your Company Name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="contact@company.com" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_number" class="form-control" value="<?= old('contact_number') ?>" placeholder="+1 555 000 0000" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" minlength="6" required>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding:12px !important;font-size:15px !important;">
          <i class="fas fa-paper-plane me-2"></i> Submit Registration
        </button>
      </form>

      <p class="text-center mt-4" style="font-size:13.5px;color:#6b7280;">
        Already registered?
        <a href="<?= base_url('company/login') ?>" style="color:#0f766e;font-weight:600;text-decoration:none;">Login here</a>
      </p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
