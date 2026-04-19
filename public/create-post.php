<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code: " . $_FILES['image']['error']);
    }

    // Image Upload Logic
    $file = $_FILES['image'];
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = "../uploads/" . $fileName;
    $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

    // Basic Validation
    $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $content, $fileName]);
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Only JPG, JPEG, PNG, & GIF files are allowed.";
    }
}

include_once '../includes/header.php';
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create a New Post</h2>

    <?php if ($error): ?>
        <p class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" name="title" required
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Book Description / Content</label>
            <textarea name="content" rows="5" required
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Upload Image</label>
            <input type="file" name="image" required class="w-full text-gray-700 px-3 py-2 border rounded-lg">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"
                class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 transition">Publish
                Post</button>
            <a href="dashboard.php" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>