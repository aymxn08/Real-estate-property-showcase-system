<?= $this->extend('company/layout') ?>
<?= $this->section('pageTitle') ?>Edit Project Type<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center" data-aos="fade-up">
  <div class="col-lg-6">
    <div class="card p-4">
      <div class="card-header-bar mb-4 px-0 pt-0" style="border-bottom:none;">
        <h5><i class="fas fa-edit me-2 text-warning"></i> Edit: <?= esc($type['type_name']) ?></h5>
        <a href="<?= base_url('company/project-types') ?>" class="btn btn-secondary btn-sm">Back</a>
      </div>

      <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('company/project-types/update/'.$type['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-4">
          <label class="form-label">Project Type Name</label>
          <input type="text" name="type_name" class="form-control" value="<?= esc($type['type_name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning w-100"><i class="fas fa-save me-2"></i> Update Project Type</button>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
