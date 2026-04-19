<?php
session_start();
require_once '../config/database.php';

// 1. Auth Guard: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($post_id) {
    try {
        // 2. Security Check: Fetch the post first to ensure it belongs to the logged-in user
        // This prevents User A from deleting User B's post by guessing the ID in the URL.
        $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        $post = $stmt->fetch();

        if ($post) {
            // 3. Physical Cleanup: Delete the image file from the /uploads folder
            $imagePath = "../uploads/" . $post['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); 
            }

            // 4. Database Cleanup: Remove the record
            $delete = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
            $delete->execute([$post_id, $user_id]);
            
            header("Location: dashboard.php?deleted=success");
            exit;
        } else {
            die("Unauthorized or Post not found.");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
}