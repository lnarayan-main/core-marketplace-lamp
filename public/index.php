<?php
session_start();
require_once '../config/database.php';
include_once '../includes/header.php';

// Fetch all posts with the author's name using a JOIN
try {
    $stmt = $pdo->query("
        SELECT posts.*, users.name as author 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}
?>

<main class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Available Books & Posts</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="create-post.php" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition">
                + Create New Post
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($posts)): ?>
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-600">No posts found. Be the first to create one!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-200">
                    <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="w-full h-48 object-cover">
                    
                    <div class="p-5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                                By <?php echo htmlspecialchars($post['author']); ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </span>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-2 truncate">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h2>
                        
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            <?php echo htmlspecialchars(substr($post['content'], 0, 120)) . '...'; ?>
                        </p>
                        
                        <a href="post-detail.php?id=<?php echo $post['id']; ?>" 
                           class="block text-center bg-blue-50 text-blue-600 font-semibold py-2 rounded-lg hover:bg-blue-600 hover:text-white transition">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include_once '../includes/footer.php'; ?>