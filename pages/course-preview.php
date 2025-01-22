<?php
require_once '../classes/user.php';
require_once '../classes/course.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
$menuItems = User::getMenuItems($userRole);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid course ID");
}

$courseId = intval($_GET['id']);
$course = new Course();
$courseDetails = $course->getCourseById($courseId);
$instructorId = $courseDetails['instructorId'];
$instructorInfo = $course->getInstructorInfo($instructorId);

$studentId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if (!$courseDetails) {
    die("Course not found");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course - <?= htmlspecialchars($courseDetails['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
    <script src="../assets/scripts/main.js" defer></script>
</head>

<body>

    <!-- Header container -->
    <div class="flex flex-col">

        <div class="hidden md:block w-full bg-[#f2b212] text-white">
            <div class="container mx-auto px-4 py-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-6">
                        <span class="flex items-center">
                            <i class="ri-phone-line mr-2"></i> +212 772508881
                        </span>
                        <span class="flex items-center">
                            <i class="ri-mail-line mr-2"></i> contact@youdemy.com
                        </span>
                    </div>
                    <span class="flex items-center">
                        <i class="ri-map-pin-line mr-2"></i> Massira N641 Safi, Morocco
                    </span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="border-b bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <a href="../index.php">
                        <img src="../assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
                    </a>
                    <nav class="hidden md:flex items-center space-x-6">
                        <?php foreach ($menuItems as $item): ?>
                            <a href="<?= $item[1] ?>" class="text-gray-900 hover:text-yellow-500 transition-colors">
                                <?= $item[0] ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <?php if (!$isLoggedIn): ?>
                            <button
                                class="p-2 px-4 bg-yellow-400 text-white rounded-full hover:bg-white hover:text-yellow-400 hover:border hover:border-yellow-400 transition-colors">
                                <a href="./login.php">Login</a>
                            </button>
                            <button
                                class="p-2 px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">
                                <a href="./register.php">Register</a>
                            </button>
                        <?php else: ?>
                            <button
                                class="p-2 px-4 bg-red-400 text-white rounded-full hover:bg-white hover:text-red-400 hover:border hover:border-red-400 transition-colors">
                                <a href="./logout.php">Logout</a>
                            </button>
                        <?php endif; ?>
                        <button id="mobile-menu-btn" class="p-2 hover:text-yellow-500 transition-colors md:hidden">
                            <i class="ri-menu-4-fill text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </div>


    <div class="sm:px-6 lg:px-8 py-4">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="../index.php" class="hover:text-gray-700">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="../pages/courses.php" class="hover:text-gray-700">Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-gray-700"><?= htmlspecialchars($courseDetails['title']) ?></span>
        </div>
    </div>

    <div class="sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
            <div class="flex">
                <div class="rounded-lg overflow-hidden flex-grow">
                    <img src="../uploads/thumbnails/<?= htmlspecialchars($courseDetails['thumbnail']) ?>"
                        alt="<?= htmlspecialchars($courseDetails['title']) ?>"
                        class="rounded-lg h-full object-cover w-full">
                </div>
            </div>

            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($courseDetails['title']) ?></h1>
                    <div class="text-4xl font-bold mb-8 text-yellow-400">
                        <?= htmlspecialchars($courseDetails['price']) ?><span class="text-sm font-normal">USD</span>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center gap-2">
                                <i class="ri-layout-grid-line"></i>
                                <span>Category</span>
                            </div>
                            <span><?= htmlspecialchars($courseDetails['category_name'] ?? 'General') ?></span>
                        </div>

                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center gap-2">
                                <i class="ri-signal-tower-line"></i>
                                <span>Difficulty</span>
                            </div>
                            <span><?= htmlspecialchars($courseDetails['Difficulty'] ?? 'Unknown') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center gap-2">
                                <i class="ri-user-line"></i>
                                <span>Students</span>
                            </div>
                            <span><?= htmlspecialchars($courseDetails['student_count'] ?? '0') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center gap-2">
                                <i class="ri-time-line"></i>
                                <span>Duration</span>
                            </div>
                            <span><?= htmlspecialchars($courseDetails['Duration'] ?? 'Unknown') ?></span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button
                        class="md:mt-6 flex-1 bg-yellow-400 text-white py-3 font-bold text-lg rounded-lg hover:bg-gray-800">
                        <a href="enroll.php?courseId=<?= urlencode($courseId) ?>">Enroll Now</a>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="sm:px-6 lg:px-8 py-4 mt-12">
        <div class="bg-red-600 shadow-sm border rounded-lg bg-white p-4 py-6 pl-8">
            <h2 class="text-2xl font-bold mb-4 text-yellow-400">Course Description</h2>
            <div class="prose max-w-none">
                <p class="text-gray-600 leading-relaxed">
                    <?= htmlspecialchars($courseDetails['description']) ?>
                </p>
            </div>
        </div>

        <div class="border rounded-lg bg-white p-4 py-6 mt-12 pl-8">
            <div class="flex justify-between items-center space-x-4">
                <div class="w-[15%]">
                    <img src="../uploads/avatars/<?php echo htmlspecialchars(isset($instructorInfo['avatarImg']) && $instructorInfo['avatarImg'] !== null ? $instructorInfo['avatarImg'] : 'simple.png'); ?>"
                        class="rounded-full w-32 h-32 object-cover">
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-black">
                        <?php echo htmlspecialchars($instructorInfo['username']); ?>
                    </h3>
                    <p class="text-gray-500 mt-2">
                        <?php echo htmlspecialchars(isset($instructorInfo['poste']) && $instructorInfo['poste'] !== null ? $instructorInfo['poste'] : 'Instructor'); ?>
                    </p>
                    <div class="flex items-center space-x-4 mt-2 text-gray-600">
                        <div class="flex items-center">
                            <i class="ri-user-3-line mr-1 text-yellow-400"></i>
                            <span><?php echo htmlspecialchars($instructorInfo['total_students']); ?> Students</span>
                        </div>
                        <div class="flex items-center">
                            <i class="ri-video-line mr-1 text-yellow-400"></i>
                            <span><?php echo $instructorInfo['total_courses']; ?> Courses</span>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        <?php
                        echo nl2br(htmlspecialchars(
                            isset($instructorInfo['bio']) && $instructorInfo['bio'] !== null
                            ? $instructorInfo['bio']
                            : 'Youdemy Instructor: A passionate educator dedicated to helping learners achieve their goals through engaging and insightful courses. With expertise in various fields, our instructors bring a wealth of knowledge and experience to empower students worldwide.'
                        ));
                        ?>
                    </p>
                </div>
            </div>
        </div>


    </div>


    <!-- Footer Section -->
    <footer class="bg-yellow-10 py-16 ">
        <div class="px-10">
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
                    <p class="text-gray-600 mb-6">Eros in cursus turpis massa tincidunt Faucibus scelerisque eleifend
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