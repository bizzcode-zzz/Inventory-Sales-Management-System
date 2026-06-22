/* =========================================
   INVENTORY SYSTEM JAVASCRIPT
   Author: Ajman
========================================= */



/* ==========================
 ***SCRIPT - SHOWTOAST 
========================== */
function showToast(msg) {
    let div = document.createElement("div");
    div.className = "toast";
    div.innerText = "✅ " + msg;

    document.body.appendChild(div);

    setTimeout(() => {
        div.remove();
    }, 3000);
}
/* script*/



/* ==========================
   ***SCRIPT  - LOADSTATS  
========================== */

     function loadStats() {

    fetch("fetch_stats.php")
    .then(res => res.json())
    .then(data => {

        document.getElementById("totalProducts").innerText =
            data.total;

        document.getElementById("averagePrice").innerText =
            "₱" + data.avg;

        document.getElementById("highestPrice").innerText =
            "₱" + data.max;

    });
}

/* =========================================
   LOAD STOCK ALERTS
========================================= */
    
    function loadAlerts() {

    fetch("fetch_alerts.php")
    .then(res => res.json())
    .then(data => {

        document.getElementById("outOfStockCount")
            .innerText = data.out;

        document.getElementById("lowStockCount")
            .innerText = data.low;

        document.getElementById("inStockCount")
            .innerText = data.in;

    });

    }

/* =========================================
   SAVE PRODUCT
========================================= */ 
     
function saveProduct() {

    let name = document.querySelector("input[name='product_name']").value;
    let price = document.querySelector("input[name='price']").value;
    let image = document.getElementById("productImage").files[0];
    let stock = document.querySelector("input[name='stock']").value;
    let category = document.getElementById("productCategory").value;

    if (!confirm("Save this product?")) return;

    let formData = new FormData();

    formData.append("save_product", 1);
    formData.append("product_name", name);
    formData.append("price", price);
    formData.append("stock", stock);
    formData.append("category", category);

    if (image) {
        formData.append("image", image);
    }

    fetch("api_save.php", {
    method: "POST",
    body: formData
})
.then(res => res.text())
.then(data => {

    

    if (data.trim() === "success") {

    showToast("Saved!");

    document.querySelector("input[name='product_name']").value = "";
    document.querySelector("input[name='price']").value = "";
    document.querySelector("input[name='stock']").value = "0";

    document.getElementById("productImage").value = "";
    document.getElementById("previewImage").src = "";
    document.getElementById("previewImage").style.display = "none";

    loadProducts();
    loadStats();
    loadAlerts();
    loadLogs();
    loadCategoryChart();
    loadStockChart();
    loadSaleProducts();
    loadRevenue();
    loadTopProduct();
    loadSalesHistory();
    loadDailySalesChart();
    loadTopProductsChart();



    } else {

        showToast("Failed!");

    }
});

}
/* script*/



/* ==========================
   ***SCRIPT  - DELETE PRODUCT
========================== */
function deleteProduct(id, btn) {

    if (!confirm("Delete this product?")) return;

   fetch("api_delete.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ id: id })
    })
    .then(res => res.text())
    .then(data => {

        alert("[" + data + "]");
        
    if (data.trim() === "success") {

        showToast("Deleted!");

        loadProducts(); // 🔥 instant update
        loadStats();
        loadAlerts();
        loadLogs();
        loadCategoryChart();
        loadStockChart();
        loadSaleProducts();
        loadRevenue();
        loadTopProduct();
        loadSalesHistory();
        loadDailySalesChart();
        loadTopProductsChart();

    } else {
    alert("DELETE ELSE BLOCK");

    }

});
}
/* script*/


/* ==========================
   ***SCRIPT - EDIT SA PRODUCT
========================== */
    let currentlyEditing = null;
    
       function enableEdit(id, name, category, price, stock, btn){


    if (currentlyEditing !== null) {

        showToast("Finish current edit first!");
        return;
    }

    currentlyEditing = id;

    let row = btn.closest("tr");

    row.querySelector(".name").innerHTML =
        `<input id="editName_${id}" value="${name}">`;
        row.querySelector(".category").innerHTML =
`
    <select id="editCategory_${id}">
        <option value="Electronics">Electronics</option>
        <option value="Office Supplies">Office Supplies</option>
        <option value="Accessories">Accessories</option>
        <option value="Others">Others</option>
    </select>
    `;

    

    row.querySelector(".price").innerHTML =
        `<input id="editPrice_${id}" value="${price}">`;
        
        row.querySelector(".stock").innerHTML =
    `<input id="editStock_${id}" value="${stock}">`;
    
    row.querySelector(".image").innerHTML =
`<input type="file" id="editImage_${id}" accept="image/*">`;

    row.querySelector(".action-buttons").innerHTML =
        `
        <button onclick="updateProduct(${id})">Save</button>
        <button onclick="cancelEdit()">Cancel</button>
        `;

        document.getElementById(`editCategory_${id}`).value = category;
}

        function cancelEdit() {

    currentlyEditing = null;

    loadProducts();
   
}
      
