<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Approval requests</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">

    <style>
        body {
          font-family: Arial, sans-serif;
          padding: 20px;
        }
    
        .search-filter-section {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }
    
        .search-filter-section input,
        .search-filter-section button {
          padding: 10px;
          font-size: 1em;
          margin-right: 10px;
        }
    
        .filter-by-date {
          margin-left: 10px;
        }
    
        .category-section {
          margin-top: 20px;
        }
    
        .category-header {
          font-size: 1.5em;
          font-weight: bold;
          margin-bottom: 15px;
        }
    
        .request-card {
          border: 1px solid #ddd;
          border-radius: 5px;
          padding: 15px;
          margin-bottom: 15px;
          background-color: #f9f9f9;
        }
    
        .request-header {
          font-weight: bold;
          margin-bottom: 10px;
        }
    
        .request-actions {
          display: flex;
          gap: 10px;
        }
    
        .request-actions button {
          padding: 5px 15px;
          font-size: 0.9em;
          border: none;
          border-radius: 3px;
          cursor: pointer;
        }
    
        .approve-btn {
          background-color: #28a745;
          color: white;
        }
    
        .approve-btn:hover {
          background-color: #218838;
        }
    
        .reject-btn {
          background-color: #dc3545;
          color: white;
        }
    
        .reject-btn:hover {
          background-color: #b02a37;
        }

        .view-ehr-link {
            color: #007bff;
            text-decoration: underline;
            font-weight: bold;
            font-size: 0.95em;
            margin-top: 10px;
            display: inline-block;
        }

    .view-ehr-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }
      </style>
 <?php 
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 session_start();
 include("./role_based_header.php"); ?>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">    
<!-- SHOP DETAILS AREA START -->
<div class="ltn__shop-details-area pb-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ltn__shop-details-inner mb-60">

                    <!-- Title -->
                    <h2 class="page-title">Pending Requests</h2>

                    <!-- Search and Filter Section -->
                    <div class="search-filter-section">
                        <div class="search-container">
                            <input type="text" id="searchInput" placeholder="Search by keyword..." oninput="filterRequests()">
                            <input type="date" id="filterDate" class="filter-by-date" onchange="filterRequests()">
                        </div>
                    </div>

                    <!-- Requests Container -->
                    <div class="requests-container">
                        <div id="pendingRequestsSection" class="category-section">
                            <!-- Cards will be dynamically inserted here -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- SHOP DETAILS AREA END -->

<!-- Inline CSS -->
<style>
    .page-title {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .search-filter-section {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-container input[type="text"],
    .search-container input[type="date"] {
        height: 45px; /* Set equal height */
        width: 300px; /* Set equal width */
        padding: 10px; /* Ensure consistent padding */
        font-size: 1em;
        border: 1px solid #ccc;
        border-radius: 25px; /* Rounded corners */
        background-color: #f9f9f9;
    }

    .search-container input[type="text"]::placeholder,
    .search-container input[type="date"]::placeholder {
        color: #aaa;
        font-size: 0.9em;
    }

    .requests-container {
        border: 1px solid #ddd;
        border-radius: 15px;
        padding: 20px;
        margin: 20px auto;
        background-color: #ffffff;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        max-width: 900px;
    }

    .category-section {
        margin-top: 10px;
    }

    .request-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .request-header {
        font-weight: bold;
        font-size: 1.2em;
        margin-bottom: 10px;
        color: #333;
    }

    .request-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .request-actions button {
        padding: 10px 20px;
        font-size: 0.95em;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        color: white;
    }

    .approve-btn {
        background-color: #28a745;
    }

    .approve-btn:hover {
        background-color: #218838;
    }

    .reject-btn {
        background-color: #dc3545;
    }

    .reject-btn:hover {
        background-color: #b02a37;
    }
</style>
</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>

    <!-- Requests -->
    <script>
        document.addEventListener('DOMContentLoaded', fetchRequests);

        document.addEventListener('DOMContentLoaded', fetchRequests);

       // Fetch requests from the backend
function fetchRequests() {
    fetch("requests_backend.php") // API endpoint
        .then((response) => response.json())
        .then((data) => {
            console.log(data); // Debugging: Log the received data
            displayRequests(data); // Call function to render the requests
        })
        .catch((error) => console.error("Error fetching requests:", error));
}

// Display requests dynamically
// Display requests dynamically
function displayRequests(data) {
    const pendingRequestsSection = document.getElementById("pendingRequestsSection");
    pendingRequestsSection.innerHTML = ""; // Clear any previous content

    if (data.length === 0) {
        pendingRequestsSection.innerHTML = "<p>No pending requests found.</p>";
        return;
    }

    data.forEach((request) => {
        const requestCard = document.createElement("div");
        requestCard.className = "request-card";
        requestCard.dataset.keywords = (request.comment || "").toLowerCase(); // Handle null or undefined comment
        requestCard.dataset.date = request.request_datetime || ""; // Handle null or undefined date

        requestCard.innerHTML = `
            <div class="request-header">Request #${request.request_id}</div>
            <p><strong>Customer:</strong> ${request.first_name || "Unknown"} ${request.last_name || "Unknown"}</p>
            <p><strong>Details:</strong> ${request.comment || "No comments available"}</p>
            <p><strong>Product Name:</strong> ${request.product_name || "N/A"}</p>
            <p><strong>Request Sent On:</strong> ${request.request_datetime || "N/A"}</p>
            <a href="generate_ehr.php?customer_id=${request.customer_id}" class="view-ehr-link" target="_blank">View Customer's EHR</a>
            <div class="request-actions">
                <button class="approve-btn" onclick="handleRequest(${request.request_id}, 'approve')">Approve</button>
                <button class="reject-btn" onclick="handleRequest(${request.request_id}, 'reject')">Reject</button>
            </div>
        `;
        pendingRequestsSection.appendChild(requestCard); // Append the request card
    });
}


// Handle approve or reject actions
function handleRequest(requestId, action) {
    const comment = action === "reject" ? prompt("Add a rejection reason:") : null;

    fetch("requests_backend.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ request_id: requestId, action: action, comment: comment }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert(`Request ${action}ed successfully!`);
                fetchRequests(); // Refresh the list
            } else {
                alert(`Failed to ${action} the request: ${data.message}`);
            }
        })
        .catch((error) => console.error("Error processing request:", error));
}

// Filter requests based on search and date
function filterRequests() {
    const searchKeyword = document.getElementById("searchInput").value.toLowerCase();
    const selectedDate = document.getElementById("filterDate").value;

    const requests = document.querySelectorAll(".request-card");
    requests.forEach((request) => {
        const keywords = request.dataset.keywords;
        const requestDate = request.dataset.date;

        const matchesKeyword = keywords.includes(searchKeyword);
        const matchesDate = !selectedDate || requestDate === selectedDate;

        request.style.display = matchesKeyword && matchesDate ? "block" : "none";
    });
}

// Initialize fetching of requests when the page loads
document.addEventListener("DOMContentLoaded", fetchRequests);

</script>
</body>
</html>

