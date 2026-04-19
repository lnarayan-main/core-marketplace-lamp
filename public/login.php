<?php
session_start();
require_once '../config/database.php';
include_once '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login to Marketplace</h2>
    
    <?php if(isset($_GET['registered'])): ?>
        <p class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm text-center">Registration successful! Please login.</p>
    <?php endif; ?>
    
    <?php if($error): ?>
        <p class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Login</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>