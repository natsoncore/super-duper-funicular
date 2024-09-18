<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing with Cart and Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px; /* Space for the fixed top app bar */
            padding-bottom: 60px; /* Space for the fixed bottom navbar */
        }
        .app-bar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .app-bar .back-arrow {
            font-size: 1.5rem;
            color: #000;
        }
        .app-bar .cart {
            position: relative;
        }
        .app-bar .cart #cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
        }
        .categories {
            overflow-x: auto; /* Allows horizontal scrolling */
            white-space: nowrap; /* Keeps the categories in a line */
            padding-bottom: 10px;
            position: relative;
            scrollbar-width: none; /* Hide scrollbar in Firefox */
        }
        .categories::-webkit-scrollbar {
            display: none; /* Hide scrollbar in Chrome */
        }
        .category-list {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
            border-bottom: 2px solid gray; /* Single gray underline for all categories */
        }
        .category-item {
            padding: 10px 15px;
            margin: 0 10px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 20px; /* Rounded category name */
            cursor: pointer;
            color: gray;
            position: relative;
        }
        .category-item.active {
            color: black;
        }
        .category-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px; /* Adjust to place the line exactly at the bottom */
            left: 0;
            right: 0;
            height: 3px;
            background-color: lightblue; /* Light blue underline */
            border-radius: 2px;
        }
        .product-card {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #fff;
            position: relative;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }
        .quantity-controls .form-control {
            width: 60px;
            text-align: center;
        }
        .product-card .quantity-controls {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 200px;
        }
        .product-card .quantity-controls.d-none {
            display: none;
        }
        .product-card.expanded .quantity-controls {
            display: flex;
        }
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
            z-index: 9999;
            display: flex;
            justify-content: center;
        }
        .bottom-navbar .nav {
            display: flex;
            width: 100%;
            max-width: 600px; /* Adjust as needed */
            padding: 0;
            margin: 0;
            list-style: none;
            justify-content: space-between;
        }
        .bottom-navbar .nav-item {
            flex: 1;
            text-align: center;
        }
        .bottom-navbar .nav-link {
            color: #007bff;
            font-size: 1.5rem;
            position: relative;
        }
        .bottom-navbar .nav-link .cart-count {
            position: absolute;
            top: 2px;
            right: 2px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Top App Bar -->
    <nav class="app-bar navbar navbar-light bg-light">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
            <form class="d-flex w-75">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            <div class="cart">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count">0</span>
            </div>
        </div>
    </nav>

    <!-- Slideable Categories -->
    <div class="categories">
        <ul class="category-list" id="category-list">
            <!-- Categories will be dynamically populated here -->
        </ul>
    </div>

    <!-- Product Grid -->
    <div class="container mt-4">
        <h3 id="category-title">Category Name</h3>
        <div class="row" id="product-list">
            <!-- Products will be dynamically populated here -->
        </div>
    </div>

    <!-- Bottom Navbar -->
    <nav class="bottom-navbar navbar navbar-light">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-home"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-search"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-th"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i><span class="cart-count" id="bottom-cart-count">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user"></i></a>
            </li>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            let cartCount = 0;
            let currentCategory = 1; // Default category ID

            // Function to load categories
            function loadCategories() {
                $.ajax({
                    url: 'get_categories.php',  // PHP script to fetch categories
                    type: 'GET',
                    success: function(response) {
                        const categories = JSON.parse(response);
                        let categoryHtml = '';
                        categories.forEach(category => {
                            categoryHtml += `<li class="category-item${category.id === currentCategory ? ' active' : ''}" data-id="${category.id}">${category.name}</li>`;
                        });
                        $('#category-list').html(categoryHtml);
                    }
                });
            }

            // Function to load products based on category
            function loadProducts(categoryId) {
                $.ajax({
                    url: 'get_products.php',  // PHP script to fetch products
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        const products = JSON.parse(response);
                        let productHtml = '';
                        products.forEach(product => {
                            productHtml += `
<div class="col-6 col-md-4">
    <div class="product-card" data-id="${product.id}">
        <img src="${product.image}" class="product-image img-fluid" alt="${product.name}">
        <p class="price">${product.price} ₾</p>
        <p>${product.name}</p>
        <button class="btn btn-primary add-to-cart" data-id="${product.id}">+</button>
        <div class="quantity-controls d-none">
            <button class="btn btn-secondary minus" data-id="${product.id}">-</button>
            <input type="number" class="form-control quantity" value="1" min="1" data-id="${product.id}">
            <button class="btn btn-secondary plus" data-id="${product.id}">+</button>
        </div>
    </div>
</div>
                            `;
                        });
                        $('#product-list').html(productHtml);
                    }
                });
            }

            // Function to handle add to cart
            function addToCart(productId) {
                $.ajax({
                    url: 'add_to_cart.php',  // PHP script to add to cart
                    type: 'POST',
                    data: { id: productId },
                    success: function(response) {
                        cartCount++;
                        $('#cart-count, #bottom-cart-count').text(cartCount);
                        // Optionally: Implement animation to cart here
                    }
                });
            }

            // Initial load
            loadCategories();
            loadProducts(currentCategory);

            // Category click event
            $(document).on('click', '.category-item', function() {
                currentCategory = $(this).data('id');
                $('.category-item.active').removeClass('active');
                $(this).addClass('active');
                $('#category-title').text($(this).text());
                loadProducts(currentCategory);
            });

            // Add to cart button click event
            $(document).on('click', '.add-to-cart', function() {
                const productId = $(this).data('id');
                addToCart(productId);
            });
        });
    </script>
</body>
</html>
