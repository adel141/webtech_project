<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:16px">
    <div class="stat">
        <div class="k">Seekers</div>
        <div class="v" id="seeker-count"><?= $data[0]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Employers</div>
        <div class="v" id="employer-count"><?= $data[1]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Recruiters</div>
        <div class="v" id="recruiter-count"><?= $data[2]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Active jobs</div>
        <div class="v" id="job-count"><?= $data['active_jobs']['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Apps (30d)</div>
        <div class="v" id="app-count"><?= $data['recent_applications']['cnt'] ?? 0 ?></div>
        <div class="d up">recent</div>
    </div>
    <div class="stat">
        <div class="k">Open complaints</div>
        <div class="v" id="complaint-count"><?= $data['open_complaints']['cnt'] ?? 0 ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:26px">
    <!-- Pending employers -->
    <div class="card flush">
        <div class="card-h">
            <h3>Pending employers</h3>
            <a href="" class="btn ghost sm" style="margin-left:auto">Manage all</a>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pendingEmployers)): ?>
                <tr>
                    <td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">All clear.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($pendingEmployers as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td class="muted" style="font-size:12px"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button class="btn sm accent" onclick = "approveUser(<?= $u['id'] ?>)">Approve</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<!-- Pending recruiters -->

<div class="card flush">
    <div class="card-h">
      <h3>Pending recruiters</h3>
      <a href="" class="btn ghost sm" style="margin-left:auto">Manage all</a>
    </div>
    <table class="tbl">
      <thead><tr><th>Name</th><th>Email</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($pendingRecruiters)): ?>
          <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">All clear.</td></tr>
        <?php else: ?>
          <?php foreach ($pendingRecruiters as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['name']) ?></td>
              <td class="muted" style="font-size:12px"><?= htmlspecialchars($u['email']) ?></td>
              <td>
                <form method="POST" style="display:inline">
                  <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                  <button class="btn sm accent" onclick="approveUser(<?= $u['id'] ?>)">Approve</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div> 






</body>
<script src ="../../public/js/admin.js">
    
</script>
</html>