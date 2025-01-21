<?php
require_once '../classes/course.php';
require_once '../classes/user.php';
require_once '../classes/db.php';
require_once '../classes/category.php';
require_once '../classes/instructor.php';
require_once '../classes/tag.php';


session_start();
if (isset($_SESSION['alert_message'])) {
    $message = $_SESSION['alert_message'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['alert_message']); 
}


$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
if ($userRole != 'Instructor') {
    header('Location: ../index.php');
}
$menuItems = User::getMenuItems($userRole);
$db = new Database();
$category = new category($db);
$categories = $category->getCategories();
$tag = new Tag($db);
$tags = $tag->getTags();
$instructorId = $_SESSION['user_id'];
$instructor = new Instructor($db);
$courseCount = $instructor->getCoursesCount($instructorId);
$enrolledCount = $instructor->getEnrolledStudentsCount($instructorId);
$courses = $instructor->getCoursesWithDetails($instructorId);
$completionRate = $instructor->getCompletionRate($instructorId);


if (isset($_SESSION['course_message'])) {
    echo "<script>alert('" . $_SESSION['course_message'] . "');</script>";
    unset($_SESSION['course_message']);
}

if (isset($_SESSION['course_message'])) {
    echo "<script>
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('courseMessageText').textContent = '" . $_SESSION['course_message'] . "';
                document.getElementById('courseMessageModal').classList.remove('hidden');
            });
          </script>";

    unset($_SESSION['course_message']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
    <script src="../assets/scripts/instructorDash.js" defer></script>
    <style>
        .text-gradient {
            background: linear-gradient(to right, #f2b212, #fadf10);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="min-h-screen ">
    <!-- Header -->
    <header class="border-b bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <a href="./instructor_dashboard.php">
                    <img src="../assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
                </a>
                <nav class="hidden md:flex items-center space-x-6">
                    <?php foreach ($menuItems as $item): ?>
                        <a href="<?= htmlspecialchars($item[1]) ?>"
                            class="text-gray-900 hover:text-yellow-500 transition-colors">
                            <?= htmlspecialchars($item[0]) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <div class="flex items-center space-x-4">
                    <?php if (!$isLoggedIn): ?>
                        <a href="./login.php"
                            class="p-2 px-4 bg-yellow-400 text-white rounded-full hover:bg-white hover:text-yellow-400 hover:border hover:border-yellow-400 transition-colors">Login</a>
                        <a href="./register.php"
                            class="p-2 px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">Register</a>
                    <?php else: ?>
                        <a href="./logout.php"
                            class="p-2 px-4 bg-red-400 text-white rounded-full hover:bg-white hover:text-red-400 hover:border hover:border-red-400 transition-colors">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg border">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-book-open-line text-2xl text-yellow-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completion Rate</dt>
                                <dd class="text-3xl font-semibold text-gray-900">
                                    <?php echo $completionRate . '%'; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg border">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-user-line text-2xl text-yellow-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Enrolled Students</dt>
                                <dd class="text-3xl font-semibold text-gray-900"><?php echo $enrolledCount; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg border">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-line text-2xl text-yellow-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Courses</dt>
                                <dd class="text-3xl font-semibold text-gray-900">
                                    <?php echo $courseCount; ?>
                                </dd>
                            </dl>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Course Management</h2>
                <button id="newCourseBtn"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-yellow-400 hover:bg-yellow-500">
                    <i class="ri-add-line mr-2"></i>
                    New Course
                </button>
            </div>

            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Students</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($course['title']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <?= htmlspecialchars($course['category'] ?: 'Uncategorized'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $course['students']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $course['status'] == 'Published' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?= htmlspecialchars($course['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                        <button class="text-yellow-600 hover:text-yellow-900 mr-4"
                                            onclick="window.location.href='editCourse.php?id=<?php echo $course['id']; ?>'">
                                            <i class="ri-edit-line text-lg"></i>
                                        </button>

                                        <button class="text-red-600 hover:text-red-900"
                                            onclick="confirmDelete(<?php echo $course['id']; ?>)">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="bg-white shadow rounded-lg border" id="add">
            <div class="px-4 py-5 sm:px-6 cursor-pointer flex justify-between items-center" id="toggleFormHeader">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Add New Course</h3>
                <i class="ri-arrow-down-s-line text-2xl text-gray-500 transition-transform duration-300"
                    id="toggleIcon"></i>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6" id="courseForm">
                <form class="space-y-6" method="POST" enctype="multipart/form-data" action="addCourse.php">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-4">Title</label>
                        <input type="text" name="title" id="title" required
                            class="mt-1 block w-full rounded-md p-2 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-4">Description</label>
                        <textarea id="description" name="description" rows="3" required
                            class="mt-1 block w-full rounded-md border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-4">Price</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                <input type="number" name="price" id="price" min="0" step="0.01" required
                                    class="mt-1 block w-full rounded-md p-2 pl-7 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                            </div>
                        </div>

                        <div>
                            <label for="difficulty"
                                class="block text-sm font-medium text-gray-700 mb-4">Difficulty</label>
                            <select id="difficulty" name="difficulty" required
                                class="mt-1 block w-full rounded-md p-2 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Duration</label>
                            <div class="flex space-x-2">
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="number" name="duration_hours" id="duration_hours" min="0"
                                            placeholder="0" required
                                            class="mt-1 block w-full rounded-md p-2 pr-14 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-center">
                                        <span
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 text-sm">hours</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="number" name="duration_minutes" id="duration_minutes" min="0"
                                            max="59" placeholder="0" required
                                            class="mt-1 block w-full rounded-md p-2 pr-16 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-center">
                                        <span
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 text-sm">mins</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="thumbnail-upload">
                        <label class="block my-4 text-sm font-medium text-gray-700">Thumbnail </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload-thumbnail"
                                        class="relative cursor-pointer text-center bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                        <span class="text-center">Upload thumbnail</span>
                                        <input id="file-upload-thumbnail" name="file-upload-thumbnail" type="file"
                                            required class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Image files (JPG/PNG) up to 2MB</p>
                                <p id="thumbnail-file-name" class="text-xs text-gray-700 mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-4">Category</label>
                        <select id="category" name="category" required
                            class="mt-1 block p-2 w-full rounded-md border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                            <?php
                            foreach ($categories as $cat) {
                                echo "<option value=\"" . $cat['id'] . "\">" . htmlspecialchars($cat['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>



                    <div class="mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Available Tags</label>
                        <div id="available-tags" class="space-y-2 space-x-1">
                            <?php
                            foreach ($tags as $tag) {
                                echo "<div class='tag-item space-x-2 inline-block p-2 px-4 border border-yellow-400 rounded-full cursor-pointer hover:bg-yellow-400' data-tag-id='" . $tag['id'] . "'>
                                          <span class='tag-name'>" . htmlspecialchars($tag['name']) . "</span>
                                      </div>";
                            }
                            ?>
                        </div>

                        <label for="selected-tags" class="block text-sm font-medium text-gray-700 mt-4 mb-2">Selected
                            Tags</label>
                        <div id="selected-tags" class="space-y-2 space-x-1"></div>
                        <input type="hidden" name="selectedTags" id="selected-tags-hidden">

                        <p class="text-xs text-gray-500 mt-1">Click on a tag to select or remove it.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Content Type</label>
                        <div>
                            <select id="content-type" name="content-type"
                                class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">-- Select content type --</option>
                                <option value="video">Video</option>
                                <option value="document">Document</option>
                            </select>
                        </div>

                        <div id="video-upload" class="hidden">
                            <label class="block my-4 text-sm font-medium text-gray-700">Video :</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                                <div class="space-y-1">
                                    <div class="flex text-sm text-gray-600 text-center justify-center">
                                        <label for="file-upload-video"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                            <span>Upload video</span>
                                            <input id="file-upload-video" name="file-upload-video" type="file"
                                                class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">MP4/AVI file up to 10MB</p>
                                    <p id="file-name" class="text-xs text-gray-700 mt-2"></p>
                                </div>
                            </div>
                        </div>

                        <div id="document-upload" class="hidden">
                            <label class="block my-4 text-sm font-medium text-gray-700">Document :</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="file-upload-document"
                                            class="relative cursor-pointer text-center bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                            <span class="text-center">Upload file</span>
                                            <input id="file-upload-document" name="file-upload-document" type="file"
                                                class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF document up to 10MB</p>
                                    <p id="document-file-name" class="text-xs text-gray-700 mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Section -->
        <footer class="bg-yellow-10 py-16">
            <div>
                <div class="mb-16">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <div
                            class="bg-yellow-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-yellow-400 hover:scale-95 transition-transform duration-300">
                            <i class="ri-team-line text-2xl text-yellow-500 mb-2"></i>
                            <p class="font-medium">Community</p>
                        </div>
                        <div
                            class="bg-yellow-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-yellow-400 hover:scale-95 transition-transform duration-300">
                            <i class="ri-link text-2xl text-yellow-500 mb-2"></i>
                            <p class="font-medium">Referrals</p>
                        </div>
                        <div
                            class="bg-yellow-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-yellow-400 hover:scale-95 transition-transform duration-300">
                            <i class="ri-book-2-line text-2xl text-yellow-500 mb-2"></i>
                            <p class="font-medium">Assignments</p>
                        </div>
                        <div
                            class="bg-yellow-50 p-6 rounded-lg text-center  hover:bg-transparent hover:border hover:border-yellow-400 hover:scale-95 transition-transform duration-300">
                            <i class="ri-medal-line text-2xl text-yellow-500 mb-2"></i>
                            <p class="font-medium">Certificates</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-1">
                        <div class="flex items-center gap-2 mb-4">
                            <img src="../assets/images/Youdemy_Logo.svg" height="200" width="200">
                        </div>
                        <p class="text-gray-600 mb-6">Eros in cursus turpis massa tincidunt Faucibus scelerisque
                            eleifend
                            vulputate sapien nec sagittis.</p>
                        <div class="flex gap-4">
                            <div
                                class="h-9 w-9 bg-yellow-400 flex justify-center items-center rounded-lg hover:border hover:border-yellow-400 hover:bg-transparent hover:text-yellow-400">
                                <a href="#" class="p-2 transition-colors">
                                    <i class="ri-facebook-fill text-xl "></i>
                                </a>
                            </div>

                            <div
                                class="h-9 w-9 bg-yellow-400 flex justify-center items-center rounded-lg hover:border hover:border-yellow-400 hover:bg-transparent hover:text-yellow-400">
                                <a href="#" class="p-2 transition-colors">
                                    <i class="ri-instagram-line text-xl "></i>
                                </a>
                            </div>

                            <div
                                class="h-9 w-9 bg-yellow-400 flex justify-center items-center rounded-lg hover:border hover:border-yellow-400 hover:bg-transparent hover:text-yellow-400">
                                <a href="#" class="p-2 transition-colors">
                                    <i class="ri-youtube-fill text-xl "></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Pages</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Home</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Courses</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">My Account</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">About</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Pricing</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Features</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Sign In / Register</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Our Newsletter</h3>
                        <div class="flex gap-2">
                            <input type="email" placeholder="Enter Your Email"
                                class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-yellow-500">
                            <button
                                class="px-6 py-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition-colors">Submit</button>
                        </div>
                        <p class="text-sm text-gray-600 mt-4">
                            By clicking "Subscribe", you agree to our
                            <a href="#" class="text-gray-900 hover:underline">Privacy Policy</a>.
                        </p>
                    </div>
                </div>

                <div
                    class="flex flex-col md:flex-row justify-between items-center pt-12 mt-12 border-t border-gray-200">
                    <p class="text-gray-600">&copy; 2024. All Rights Reserved.</p>
                    <div class="flex gap-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-600 hover:text-gray-900">Terms & Conditions</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">Privacy policy</a>
                    </div>
                </div>
            </div>
        </footer>

</body>

</html>