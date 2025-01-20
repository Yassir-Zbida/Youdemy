<?php
require_once '../classes/user.php';
require_once '../classes/db.php';
require_once '../classes/category.php';
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
if ($userRole != 'Admin') {
    header('Location: ../index.php');
}
$AdminId = $_SESSION['user_id'];
$db = new Database();
$category = new category($db);
$categories = $category->getCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-4">
                <div class="flex items-center gap-2 mb-8">
                    <img src="../assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
                </div>

                <nav class="space-y-1">
                    <a href="./admin_dashboard.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span class="text-lg">Overview</span>
                    </a>
                    <a href="./users_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-group-line text-xl"></i>
                        <span class="text-lg">Users</span>
                    </a>
                    <a href="./categories_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-yellow-400 rounded-md bg-gray-800">
                        <i class="ri-apps-line text-xl"></i>
                        <span class="text-lg">Categories</span>
                    </a>
                    <a href="./tags_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-price-tag-3-line text-xl"></i>
                        <span class="text-lg">Tags</span>
                    </a>
                </nav>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="flex items-center gap-3 mb-4">
                    <img src="../uploads/avatars/simple.png" alt="Profile" class="w-10 h-10 rounded-full">
                    <div>
                        <div class="text-sm font-medium">Youdemy Admin</div>
                        <div class="text-xs text-gray-400">View Profile</div>
                    </div>
                </div>
                <a href="./logout.php">
                    <button
                        class="flex items-center justify-center text-white text-lg font-bold rounded-lg gap-2 p-2 text-center bg-red-600 w-[225px] text-sm text-gray-400 hover:text-white">
                        <i class="ri-logout-box-line"></i>
                        <span>Log out</span>
                    </button>
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-auto">
            <header class="bg-white border-b p-4">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <div>
                        <h1 class="text-2xl font-semibold ml-3">Categories Management</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Enter catagory name"
                                class="bg-gray-100 rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <button class="bg-yellow-50 text-yellow-600 px-4 py-2 rounded-md text-sm font-medium">Search
                            </button>
                        <i class="ri-notification-line text-xl text-gray-400"></i>
                        <i class="ri-settings-3-line text-xl text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm mb-6 border">
                    <form action="add_category.php" method="POST" class="flex items-center gap-4 p-6">
                        <input type="text" name="category_name" placeholder="Enter category name"
                            class="p-2 border border-gray-300 rounded-lg w-64" required>
                        <button type="submit" class="bg-yellow-500 text-white p-2 px-4 rounded-lg hover:bg-yellow-600">
                            Add Category
                        </button>
                    </form>
                </div>
                <div class="bg-white rounded-lg shadow-sm">

                    <div class="overflow-hidden rounded-lg">
                        <table class="w-full border border-gray-200">
                            <thead class="bg-gray-50 text-sm text-gray-500">
                                <tr>
                                    <th class="text-left p-4 border-b border-gray-200">ID</th>
                                    <th class="text-left p-4 border-b border-gray-200">Name</th>
                                    <th class="text-right p-4 border-b border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="border-b last:border-none hover:bg-gray-50">
                                        <td class="p-4 border-b border-gray-200">
                                            <?php echo htmlspecialchars($category['id']); ?>
                                        </td>
                                        <td class="p-4 border-b border-gray-200">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </td>
                                        <td class="p-4 text-right pr-8 border-b border-gray-200">
                                            <a href="delete_category.php?id=<?php echo $category['id']; ?>"
                                                class="text-red-600 hover:text-red-800 ml-4"
                                                onclick="return confirm('Are you sure you want to delete this category ?');">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>