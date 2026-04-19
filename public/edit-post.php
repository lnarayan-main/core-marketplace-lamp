<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

// Fetch existing post data
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found or unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $imageName = $post['image']; // Keep old image by default

    // If new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $imageName = time() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], "../uploads/" . $imageName);
    }

    $update = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ? AND user_id = ?");
    $update->execute([$title, $content, $imageName, $post_id, $user_id]);
    header("Location: dashboard.php");
    exit;
}

include_once '../includes/header.php';
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Post</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Content</label>
            <textarea name="content" rows="5" required class="w-full px-3 py-2 border rounded-lg"><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Change Image (Optional)</label>
            <p class="text-xs text-gray-500 mb-2">Current: <?php echo $post['image']; ?></p>
            <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition">Update Post</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>