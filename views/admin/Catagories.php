<html>
<head>
    <title>Admin | Categories</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="../../public/js/admin.js"></script>

</head>
<body>      
    
<div style="display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:flex-start">
    <!-- Category list -->
    <div class="card flush">
        <div class="card-h"><h3>All categories</h3><span id="categories-count" class="muted" style="font-size:12px;margin-left:auto"></span></div>
        <table class="tbl">
        <thead>
          <tr>
            <th>Name</th>
            <th class="num">Active jobs</th>
            <th class="num">All jobs</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="category-table-data">

        </tbody>
        </table>
    </div>
  </div>
  
  </body>
  <script>
    loadCategories();
  </script>
</html>
