<?= $this->extend('company/layout') ?>

<?= $this->section('pageTitle') ?>
Leads & Enquiries
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row" data-aos="fade-up">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h5 class="card-title fw-bold text-dark mb-4">Recent Enquiries</h5>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Project</th>
                <th>Name</th>
                <th>Contact info</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($enquiries)): ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">No enquiries found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($enquiries as $enquiry): ?>
                  <tr>
                    <td><?= date('M d, Y', strtotime($enquiry['created_at'])) ?></td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill"><?= esc($enquiry['project_name']) ?></span></td>
                    <td class="fw-semibold">
                      <?= esc($enquiry['name']) ?>
                    </td>
                    <td>
                      <div><i class="fas fa-envelope text-muted"></i> <a href="mailto:<?= esc($enquiry['email']) ?>"><?= esc($enquiry['email']) ?></a></div>
                      <div><i class="fas fa-phone mt-1 text-muted"></i> <a href="tel:<?= esc($enquiry['phone']) ?>"><?= esc($enquiry['phone']) ?></a></div>
                    </td>
                    <td>
                      <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#messageModal<?= $enquiry['id'] ?>">
                        <i class="fas fa-eye text-primary"></i> View
                      </button>
                    </td>
                    <td>
                        <?php
                            $badgeClass = '';
                            if ($enquiry['status'] == 'New') $badgeClass = 'bg-danger';
                            if ($enquiry['status'] == 'Read') $badgeClass = 'bg-warning text-dark';
                            if ($enquiry['status'] == 'Contacted') $badgeClass = 'bg-success';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $enquiry['status'] ?></span>
                    </td>
                    <td>
                        <form action="<?= base_url('company/enquiries/update-status/' . $enquiry['id']) ?>" method="POST" class="d-flex align-items-center gap-2">
                             <select name="status" class="form-select form-select-sm" style="width:auto">
                                <option value="New" <?= $enquiry['status'] == 'New' ? 'selected' : '' ?>>New</option>
                                <option value="Read" <?= $enquiry['status'] == 'Read' ? 'selected' : '' ?>>Read</option>
                                <option value="Contacted" <?= $enquiry['status'] == 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                             </select>
                             <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                  </tr>

                  <!-- Message Modal -->
                  <div class="modal fade" id="messageModal<?= $enquiry['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Enquiry from <?= esc($enquiry['name']) ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="text-muted fw-bold small">Project</label>
                            <div class="fw-semibold"><?= esc($enquiry['project_name']) ?></div>
                          </div>
                          <div class="mb-3">
                            <label class="text-muted fw-bold small">Details</label>
                            <div class="bg-light p-3 rounded" style="white-space: pre-wrap; font-size: 0.95rem;"><?= esc($enquiry['message']) ?></div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
