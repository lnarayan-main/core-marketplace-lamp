<?php
session_start();
require_once '../config/database.php';
include_once '../includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Check if email exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->rowCount() > 0) {
            $message = "Email already registered!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $password])) {
                header("Location: login.php?registered=success");
                exit;
            }
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-center">Create an Account</h2>
    
    <?php if($message): ?>
        <p class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Register</button>
    </form>
    <p class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
</div>

<?php include_once '../includes/footer.php'; ?>