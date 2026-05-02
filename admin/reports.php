<?php
require '../includes/db.php';
require '../includes/auth.php';
require_admin();
include '../includes/header.php';

$sales = $pdo->query("SELECT q.id, c.name client, q.status, q.total, DATE(q.created_at) qdate
  FROM quotations q JOIN clients c ON q.client_id=c.id
  ORDER BY q.created_at DESC LIMIT 50")->fetchAll();
?>
<div class="container my-4">
  <h2>Recent Quotations & Sales</h2>
  <button onclick="window.print()" class="btn btn-secondary mb-3">Print / Export</button>
  <table class="table table-bordered" id="report-table">
    <thead><tr><th>ID</th><th>Client</th><th>Status</th><th>Date</th><th>Total</th></tr></thead>
    <tbody>
      <?php foreach ($sales as $row): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['client']) ?></td>
          <td><?= $row['status'] ?></td>
          <td><?= htmlspecialchars($row['qdate']) ?></td>
          <td>UGX <?= number_format($row['total'],0) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
