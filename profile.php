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
            <a href="home.html" class="text-xl font-bold">Todo App</a>
            <div>
                <a href="#" class="text-sm hover:text-primary-300 mr-4">Profile</a>
                <a href="login-signup.html" class="text-sm hover:text-primary-300">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">User Profile</h1>
        
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <div class="flex items-center justify-center mb-6">
                <img src="./assets/user.png" alt="Profile Picture" class="rounded-full w-32 h-32">
            </div>
            <form>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" value="John Doe" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" value="johndoe@example.com" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="mb-6">
                    <label for="bio" class="block text-sm font-medium mb-2">Bio</label>
                    <textarea id="bio" name="bio" rows="3" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">I love staying organized with my todo app!</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>