<div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employers as $employer): ?>
            <tr>
                <td><?= htmlspecialchars($employer['name']) ?></td>
                <td><?= htmlspecialchars($employer['email']) ?></td>
                <td><?= htmlspecialchars($employer['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>