/* script*/


/* ==========================
   ***SCRIPT -  UPDATE PRODUCT
========================== */
function updateProduct(id) {

    let name = document.getElementById(`editName_${id}`).value;
    let price = document.getElementById(`editPrice_${id}`).value;
    let stock = document.getElementById(`editStock_${id}`).value;
    let category = document.getElementById(`editCategory_${id}`).value;

    let formData = new FormData();

formData.append("product_id", id);
formData.append("new_product_name", name);
formData.append("new_category", category);
formData.append("new_price", price);
formData.append("new_stock", stock);

let imageFile =
document.getElementById(`editImage_${id}`).files[0];

if (imageFile) {
    formData.append("new_image", imageFile);
}

fetch("api_update.php", {
    method: "POST",
    body: formData
})
    .then(res => res.text())
    .then(data => {
         

    if (data.trim() === "success") {

     currentlyEditing = null;

    showToast("Saved!");

    loadProducts();
    loadStats();
    loadAlerts();
    loadLogs();
    loadCategoryChart();
    loadStockChart();
    loadSaleProducts();
    loadRevenue();
    loadTopProduct();
    loadSalesHistory();
    loadDailySalesChart();
    loadTopProductsChart();



    } else {
        showToast("Failed!");
    }

});
}
/* script*/



/* ==========================
   ***SCRIPT - Search Products
========================== */
function searchProducts() {

    let keyword =
        document.getElementById("searchBox").value;

    let category =
        document.getElementById("categoryFilter").value;

    let sort =
    document.getElementById("sortFilter").value;

    fetch(
    "fetch_products.php?keyword=" +
    encodeURIComponent(keyword) +
    "&category=" +
    encodeURIComponent(category) +
    "&sort=" +
    encodeURIComponent(sort)
)
    .then(res => res.text())
    .then(data => {
        document.getElementById("productList").innerHTML = data;
        
    });

}
    
    function setLoading() {
    document.getElementById("productList").innerHTML =
        "<p>Loading products...</p>";
}

/* =========================================
   LOAD PRODUCT TABLE
========================================= */

function loadProducts() {

    setLoading();

    fetch("fetch_products.php?page=" + currentPage)
    .then(res => res.text())
    .then(data => {

        
     

        document.getElementById("productList").innerHTML = data;
          
    });

}
    function refreshTable() {
    setTimeout(() => {
        loadProducts();
    }, 200);
}

    document.addEventListener("DOMContentLoaded", function () {

     loadProducts();
    loadStats();
    loadAlerts();
    loadLogs();
    loadCategoryChart();
    loadStockChart();
    loadSaleProducts();
    loadRevenue();
    loadTopProduct();
    loadSalesHistory();
    loadDailySalesChart();
    loadTopProductsChart();

    document.getElementById("searchBox")
    .addEventListener("keyup", searchProducts);
    document.getElementById("categoryFilter")
    .addEventListener("change", searchProducts);
    document.getElementById("sortFilter")
.addEventListener("change", searchProducts);



});
/* script*/


/* ==========================
   ***SCRIPT -  PREVIEW IMAGE
========================== */

