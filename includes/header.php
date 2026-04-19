<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Core Marketplace</title>
</head>
<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-md mb-6">
        <div class="container mx-auto flex justify-between">
            <a href="index.php" class="text-xl font-bold">Marketplace</a>
            <div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="px-4">Dashboard</a>
                    <a href="logout.php" class="text-red-500">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="px-4">Login</a>
                    <a href="register.php" class="bg-blue-500 text-white px-4 py-2 rounded">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>