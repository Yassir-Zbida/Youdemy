<?php
require_once '../classes/course.php';
require_once '../classes/user.php';
require_once '../classes/db.php';
require_once '../classes/category.php';
require_once '../classes/admin.php';
require_once '../classes/tag.php';
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
if ($userRole != 'Admin') {
    header('Location: ../index.php');
}
$db = new Database();
$category = new category($db);
$categories = $category->getCategories();
$tag = new Tag($db);
$tags = $tag->getTags();
$adminId = $_SESSION['user_id'];
$admin = new Admin($db, $adminId);
$totalStudents = $admin->countStudents();
$totalCourses = $admin->countCourses();
$newUserRate = $admin->getNewUserRate();
$newCoursesRate = $admin->getNewCoursesRate();
$averageCompletionRate = $admin->getAverageCompletionRate();
$totalEnrollment = $admin->getTotalEnrollment();
$topInstructors = $admin->getTopInstructors(2);
$topCourses = $admin->getTopCourses(2);
$recentEnrollments = $admin->getRecentEnrollments(10);


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
                    <a href="./admin_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-yellow-400 rounded-md bg-gray-800">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span class="text-lg">Overview</span>
                    </a>
                    <a href="./users_dashbord.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 rounded-md">
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
                        <h1 class="text-2xl font-semibold ml-3">Welcome, Admin 👋</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Search for courses"
                                class="bg-gray-100 rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <button
                            class="bg-yellow-50 text-yellow-600 px-4 py-2 rounded-md text-sm font-medium">Enter</button>
                        <i class="ri-notification-line text-xl text-gray-400"></i>
                        <i class="ri-settings-3-line text-xl text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto">
                <div class="grid grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-blue-100 w-12 h-12 flex justify-center items-center p-3 rounded-full">
                                <i class="ri-user-line text-xl text-blue-600"></i>
                            </div>
                            <span
                                class="ml-2 text-lg text-green-600 font-bold"><?php echo ($newUserRate >= 0 ? '+' : '') . number_format($newUserRate, 1) . '%'; ?></span>
                        </div>
                        <h3 class="text-gray-500 text-sm">Total Students</h3>
                        <p class="text-2xl font-bold">
                            <?php echo htmlspecialchars($totalStudents) ?>
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-yellow-100 w-12 h-12 flex justify-center items-center p-3 rounded-full">
                                <i class="ri-book-open-line text-xl text-yellow-600"></i>
                            </div>
                            <span
                                class="ml-2 text-lg text-green-600 font-bold ">+<?php echo htmlspecialchars($newCoursesRate); ?>%</span>
                        </div>
                        <h3 class="text-gray-500 text-sm">Published Courses</h3>
                        <p class="text-2xl font-bold">
                            <?php echo htmlspecialchars($totalCourses) ?>
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-green-100 w-12 h-12 flex justify-center items-center p-3 rounded-full">
                                <i class="ri-user-star-line text-xl text-green-600"></i>
                            </div>
                            <span class="ml-2 text-lg text-green-600 font-bold ">+8.4%</span>
                        </div>
                        <h3 class="text-gray-500 text-sm">Total Enrollments</h3>
                        <p class="text-2xl font-bold">
                            <?php echo htmlspecialchars($totalEnrollment); ?>
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-purple-100 w-12 h-12 flex justify-center items-center p-3 rounded-full">
                                <i class="ri-medal-line text-xl text-purple-600"></i>
                            </div>
                            <span class="ml-2 text-lg text-green-600 font-bold ">+15.7%</span>
                        </div>
                        <h3 class="text-gray-500 text-sm">Completion Rate</h3>
                        <p class="text-2xl font-bold">
                            <?php echo htmlspecialchars(number_format($averageCompletionRate, 1)) . '%'; ?>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8 ">
                    <div class="bg-white p-6 rounded-lg border shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold">Top Instructors</h2>
                            <a href="./users_dashbord.php"><button
                                    class="text-sm text-yellow-400 hover:text-yellow-700">View All</button></a>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($topInstructors as $instructor): ?>
                                <div
                                    class="flex border items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3">
                                        <img src="../uploads/avatars/<?= htmlspecialchars($instructor['profile_image'] ?: 'simple.png') ?>"
                                            alt="Instructor" class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($instructor['instructor_name']) ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium"><?= htmlspecialchars($instructor['enrollment_count']) ?>
                                            Enrollments</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <div class="bg-white border p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold">Top Courses</h2>
                            <button class="text-sm text-yellow-400 hover:text-yellow-700">View All</button>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($topCourses as $course): ?>
                                <div
                                    class="flex border px-4 items-center justify-between py-4 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <img src="../uploads/thumbnails/<?= htmlspecialchars($course['thumbnail'] ?: 'simple.png') ?>"
                                                alt="courses" class="w-16 h-10 rounded-lg object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($course['course_name']) ?></h3>
                                            <p class="text-sm text-gray-500">
                                                <?= number_format($course['enrollment_count']) ?> students
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium"><?= '$' . number_format($course['price'], 2) ?></p>
                                        <p class="text-sm text-green-600"><?= $course['sales_percentage'] ?>% sales</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Recent Enrollments</h2>
                            <div class="flex items-center gap-4">
                                <a href=""><button class="text-sm text-yellow-400 font-bold underline hover:text-yellow-700">View All</button></a>
                            </div>
                        </div>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50 text-sm text-gray-500">
                            <tr>
                                <th class="text-left p-4">Student</th>
                                <th class="text-left p-4">Course</th>
                                <th class="text-left p-4">Price</th>
                                <th class="text-left p-4">Progress</th>
                                <th class="text-right pr-[60px] p-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($recentEnrollments as $enrollment): ?>
                                <tr class="border-b">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <img src="../uploads/avatars/<?= htmlspecialchars($enrollment['student_avatar'] ?: 'simple.png') ?>"
                                                alt="Student" class="w-10 h-10 rounded-full object-cover">
                                            <div>
                                                <p class="font-medium"><?= htmlspecialchars($enrollment['student_name']) ?>
                                                </p>
                                                <p class="text-gray-500 text-xs">
                                                    <?= htmlspecialchars($enrollment['student_email']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4"><?= htmlspecialchars($enrollment['course_name']) ?></td>
                                    <td class="p-4"><?= '$' . number_format($enrollment['course_price'], 2) ?></td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-2 bg-gray-200 rounded-full">
                                                <div class="h-full bg-yellow-400 rounded-full"
                                                    style="width: <?= htmlspecialchars($enrollment['progress']) ?>%"></div>
                                            </div>
                                            <span
                                                class="text-sm text-gray-600"><?= htmlspecialchars($enrollment['progress']) ?>%</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right text-gray-500">
                                        <?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
</body>

</html>