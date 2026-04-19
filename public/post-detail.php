<?php
session_start();
require_once '../config/database.php';
include_once '../includes/header.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("
    SELECT posts.*, users.name as author 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE posts.id = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<div class='text-center mt-20'><h2 class='text-2xl'>Post not found.</h2><a href='index.php' class='text-blue-500'>Go Home</a></div>";
    include_once '../includes/footer.php';
    exit;
}
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="index.php" class="text-blue-600 hover:underline mb-6 inline-block">&larr; Back to Listings</a>
    
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <img src="../uploads/<?php echo $post['image']; ?>" class="w-full h-96 object-cover" alt="">
        
        <div class="p-8">
            <div class="flex items-center space-x-4 mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full uppercase">Book Post</span>
                <span class="text-gray-500 text-sm"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-6"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="flex items-center mb-8 pb-8 border-b border-gray-100">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold">
                    <?php echo substr($post['author'], 0, 1); ?>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($post['author']); ?></p>
                    <p class="text-xs text-gray-500">Verified Seller</p>
                </div>
            </div>
            
            <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>