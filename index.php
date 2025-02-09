<?php
session_start();
require_once "./server/dbcon.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

// Handle todo actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = trim($_POST['todo_name']);
                if (!empty($name)) {
                    $stmt = $pdo->prepare("INSERT INTO todo (user_id, name) VALUES (?, ?)");
                    $stmt->execute([$_SESSION['user_id'], $name]);
                }
                break;
            case 'update_status':
                $stmt = $pdo->prepare("UPDATE todo SET status = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$_POST['status'], $_POST['todo_id'], $_SESSION['user_id']]);
                break;
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM todo WHERE id = ? AND user_id = ?");
                $stmt->execute([$_POST['todo_id'], $_SESSION['user_id']]);
                break;
        }
        // Redirect to prevent form resubmission
        header("Location: index.php");
        exit();
    }
}

// Fetch user's todos
$stmt = $pdo->prepare("SELECT * FROM todo WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Todo App</title>
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
    <!-- Navigation -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold">Todo App</a>
            <div>
                <span class="text-sm mr-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="profile.php" class="text-sm hover:text-primary-300 mr-4">Profile</a>
                <form action="sign-out.php" method="POST" class="inline">
                    <button type="submit" class="text-sm hover:text-primary-300">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Todo Management</h1>
        
        <!-- Add Todo Form -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Todo</h2>
            <form method="POST" class="flex">
                <input type="hidden" name="action" value="add">
                <input type="text" name="todo_name" placeholder="Enter your todo" required
                    class="flex-grow px-3 py-2 bg-gray-700 border border-gray-600 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-r-md focus:outline-none focus:shadow-outline">
                    Add
                </button>
            </form>
        </div>

        <!-- Todo List -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Your Todos</h2>
            <?php if (empty($todos)): ?>
                <p class="text-gray-400">No todos yet. Add your first todo above!</p>
            <?php else: ?>
                <ul class="space-y-4">
                    <?php foreach ($todos as $todo): ?>
                        <li class="flex items-center justify-between bg-gray-800 p-4 rounded-md <?php echo $todo['status'] === 'completed' ? 'opacity-75' : ''; ?>">
                            <span class="<?php echo $todo['status'] === 'completed' ? 'line-through text-gray-400' : ''; ?>">
                                <?php echo htmlspecialchars($todo['name']); ?>
                            </span>
                            <div class="flex items-center space-x-2">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" 
                                        class="bg-gray-700 border border-gray-600 rounded px-2 py-1 text-sm">
                                        <option value="pending" <?php echo $todo['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="in_progress" <?php echo $todo['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed" <?php echo $todo['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </form>
                                <button onclick="confirmDelete(<?php echo $todo['id']; ?>)" 
                                    class="text-red-500 hover:text-red-400">
                                    Delete
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">Confirm Delete</h3>
            <p class="mb-6">Are you sure you want to delete this todo? This action cannot be undone.</p>
            <div class="flex justify-end space-x-4">
                <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="todo_id" id="deleteTaskId">
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(todoId) {
            document.getElementById('deleteTaskId').value = todoId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>