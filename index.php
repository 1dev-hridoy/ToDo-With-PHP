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
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold">Todo App</a>
            <div>
                <a href="profile.html" class="text-sm hover:text-primary-300 mr-4">Profile</a>
                <a href="login-signup.html" class="text-sm hover:text-primary-300">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Todo Management</h1>
        
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Todo</h2>
            <form class="flex">
                <input type="text" placeholder="Enter your todo" class="flex-grow px-3 py-2 bg-gray-700 border border-gray-600 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-r-md focus:outline-none focus:shadow-outline">
                    Add
                </button>
            </form>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-4">Your Todos</h2>
            <ul class="space-y-4">
                <li class="flex items-center justify-between bg-gray-800 p-4 rounded-md">
                    <span>Complete project proposal</span>
                    <div>
                        <button class="text-green-500 hover:text-green-400 mr-2">Complete</button>
                        <button class="text-red-500 hover:text-red-400">Delete</button>
                    </div>
                </li>
                <li class="flex items-center justify-between bg-gray-800 p-4 rounded-md">
                    <span>Buy groceries</span>
                    <div>
                        <button class="text-green-500 hover:text-green-400 mr-2">Complete</button>
                        <button class="text-red-500 hover:text-red-400">Delete</button>
                    </div>
                </li>
                <li class="flex items-center justify-between bg-gray-800 p-4 rounded-md">
                    <span>Schedule team meeting</span>
                    <div>
                        <button class="text-green-500 hover:text-green-400 mr-2">Complete</button>
                        <button class="text-red-500 hover:text-red-400">Delete</button>
                    </div>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>