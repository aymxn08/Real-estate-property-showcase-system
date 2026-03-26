<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Admin Login | Harxa Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body style="animation: pageLoad 0.5s ease;">

<div class="auth-page">
  <!-- Left Panel -->
  <div class="auth-left">
    <div class="auth-orb auth-orb-1"></div>
    <div class="auth-orb auth-orb-2"></div>
    <div class="auth-orb auth-orb-3"></div>
    <div class="auth-left-content">
      <div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;">
        <div style="width:44px;height:44px;background:rgba(20,184,166,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
          <i class="fas fa-building" style="color:#14b8a6;font-size:18px;"></i>
        </div>
        <span style="color:#fff;font-size:18px;font-weight:700;">Harxa Tech</span>
      </div>
      <h1>Manage Your Real Estate<br>Empire from One Place</h1>
      <p>The SaaS platform trusted by real estate companies to manage projects, leads, and bookings.</p>
      <ul class="auth-features">
        <li><i class="fas fa-check-circle"></i> Multi-company data isolation</li>
        <li><i class="fas fa-check-circle"></i> Full booking pipeline tracking</li>
        <li><i class="fas fa-check-circle"></i> Dynamic project type configuration</li>
        <li><i class="fas fa-check-circle"></i> Built for scale</li>
      </ul>
    </div>
  </div>

  <!-- Right Login Form -->
  <div class="auth-right">
    <div class="auth-form-box">
      <div class="form-title">Welcome back</div>
      <div class="form-subtitle">Sign in to the Super Admin Panel</div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('super-admin/login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="admin@harxatech.com" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding:12px !important;font-size:15px !important;">
          <i class="fas fa-sign-in-alt me-2"></i> Sign In
        </button>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
