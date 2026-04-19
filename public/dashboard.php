<?php
session_start();
require_once '../config/database.php';

// Auth Protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once '../includes/header.php';

// Fetch only user's posts
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$myPosts = $stmt->fetchAll();
?>

<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <a href="create-post.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ New Post</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Image</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Title</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($myPosts)): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-center text-sm text-gray-500">You haven't posted anything yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($myPosts as $post): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <img src="../uploads/<?php echo $post['image']; ?>" class="w-12 h-12 rounded object-cover">
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($post['title']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-600">
                                <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                <a href="delete-post.php?id=<?php echo $post['id']; ?>" 
                                   onclick="return confirm('Are you sure?')" 
                                   class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>