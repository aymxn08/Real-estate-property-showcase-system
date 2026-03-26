<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Login | Real Estate SaaS</title>
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
      <h1>Your Real Estate<br>Command Center</h1>
      <p>Manage villas, apartments, plots, and bookings — all from one beautiful dashboard.</p>
      <ul class="auth-features">
        <li><i class="fas fa-city"></i> Manage unlimited projects</li>
        <li><i class="fas fa-map-marker-alt"></i> Track bookings by location</li>
        <li><i class="fas fa-sliders-h"></i> Custom project field types</li>
        <li><i class="fas fa-shield-alt"></i> Fully isolated data per company</li>
      </ul>
    </div>
  </div>

  <!-- Right Login Form -->
  <div class="auth-right">
    <div class="auth-form-box">
      <div class="form-title">Company Sign In</div>
      <div class="form-subtitle">Log in to your company dashboard</div>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('company/login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="you@company.com" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding:12px !important;font-size:15px !important;">
          <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
        </button>
      </form>

      <p class="text-center mt-4" style="font-size:13.5px;color:#6b7280;">
        Don't have an account?
        <a href="<?= base_url('company/register') ?>" style="color:#0f766e;font-weight:600;text-decoration:none;">Register here</a>
      </p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
