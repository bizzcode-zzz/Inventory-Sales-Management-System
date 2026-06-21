<?php
$conn = new mysqli("localhost", "root", "", "mydb");

/* =========================================
   PAGINATION
========================================= */

$limit = 5;

$page = $_GET['page'] ?? 1;

$page = max(1, (int)$page);

$offset = ($page - 1) * $limit;

$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';



$orderBy = "id DESC";

$totalResult = $conn->query(
    "SELECT COUNT(*) as total FROM products"
);

$totalRows = $totalResult->fetch_assoc()['total'];

$totalPages = ceil($totalRows / $limit);

if ($sort === "oldest") {

    $orderBy = "id ASC";

} elseif ($sort === "price_high") {

    $orderBy = "price DESC";

} elseif ($sort === "price_low") {

    $orderBy = "price ASC";

} elseif ($sort === "name_asc") {

    $orderBy = "product_name ASC";

} elseif ($sort === "name_desc") {

    $orderBy = "product_name DESC";

}

$search = "%{$keyword}%";

if (!empty($keyword) && !empty($category)) {

    $stmt = $conn->prepare("
        SELECT id, product_name, category, price, image, stock
        FROM products
        WHERE product_name LIKE ?
        AND category = ?
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ");

    $stmt->bind_param("ss", $search, $category);

} elseif (!empty($keyword)) {

    $stmt = $conn->prepare("
        SELECT id, product_name, category, price, image, stock
        FROM products
        WHERE product_name LIKE ?
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ");

    $stmt->bind_param("s", $search);

} elseif (!empty($category)) {

    $stmt = $conn->prepare("
        SELECT id, product_name, category, price, image, stock
        FROM products
        WHERE category = ?
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ");

    $stmt->bind_param("s", $category);

} else {

    $stmt = $conn->prepare("
        SELECT id, product_name, category, price, image, stock
        FROM products
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ");
}

$stmt->execute();
$result = $stmt->get_result();


if ($result && $result->num_rows > 0) {

    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Stock</th>
<th>Action</th>
</tr>";





    while ($row = $result->fetch_assoc()) {

        $id = $row['id'];
        $name = htmlspecialchars($row['product_name'], ENT_QUOTES);
        $price = (float)$row['price'];
        $image = htmlspecialchars($row['image'], ENT_QUOTES);
        $category = trim(htmlspecialchars($row['category'], ENT_QUOTES));
        $sort = $_GET['sort'] ?? 'newest';
        $imagePath = "uploads/" . $image;
        $stock = $row['stock'];
        $safeName = json_encode($name);
        $safeCategory = json_encode(trim($category));
        



        echo "<tr>";

        echo "<td>{$id}</td>";

        if (!empty($image)) {

    echo "<td class='image'>
    <img src='$imagePath'
    width='60'
    height='60'
    style='object-fit:cover;border-radius:5px;cursor:pointer;'
    onclick=\"openImage('$imagePath')\">
</td>";

} else {

    echo "<td class='image'>No Image</td>";
}

        echo "<td class='name'>{$name}</td>";
        echo "<td class='category'>{$category}</td>";
        echo "<td class='price'>₱" . number_format($price, 2) . "</td>";

        

        if ($stock <= 0) {

        echo "<td class='stock'>
        <span class='badge badge-danger'
        >OUT OF STOCK
        </span></td>";

    } elseif ($stock <= 5) {

        echo "<td class='stock'>
        <span class='badge badge-warning'>
            LOW STOCK ($stock)
        </span>
      </td>";

    } else {

        echo "<td class='stock'>
        <span class='badge badge-success'>
        IN STOCK ($stock)
        </span>
        </td>";

    }

    echo "<td class='action-buttons'>
    <button type='button'
    onclick='enableEdit($id, $safeName, $safeCategory, $price, $stock, this)'>
    Edit
    </button>

    <button type='button'
    onclick='deleteProduct($id, this)'>
    Delete
    </button>

    </td>";

    echo "</tr>";
}

    echo "</table>";
    echo "<div style='margin-top:15px;'>";

    /* ==========================
  MAO NI ANG PREVIEW AND NEXT BUTTON
========================== */

    if ($page > 1) {

    echo "
    <button
        type='button'
        onclick='changePage(" . ($page - 1) . ")'
    >
        Prev
    </button>
    ";
}

for ($i = 1; $i <= $totalPages; $i++) {

    $active = ($i == $page)
        ? "style='background:#4CAF50;color:white;'"
        : "";

    echo "
    <button
        type='button'
        $active
        onclick='changePage($i)'
    >
        $i
    </button>
    ";
}

if ($page < $totalPages) {

    echo "
    <button
        type='button'
        onclick='changePage(" . ($page + 1) . ")'
    >
        Next
    </button>
    ";
}

echo "</div>";

} else {

    echo "<p>No products found.</p>";

}
?>