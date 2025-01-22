<?php
require_once '../classes/db.php';
require_once '../classes/admin.php';
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
if ($userRole != 'Admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$admin = new Admin($db, $_SESSION['user_id']);
$users = $admin->getUsers();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['status'])) {
    $userId = $_POST['user_id'];
    $status = $_POST['status'];

    if ($admin->updateUserStatus($userId, $status)) {
        header('Location: users_dashbord.php?message=User status updated successfully');
        exit;
    } else {
        header('Location: users_dashbord.php?message=Error updating user status');
        exit;
    }
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script type='text/javascript'>
        alert('$message'); 
        history.replaceState(null, '', window.location.pathname); // This removes the 'message' from the URL
    </script>";
}
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
        <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between">
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
                        class="flex items-center gap-3 px-3 py-2 text-sm text-yellow-400 rounded-md bg-gray-800">
                        <i class="ri-group-line text-xl"></i>
                        <span class="text-lg">Users</span>
                    </a>
                    <a href="./categories_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
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

            <div class="bottom-0 left-0 right-0 p-4">
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
                        <h1 class="text-2xl font-semibold ml-3">Users Management</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Search for users"
                                class="bg-gray-100 rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <button
                            class="bg-yellow-50 text-yellow-600 px-4 py-2 rounded-md text-sm font-medium">Search</button>
                        <i class="ri-notification-line text-xl text-gray-400"></i>
                        <i class="ri-settings-3-line text-xl text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto">
                <h2 class="text-xl font-medium mb-4">Students</h2>
                <div class="bg-white rounded-lg shadow-sm mb-6 border">

                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-50 text-sm text-gray-500">
                            <tr>
                                <th class="text-left p-4 border-b">ID</th>
                                <th class="text-left p-4 border-b">Username</th>
                                <th class="text-left p-4 border-b">Email</th>
                                <th class="text-left p-4 border-b">Status</th>
                                <th class="text-right pr-[115px] p-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['role'] == 'Student'): ?>
                                    <tr class="border-b last:border-none hover:bg-gray-50">
                                        <td class="p-4"><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td class="p-4"><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class="p-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="p-4">
                                            <?php
                                            $statusClass = '';
                                            $statusText = $user['status'];

                                            if ($statusText == 'pending') {
                                                $statusClass = 'bg-yellow-500 text-white';
                                            } elseif ($statusText == 'suspended') {
                                                $statusClass = 'bg-red-500 text-white';
                                            } elseif ($statusText == 'activated') {
                                                $statusClass = 'bg-green-500 text-white';
                                            }
                                            ?>
                                            <span class="px-4 py-2 rounded-lg <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($statusText); ?>
                                            </span>
                                        </td>

                                        <td class="p-4 flex items-center justify-end">
                                            <form action="users_dashbord.php" method="POST">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <select name="status" class="border rounded-lg p-2 px-3"
                                                    onchange="this.form.submit()">
                                                    <option value="pending" <?php echo ($user['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="suspended" <?php echo ($user['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                                                    <option value="activated" <?php echo ($user['status'] == 'activated') ? 'selected' : ''; ?>>Activated</option>
                                                </select>
                                            </form>
                                            <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                                                class="text-red-600 hover:text-red-800 ml-4"
                                                onclick="return confirm('Are you sure you want to delete this Student ?');">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h2 class="text-xl font-medium mb-4">Instructors</h2>
                <div class="bg-white rounded-lg shadow-sm mb-6 border">
                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-50 text-sm text-gray-500">
                            <tr>
                                <th class="text-left p-4 border-b">ID</th>
                                <th class="text-left p-4 border-b">Username</th>
                                <th class="text-left p-4 border-b">Email</th>
                                <th class="text-left p-4 border-b">Status</th>
                                <th class="text-right pr-[115px] p-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['role'] == 'Instructor'): ?>
                                    <tr class="border-b last:border-none hover:bg-gray-50">
                                        <td class="p-4"><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td class="p-4"><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class="p-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="p-4">
                                            <?php
                                            $statusClass = '';
                                            $statusText = $user['status'];

                                            if ($statusText == 'pending') {
                                                $statusClass = 'bg-yellow-500 text-white';
                                            } elseif ($statusText == 'suspended') {
                                                $statusClass = 'bg-red-500 text-white';
                                            } elseif ($statusText == 'activated') {
                                                $statusClass = 'bg-green-500 text-white';
                                            }
                                            ?>
                                            <span class="px-4 py-2 rounded-lg <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($statusText); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 flex items-center justify-end space-x-4">
                                            <form action="users_dashbord.php" method="POST">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <select name="status" class="rounded-lg border p-2 px-3"
                                                    onchange="this.form.submit()">
                                                    <option value="pending" <?php echo ($user['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="suspended" <?php echo ($user['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                                                    <option value="activated" <?php echo ($user['status'] == 'activated') ? 'selected' : ''; ?>>Activated</option>
                                                </select>
                                            </form>
                                            <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                                                class="text-red-600 hover:text-red-800 ml-4"
                                                onclick="return confirm('Are you sure you want to delete this user?');">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.querySelectorAll('td.flex').forEach(element => {
            element.classList.add('items-center', 'justify-end', 'space-x-4');
        });
    </script>

</body>

</html>