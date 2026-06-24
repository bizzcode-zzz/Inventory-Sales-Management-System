<?php
session_start();

 

// 1. HARANG PARA SA HINDI NAKA-LOGIN (Dapat laging una ito)
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
// 2. KONEKSYON SA DATABASE (XAMPP Default)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb"; // Palitan kung iba ang pangalan ng database mo

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
}
// ===============================
// DASHBOARD STATISTICS
// ===============================

// Lalagyan muna natin ng default value
// para walang error kahit walang laman ang database

$totalProducts = 0;
$averagePrice = 0;
$highestPrice = 0;


// Query para kumuha ng statistics
// COUNT(*) = bilang ng lahat ng records
// AVG(price) = average ng lahat ng presyo
// MAX(price) = pinakamataas na presyo

$stats = $conn->query("
    SELECT
        COUNT(*) AS total_products,
        COALESCE(AVG(price), 0) AS avg_price,
        COALESCE(MAX(price), 0) AS max_price
FROM products
");


// Kung may nakuha tayong result
if ($stats && $stats->num_rows > 0) {

    // Kunin ang unang row
    $row = $stats->fetch_assoc();

    // I-save sa variables
    $totalProducts = $row['total_products'];
    $averagePrice = $row['avg_price'];
    $highestPrice = $row['max_price'];
}
?>

<!DOCTYPE html>
<html>

<head>

    <!-- =========================================
         PAGE INFORMATION
    ========================================== -->

    <title>Dashboard</title>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <!-- =========================================
         EXTERNAL CSS FILE
    ========================================== -->

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

    <!-- =========================================
         MAIN CONTAINER
         Lahat ng dashboard content
    ========================================== -->

    <div class="container">

        <!-- =========================================
             TOP NAVIGATION BAR
             User info + logout button
        ========================================== -->

        <div class="topbar">

            <div>

                <h1>Inventory Dashboard</h1>

                <p>
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION["user"]); ?>
                </p>

            </div>

            <a href="logout.php"
               class="logout-btn">

               Logout

            </a>

        </div>

        <!-- =========================================
             SYSTEM MENU
        ========================================== -->

        <h2>Your System</h2>

        <ul>

            <li>Profile</li>
            <li>Settings</li>
            <li>My Data</li>

        </ul>

        <hr>

        <!-- =========================================
             DASHBOARD STATISTICS
             Total Products
             Average Price
             Highest Price
             Total Revenue
             Total Item Sold
             Monthly Revenue
             
        ========================================== -->

        <div class="stats">

            <div class="stat-card">

                <h3>Total Products</h3>

                <p id="totalProducts">

                    <?php echo $totalProducts; ?>

                </p>

            </div>

            <div class="stat-card">

                <h3>Average Price</h3>

                <p id="averagePrice">

                    ₱<?php echo number_format($averagePrice, 2); ?>

                </p>

            </div>

            <div class="stat-card">

                <h3>Highest Price</h3>

                <p id="highestPrice">

                    ₱<?php echo number_format($highestPrice, 2); ?>

                </p>

            </div>


            <div class="stat-card">

    <h3>Total Items Sold</h3>

    <div id="totalItemsSold">
        0
    </div>

</div>


<div class="stat-card">

    <h3>Today's Revenue</h3>

    <p id="todayRevenue">

        ₱0.00

    </p>

</div>


<div class="stat-card">

    <h3>This Week Revenue</h3>

    <p id="weekRevenue">

        ₱0.00

    </p>

</div>


<div class="stat-card">

    <h3>This Month Revenue</h3>

    <p id="monthRevenue">

        ₱0.00

    </p>

</div>

<div class="stat-card">

    <h3>Sales Today</h3>

    <p id="salesToday">
        0
    </p>

</div>
  

<div class="stat-card">

    <h3>Total Revenue</h3>

    <p id="totalRevenue">

        ₱0.00

    </p>

</div>

<h3>⚠ Low Stock Alerts</h3>

<div id="lowStockList">

Loading...

</div>

        </div>

        <!-- =========================================
             STOCK STATUS CARDS
        ========================================== -->

        <div class="stats">

            <div class="stat-card">

                <h3>🔴 Out of Stock</h3>

                <p id="outOfStockCount">0</p>

            </div>

            <div class="stat-card">

                <h3>🟡 Low Stock</h3>

                <p id="lowStockCount">0</p>

            </div>

            <div class="stat-card">

                <h3>🟢 In Stock</h3>

                <p id="inStockCount">0</p>

            </div>

        </div>

  

<!-- =========================================
     CATEGORY CHART
     Products Per Category
========================================= -->

<div class="card">

    <h2>Products Per Category</h2>

    <div class="chart-container">

        <canvas id="categoryChart"></canvas>

    </div>

</div>

<!-- =========================================
     STOCK STATUS CHART
     In Stock / Low Stock / Out Of Stock
========================================= -->

<div class="card">

    <h2>Stock Status</h2>

    <div style="height: 400px; width: 100%;">

        <canvas id="stockChart"></canvas>

    </div>

</div>

<!-- =========================================
     PRODUCT MANAGEMENT
     Add New Product Form
========================================= -->

