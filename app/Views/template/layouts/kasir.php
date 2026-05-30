<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard POS - Warkop Pos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .cart-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .cart-scroll::-webkit-scrollbar-track {
            background: #f5f5f4;
        }
        .cart-scroll::-webkit-scrollbar-thumb {
            background: #d6d3d1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #292524;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #57534e;
            border-radius: 3px;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        .category-btn.active {
            background: #b45309;
            color: white;
        }
        .nav-link.active {
            background: linear-gradient(90deg, #b45309 0%, #d97706 100%);
            color: white;
        }
        .nav-link:not(.active):hover {
            background: #44403c;
        }
    </style>
</head>
<body class="bg-stone-100 min-h-screen">

<?= $this->renderSection('content') ?>

</body>
</html>
