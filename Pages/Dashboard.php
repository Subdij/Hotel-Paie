<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Dashboard</title>
</head>
<body class="h-full">
    <?php include 'Header.php'; ?>
    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
                <nav>
                    <a href="dashboard.php?page=rooms" class="text-blue-500 hover:underline">Rooms</a> |
                    <a href="dashboard.php?page=clients" class="text-blue-500 hover:underline">Clients</a>
                </nav>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    $allowed_pages = ['rooms', 'clients'];
                    if (in_array($page, $allowed_pages)) {
                        include $page . '.php';
                    } else {
                        echo "<p>Invalid page requested!</p>";
                    }
                } else {
                    echo "<p>Welcome to the dashboard! Use the links above to navigate.</p>";
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