<div class="card">

    <h2>Product Management</h2>

    <h3>Add New Product</h3>

    <form method="POST" action="dashboard.php">

        <!-- Product Name -->

        <div class="mb10">

            <label>Product Name:</label><br>

            <input type="text"
                   name="product_name"
                   required>

        </div>

        <!-- Product Category -->

        <div class="mb20">

            <label>Category:</label><br>

            <select id="productCategory">

                <option value="Electronics">
                    Electronics
                </option>

                <option value="Office Supplies">
                    Office Supplies
                </option>

                <option value="Accessories">
                    Accessories
                </option>

                <option value="Others">
                    Others
                </option>

            </select>

        </div>

        <!-- Product Price -->

        <div style="margin-bottom: 10px;">

            <label>Price (Php):</label><br>

            <input type="number"
                   step="0.01"
                   name="price"
                   required>

        </div>

        <!-- Product Stock -->

        <div style="margin-bottom: 10px;">

            <label>Stock:</label><br>

            <input type="number"
                   name="stock"
                   min="0"
                   value="0"
                   required>

        </div>

        <!-- Product Image -->

        <div style="margin-bottom: 20px;">

            <label>Product Image:</label><br>

            <input type="file"
                   id="productImage">

           

            <img
                id="previewImage"
                class="preview-image"
                src=""
                width="120">

        </div>

        <!-- Save Button -->

        <div style="margin-bottom: 10px;">

            <button type="button"
                    onclick="saveProduct()">

                Save Product

            </button>

        </div>

    </form>

    <br>

    <!-- =========================================
     SALES ENTRY
========================================= -->

<div class="card">

    <h2>Record Sale</h2>

    <div class="mb10">

        <label>Product</label><br>

        <select id="saleProduct">

        </select>

    </div>

    <div class="mb10">

        <label>Quantity</label><br>

        <input type="number"
               id="saleQuantity"
               min="1"
               value="1">

    </div>

    <button onclick="recordSale()">

        Record Sale

    </button>

</div>

  

<!-- =========================================
         Top selling product
    ========================================== -->
<div class="stat-card">

    <h3>🏆 Top Selling Product</h3>

    <p id="topProduct">

        Loading...

    </p>

</div>

<div class="stat-card">
<!-- =========================================
         Best selling Category
    ========================================== -->
    <h3>🏆 Best Selling Category</h3>

    <p id="bestCategory">

        N/A

    </p>

</div>

<!-- =========================================
     SALES FILTER  
========================================= -->
<div class="card">

    <h2>Sales Filter</h2>

    <input type="date" id="startDate">

    <input type="date" id="endDate">

    <button onclick="filterSales()">

        Filter

    </button>
    
    <button onclick="printSalesReport()">
    Print Report
</button>

  <?php if ($_SESSION["role"] == "admin") { ?>

<button onclick="clearSalesHistory()">
    Clear Sales History
</button>

<?php } ?>

        <?php if ($_SESSION["role"] == "admin") { ?>

<button onclick="factoryReset()">
    Factory Reset
</button>

<?php } ?>

<?php if ($_SESSION["role"] == "admin") { ?>

<button onclick="backupDatabase()">
💾 Backup Database
</button>

<?php } ?>

</div>

<!-- =========================================
     SALES HISTORY
========================================= -->

<div class="card">

    <h2>Sales History</h2>

    <div id="salesHistory">

        Loading Sales...

    </div>

</div>

    <!-- =========================================
         Daily Sales Revenue
    ========================================== -->
<div class="card">

    <h2>📈 Daily Sales Revenue</h2>

    <div class="chart-container">

        <canvas id="dailySalesChart"></canvas>

    </div>

</div>

<div class="card">

    <h2>🏆 Top Products</h2>

    <div class="chart-container">

        <canvas id="topProductsChart"></canvas>

    </div>

</div>





    <!-- =========================================
         SEARCH / FILTER / SORT
    ========================================== -->

    <div>

        <input type="text"
               id="searchBox"
               placeholder="Search product...">

        <select id="categoryFilter">

            <option value="">All Categories</option>

            <option value="Electronics">
                Electronics
            </option>

            <option value="Office Supplies">
                Office Supplies
            </option>

            <option value="Accessories">
                Accessories
            </option>

            <option value="Others">
                Others
            </option>

        </select>

        <select id="sortFilter">

            <option value="newest">
                Newest
            </option>

            <option value="oldest">
                Oldest
            </option>

            <option value="price_high">
                Price High-Low
            </option>

            <option value="price_low">
                Price Low-High
            </option>

            <option value="name_asc">
                Name A-Z
            </option>

            <option value="name_desc">
                Name Z-A
            </option>

        </select>

        <button onclick="exportCSV()">
            Export CSV
        </button>

        <button onclick="exportExcel()">
            Export Excel
        </button>

      

    </div>

    <!-- =========================================
         PRODUCT LIST
    ========================================== -->

    <h3>Product List</h3>

    <div id="productList">

    </div>

</div>

<!-- =========================================
     RECENT ACTIVITY LOGS
========================================= -->

<div class="card">

    <h3>Recent Activity</h3>

    <input
        type="text"
        id="logSearch"
        placeholder="Search logs..."
    >

    <input
    type="date"
    id="logStartDate"
    >

    <input
    type="date"
    id="logEndDate"
    >

<button onclick="loadLogs()">
    Filter
</button>

    <div id="activityLogs">

        Loading logs...

    </div>

</div>

<!-- =========================================
     IMAGE PREVIEW MODAL
========================================= -->

<div id="imageModal"
     class="modal">

    <span class="closeModal"
          onclick="closeImage()">

        &times;

    </span>

    <img id="modalImage">

</div>

<!-- =========================================
     EXTERNAL JAVASCRIPT LIBRARIES
========================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- =========================================
     MAIN APPLICATION JAVASCRIPT
========================================= -->

<script src="js/app.js"></script>

</body>
</html>