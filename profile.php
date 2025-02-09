<?php
session_start();
require_once "./server/dbcon.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$message = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    
    if (empty($name) || empty($email)) {
        $error = "Name and email are required";
    } else {
        // Check if email is changed and already exists
        if ($email !== $user['email']) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->rowCount() > 0) {
                $error = "Email already exists";
            }
        }
        
        if (empty($error)) {
            // Start transaction
            $pdo->beginTransaction();
            try {
                // Update basic info
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, bio = ? WHERE id = ?");
                $stmt->execute([$name, $email, $bio, $_SESSION['user_id']]);
                
                // Update password if provided
                if (!empty($currentPassword) && !empty($newPassword)) {
                    if (password_verify($currentPassword, $user['password'])) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
                        $message = "Profile and password updated successfully";
                    } else {
                        throw new Exception("Current password is incorrect");
                    }
                } else {
                    $message = "Profile updated successfully";
                }
                
                $pdo->commit();
                $_SESSION['user_name'] = $name; // Update session name
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Todo App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold">Todo App</a>
            <div>
                <span class="text-sm mr-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="index.php" class="text-sm hover:text-primary-300 mr-4">Todos</a>
                <form action="sign-out.php" method="POST" class="inline">
                    <button type="submit" class="text-sm hover:text-primary-300">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">User Profile</h1>
        
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <?php if ($message): ?>
                <div class="mb-4 p-3 bg-green-500/50 text-white rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-500/50 text-white rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="flex items-center justify-center mb-6">
                <div class="w-32 h-32 bg-gray-700 rounded-full flex items-center justify-center text-4xl font-bold">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
            </div>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" 
                        value="<?php echo htmlspecialchars($user['name']); ?>" 
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" 
                        required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" 
                        value="<?php echo htmlspecialchars($user['email']); ?>" 
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" 
                        required>
                </div>
                
                <div class="mb-6">
                    <label for="bio" class="block text-sm font-medium mb-2">Bio</label>
                    <textarea id="bio" name="bio" rows="3" 
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    ><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>

                <div class="border-t border-gray-700 my-6 pt-6">
                    <h2 class="text-lg font-semibold mb-4">Change Password</h2>
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" 
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div class="mb-6">
                        <label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password" 
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <p class="text-sm text-gray-400 mt-1">Leave password fields empty if you don't want to change it</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                        class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>