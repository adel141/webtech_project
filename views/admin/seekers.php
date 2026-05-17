<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Admin | Seekers</title>
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
    loadUserByRole('seeker');


</script>
</html>