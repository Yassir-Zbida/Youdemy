<?php
require_once '../classes/course.php';
require_once '../classes/user.php';
require_once '../classes/db.php';
require_once '../classes/category.php';
require_once '../classes/instructor.php';
require_once '../classes/tag.php';
session_start();
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
$instructorId = $_SESSION['user_id'];
$instructor = new Instructor($db);
$courseId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$courseId) {
    die("Course ID is missing");
}
$course = new Course();
$courseDetails = $course->getCourseById($courseId);


$selectedTags = $course->getCourseTags($courseId);
$selectedTagIds = array_map(function ($tag) {
    return $tag['id'];
}, $selectedTags);

$tags = $tag->getTags();

if (!$courseDetails) {
    die("Course not found");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
    <script src="../assets/scripts/editCourse.js" defer></script>
</head>

<body>
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
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6" id="courseForm">
        <form class="space-y-6" method="POST" enctype="multipart/form-data" action="edit_course.php">
            <div>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($courseDetails['id']); ?>">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-4">Title</label>
                <input type="text" name="title" id="title"
                    value="<?php echo htmlspecialchars($courseDetails['title']); ?>" required
                    class="mt-1 block w-full rounded-md p-2 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-4">Description</label>
                <textarea id="description" name="description" rows="3" required value=""
                    class="mt-1 block w-full rounded-md border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"><?php echo htmlspecialchars($courseDetails['description']); ?></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-4">Price</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" name="price" id="price" min="0" step="0.01"
                            value="<?php echo htmlspecialchars($courseDetails['price']); ?>" required
                            class="mt-1 block w-full rounded-md p-2 pl-7 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                </div>

                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-4">Difficulty</label>
                    <select id="difficulty" name="difficulty" required
                        class="mt-1 block w-full rounded-md p-2 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="Beginner" <?php echo $courseDetails['Difficulty'] == 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                        <option value="Intermediate" <?php echo $courseDetails['Difficulty'] == 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="Advanced" <?php echo $courseDetails['Difficulty'] == 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Duration</label>
                    <div class="flex space-x-2">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="number" name="duration" id="duration" min="0" placeholder="0" required
                                    value="<?php echo floor($courseDetails['Duration']); ?>"
                                    class="mt-1 block w-full rounded-md p-2 pr-14 border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-center">
                                <span
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 text-sm">hours</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="thumbnail-upload" class="flex items-center space-x-6">
                <div class="flex-1">
                    <label class="block my-4 text-sm font-medium text-gray-700">Thumbnail</label>
                    <img src="../uploads/thumbnails/<?php echo htmlspecialchars($courseDetails['thumbnail']); ?>"
                        class="rounded-lg w-full h-full max-h-40 object-cover" id="thumbnail-image">
                </div>

                <div class="flex-[2]">
                    <div class="flex justify-center items-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md"
                        style="height: 100%; max-height: 10rem;">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload-thumbnail"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                    <span>Upload thumbnail</span>
                                    <input id="file-upload-thumbnail" name="file-upload-thumbnail" type="file"
                                        class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">Image files (JPG/PNG) up to 2MB</p>
                            <p id="thumbnail-file-name" class="text-xs text-gray-700 mt-2"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-4">Category</label>
                <select id="category" name="category" required
                    class="mt-1 block p-2 w-full rounded-md border border-gray-200 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <?php
                    foreach ($categories as $cat) {
                        $selected = $courseDetails['categoryId'] == $cat['id'] ? 'selected' : '';
                        echo "<option value=\"" . $cat['id'] . "\" $selected>" . htmlspecialchars($cat['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 mb-4">Select Tags</label>
                <div class="flex flex-wrap space-x-4">
                    <?php
                    foreach ($tags as $tag) {
                        $isChecked = in_array($tag['id'], $selectedTagIds) ? 'checked' : '';
                        echo "<div class='flex items-center space-x-2'>
                <input type='checkbox' id='tag-{$tag['id']}' name='tags[]' value='{$tag['id']}' $isChecked class='h-5 w-5'>
                <label for='tag-{$tag['id']}' class='text-sm text-gray-700'>" . htmlspecialchars($tag['name']) . "</label>
              </div>";
                    }
                    ?>
                </div>
            </div>



            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">Content Type</label>
                <div>
                    <select id="content-type" name="content-type"
                        class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="">-- Select content type --</option>
                        <option value="video" <?= ($courseDetails['type'] === 'video') ? 'selected' : '' ?>>Video</option>
                        <option value="document" <?= ($courseDetails['type'] === 'document') ? 'selected' : '' ?>>Document
                        </option>
                    </select>
                </div>

                <div id="video-upload" class="<?= ($courseDetails['type'] === 'video') ? '' : 'hidden' ?>">
                    <label class="block my-4 text-sm font-medium text-gray-700">Video:</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload-video"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                    <span>Upload video</span>
                                    <input id="file-upload-video" name="file-upload-video" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">MP4/AVI file up to 10MB</p>
                            <p id="file-name" class="text-xs text-gray-700 mt-2">
                                <?= !empty($courseDetails['videoUrl']) ? htmlspecialchars($courseDetails['videoUrl']) : '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div id="document-upload" class="<?= ($courseDetails['type'] === 'document') ? '' : 'hidden' ?>">
                    <label class="block my-4 text-sm font-medium text-gray-700">Document:</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload-document"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-400 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-yellow-500">
                                    <span>Upload file</span>
                                    <input id="file-upload-document" name="file-upload-document" type="file"
                                        class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PDF document up to 10MB</p>
                            <p id="document-file-name" class="text-xs text-gray-700 mt-2">
                                <?= !empty($courseDetails['document']) ? htmlspecialchars($courseDetails['document']) : '' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex justify-end">
                <button type="submit" name="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Edit Course
                </button>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <footer class="bg-yellow-10 py-16 ">
        <div class="px-5">
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

            <div class="flex flex-col md:flex-row justify-between items-center pt-12 mt-12 border-t border-gray-200">
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