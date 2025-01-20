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
                    <a href="./admin_dashboard.php" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span class="text-lg">Overview</span>
                    </a>
                    <a href="./users_dashbord.php" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-group-line text-xl"></i>
                        <span class="text-lg">Users</span>
                    </a>
                    <a href="./categories_dashbord.php" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
                        <i class="ri-apps-line text-xl"></i>
                        <span class="text-lg">Categories</span>
                    </a>
                    <a href="./tags_dashbord.php" class="flex items-center gap-3 px-3 py-2 text-sm text-yellow-400 rounded-md bg-gray-800">
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
                  <button class="flex items-center justify-center text-white text-lg font-bold rounded-lg gap-2 p-2 text-center bg-red-600 w-[225px] text-sm text-gray-400 hover:text-white">
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
                        <h1 class="text-2xl font-semibold">Tags</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Search for courses" class="bg-gray-100 rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <button class="bg-yellow-50 text-yellow-600 px-4 py-2 rounded-md text-sm font-medium">Enter</button>
                        <i class="ri-notification-line text-xl text-gray-400"></i>
                        <i class="ri-settings-3-line text-xl text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto">
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold">Portfolio Value</h2>
                            <div class="flex items-center gap-4">
                                <button class="text-gray-500 text-sm">Export</button>
                                <select class="text-sm border rounded px-2 py-1">
                                    <option>Last 30 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-64 bg-gray-50"></div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-lg font-semibold mb-6">Available Revenue</h2>
                        <div class="flex items-center justify-center h-64">
                            <div class="relative w-40 h-40 rounded-full border-8 border-yellow-400">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">70%</div>
                                        <div class="text-sm text-gray-500">Unavailable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Revenue</h2>
                            <div class="flex items-center gap-4">
                                <select class="text-sm border rounded px-2 py-1">
                                    <option>All Transaction</option>
                                </select>
                                <select class="text-sm border rounded px-2 py-1">
                                    <option>Last 30 Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50 text-sm text-gray-500">
                            <tr>
                                <th class="text-left p-4">Date</th>
                                <th class="text-left p-4">ID</th>
                                <th class="text-left p-4">Value</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b">
                                <td class="p-4">Jan 25th, 2020</td>
                                <td class="p-4">#462980</td>
                                <td class="p-4">$95,550</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-50 text-green-600">
                                        <span class="w-1 h-1 bg-green-600 rounded-full mr-1"></span>
                                        Available
                                    </span>
                                </td>
                                <td class="p-4 text-gray-500">Personal Payment</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>