document.addEventListener("DOMContentLoaded", function () {

    let imageInput =
        document.getElementById("productImage");

    if (!imageInput) return;

    imageInput.addEventListener("change", function() {

        let file = this.files[0];

        if (!file) return;

        let reader = new FileReader();

        reader.onload = function(e) {

            let preview =
                document.getElementById("previewImage");

            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(file);

    });

});

/* script*/

/* ==========================
   ***SCRIPT - OPEN IMAGE
========================== */

function openImage(src) {

    document.getElementById("imageModal")
        .style.display = "block";

    document.getElementById("modalImage")
        .src = src;
}

function closeImage() {

    document.getElementById("imageModal")
        .style.display = "none";
}

/* script*/

/* ==========================
   ***SCRIPT - EXPORT FILE CSV
========================== */
 
    function exportCSV() {

    window.location.href = "export_csv.php";

}
 
/* ==========================
   ***SCRIPT - EXPORT FILE XLSX
========================== */
 
    function exportExcel() {

    window.location.href = "export_excel.php";

}
 /* ==========================
   ***SCRIPT -  - LOAD LOGS
========================== */
function loadLogs() {

    fetch("fetch_logs.php")
    .then(res => res.text())
    .then(data => {

        document.getElementById("activityLogs").innerHTML = data;

    });

}
 /* MGA LOGS */

  /* ==========================
   ***SCRIPT -  - PAGINATION or PAGE
========================== */

 let currentPage = 1;

function changePage(page) {

    currentPage = page;

    loadProducts();
}

/* =========================================
   LOAD CATEGORY CHART
========================================= */

let categoryChart;
let stockChart;

function loadCategoryChart() {

    fetch("fetch_chart.php")
    .then(res => res.json())
    .then(data => {

        let labels = [];
        let totals = [];

        data.forEach(item => {

            labels.push(item.category);
            totals.push(item.total);

        });

        const canvas =
            document.getElementById("categoryChart");

        const ctx =
            canvas.getContext("2d");

        if (categoryChart) {
            categoryChart.destroy();
            categoryChart = null;
        }

        categoryChart = new Chart(ctx, {

            type: "bar",

            data: {

                labels: labels,

                datasets: [{

                    label: "Products",

                    data: totals

                }]
            },

            options: {

                responsive: true,
                maintainAspectRatio: false,

                scales: {

                    y: {

                        beginAtZero: true

                    }
                }
            }
        });

    });

}

function loadStockChart() {

    fetch("fetch_stock_chart.php")
    .then(res => res.json())
    .then(data => {

        const ctx =
            document.getElementById("stockChart")
            .getContext("2d");

        if (stockChart) {

            stockChart.destroy();

            stockChart = null;
        }

        stockChart = new Chart(ctx, {

            type: "pie",

            data: {

                labels: [

                    "In Stock",
                    "Low Stock",
                    "Out Of Stock"

                ],

                datasets: [{

                    data: [

                        data.inStock,
                        data.lowStock,
                        data.outStock

                    ]

                }]
            },

            options: {

                responsive: true,
                maintainAspectRatio: false

            }

        });

    });

}

/* =========================================
   LOAD PRODUCTS FOR SALES
========================================= */

function loadSaleProducts() {

    fetch("fetch_products_dropdown.php")

    .then(res => res.json())

    .then(data => {

        let html = "";

        data.forEach(product => {

            

            html += `
                <option value="${product.id}">
                    ${product.product_name}
                </option>
            `;

        });

        document.getElementById("saleProduct").innerHTML = html;

    });

}

/* =========================================
   RECORD SALE
========================================= */

function recordSale() {

    let productId =
        document.getElementById("saleProduct").value;

    let quantity =
        document.getElementById("saleQuantity").value;

    fetch("record_sale.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body:
            "product_id=" + productId +
            "&quantity=" + quantity

    })

    .then(res => res.text())

    .then(data => {

        alert(data);

        loadProducts();
        loadCategoryChart();
        loadStockChart();
        loadRevenue();
        loadTopProduct();
        loadSalesHistory();
        loadDailySalesChart();
        loadTopProductsChart();

    });

}

/* =========================================
   LOAD TOTAL REVENUE
========================================= */

function loadRevenue() {

    let dates = getSelectedDates();

fetch(
    "fetch_revenue.php?startDate="
    + dates.startDate
    +
    "&endDate="
    + dates.endDate
)

    .then(res => res.json())

    .then(data => {

        let revenue = data.revenue || 0;

        document.getElementById("totalRevenue")
        .innerText =
        "₱" + Number(revenue).toFixed(2);

    });

}

/* =========================================
   TOP SELLING PRODUCT
========================================= */

function loadTopProduct() {

    fetch("fetch_top_product.php")

    .then(res => res.json())

    .then(data => {

        if (!data) {

            document.getElementById("topProduct")
            .innerText = "No Sales Yet";

            return;
        }

        document.getElementById("topProduct")
        .innerText =
        data.product_name +
        " (" +
        data.total_sold +
        " sold)";

    });

}

/* =========================================
   SALES HISTORY
========================================= */

function loadSalesHistory(
    startDate = "",
    endDate = ""
) {

    fetch(
    "fetch_sales_history.php?startDate="
    + startDate +
    "&endDate="
    + endDate
)

    .then(res => res.json())

    .then(data => {

        let html = `

        <table>

            <tr>

                <th>Date</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Amount</th>

            </tr>

        `;

        data.forEach(item => {

            let amount =
                item.quantity * item.sale_price;

            html += `

            <tr>

                <td>${item.sale_date}</td>

                <td>${item.product_name}</td>

                <td>${item.quantity}</td>

                <td>₱${amount.toFixed(2)}</td>

            </tr>

            `;

        });

        html += "</table>";

        document.getElementById("salesHistory")
        .innerHTML = html;

    });

}

