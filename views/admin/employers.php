<?php
$stats     = isset($stats) ? $stats : [];
$byCat     = isset($byCat) ? $byCat : [];
$activeNav = 'Analytics';
$topCrumb  = 'ADMIN · ANALYTICS';
$pageTitle = 'Analytics';
require   '../layout/header.php';
?>
    
    <title>Admin | Employers</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="../../public/js/admin.js"></script>
</head>
<body>
    <div class="card flush">
    <table class="tbl">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="user-table-body">
        </tbody>
    </table>
</div>

</body>
<script >
    loadUserByRole('employer');


</script>
</html>