/* =========================================
   DAILY SALES CHART
========================================= */
let dailySalesChart;

function loadDailySalesChart() {

    let dates = getSelectedDates();

fetch(
    "fetch_daily_sales.php?startDate="
    + dates.startDate
    +
    "&endDate="
    + dates.endDate
)

    .then(res => res.json())

    .then(data => {

        let labels = [];
        let revenues = [];

        data.forEach(item => {

            labels.push(item.sale_day);

            revenues.push(item.revenue);

        });

        const ctx =
        document
        .getElementById("dailySalesChart")
        .getContext("2d");

        if (dailySalesChart) {

            dailySalesChart.destroy();

        }

        dailySalesChart = new Chart(ctx, {

            type: "line",

            data: {

                labels: labels,

                datasets: [{

                    label: "Revenue",

                    data: revenues,

                    tension: 0.3

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false

            }

        });

    });

}

/* =========================================
   TOP PRODUCTS CHART
========================================= */
let topProductsChart;

function loadTopProductsChart() {

    let dates = getSelectedDates();

fetch(
    "fetch_top_products_chart.php?startDate="
    + dates.startDate
    +
    "&endDate="
    + dates.endDate
)

    .then(res => res.json())

    .then(data => {

        let labels = [];
        let totals = [];

        data.forEach(item => {

            labels.push(item.product_name);

            totals.push(item.total_sold);

        });

        const ctx =
        document
        .getElementById("topProductsChart")
        .getContext("2d");

        if (topProductsChart) {

            topProductsChart.destroy();

        }

        topProductsChart = new Chart(ctx, {

            type: "bar",

            data: {

                labels: labels,

                datasets: [{

                    label: "Units Sold",

                    data: totals

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        });

    });

    /* =========================================
   SALES FILTER
========================================= */
}

function filterSales() {

    let startDate =
    document.getElementById("startDate").value;

    let endDate =
    document.getElementById("endDate").value;

    loadSalesHistory(
    startDate,
    endDate
);

loadRevenue();
loadDailySalesChart();
loadTopProductsChart();

}
    /* =========================================
   SALES FILTER UPGRAADE 
========================================= */
function getSelectedDates() {

    return {

        startDate:
        document.getElementById("startDate").value,

        endDate:
        document.getElementById("endDate").value

    };

}
   /* =========================================
   SALES FILTER print sales report
========================================= */
function printSalesReport() {

    let dates =
    getSelectedDates();

    window.open(

        "print_report.php"

        +

        "?startDate="

        +

        dates.startDate

        +

        "&endDate="

        +

        dates.endDate,

        "_blank"

    );


}

/* =========================================
   CLEAR HISTORY FUNCTION and reload 
========================================= */

function clearSalesHistory() {

    if (
        !confirm(
        "Delete all sales history?"
        )
    ) {
        return;
    }

    fetch("clear_sales.php", {
    method: "POST"
})

    .then(res => res.text())

    .then(data => {

        if (
            data === "success"
        ) {

            alert(
            "Sales history cleared!"
            );

            loadRevenue();

            loadSalesHistory();

            loadDailySalesChart();

            loadTopProductsChart();

        }

    });

}

/* =========================================
   FACTORY RESET
========================================= */


function factoryReset() {

    if (
        !confirm(
        "WARNING! This will delete ALL DATA."
        )
    ) {
        return;
    }

    let confirmation =
    prompt(
    "Type RESET to continue"
    );

    if (
        confirmation !== "RESET"
    ) {
        return;
    }

    fetch(
    "factory_reset.php",
    {
        method: "POST"
    }
)

    .then(res => res.text())

    .then(data => {

        if (
            data === "success"
        ) {

            alert(
            "Factory Reset Complete!"
            );

           loadProducts();
    loadStats();
    loadAlerts();
    loadLogs();
    loadCategoryChart();
    loadStockChart();
    loadSaleProducts();
    loadRevenue();
    loadTopProduct();

    loadSalesHistory();
    loadDailySalesChart();
    loadTopProductsChart();

        }

    });

}

/* =========================================
   BACK UP DATABASE
========================================= */

function backupDatabase() {

    window.location.href =
    "backup_database.php";

}