<?php
require_once './classes/user.php';
require_once './classes/course.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
$menuItems = User::getMenuItems($userRole);

$db = new Database();
$course = new Course($db);
$courses = $course->browseCourses($db, 6, 1);
$grid_courses = array_slice($courses, 0, 6);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.svg">
    <script src="./assets/scripts/main.js" defer></script>
    <style>
        .text-gradient {
            background: linear-gradient(to right, #f2b212, #fadf10);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>

<!-- main container -->
    <div class="min-h-screen flex flex-col">

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
                    <a href="./index.php">
                        <img src="./assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
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
                                <a href="./pages/login.php">Login</a>
                            </button>
                            <button
                                class="p-2 px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">
                                <a href="./pages/register.php">Register</a>
                            </button>
                        <?php else: ?>
                            <button
                                class="p-2 px-4 bg-red-400 text-white rounded-full hover:bg-white hover:text-red-400 hover:border hover:border-red-400 transition-colors">
                                <a href="./pages/logout.php">Logout</a>
                            </button>
                        <?php endif; ?>
                        <button id="mobile-menu-btn" class="p-2 hover:text-yellow-500 transition-colors md:hidden">
                            <i class="ri-menu-4-fill text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section
            class="hero bg-bg-yellow-500/5 flex-grow flex items-center bg-opacity-20 bg-[url('./assets/images/hero-bg1.png')]  bg-cover bg-center">
            <div class="container mx-auto flex flex-col items-center py-12 px-6 md:px-12">
                <div class="text-center space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold">
                        Learn Anything, Anytime, Anywhere <br>
                        <span class="text-gradient md:leading-relaxed">Your Future Starts Here</span>
                    </h1>
                    <p class="text-gray-600 md:text-lg">
                        Empower Your Mind with World-Class Learning – Join Youdemy Today
                    </p>

                    <!-- Search Form -->
                    <div class="mt-8">
                        <form action="./pages/search.php" method="GET" class="relative">
                            <input type="text" name="query" placeholder="What Do You Need To Learn?"
                                class="w-full p-3 pl-4 rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                            <button type="submit"
                                class="bg-yellow-400 absolute right-1 top-1 bottom-1 px-4 text-white rounded-full hover:bg-yellow-500">
                                Search
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center flex justify-center space-x-2">
                        <span class="text-yellow-400 text-xl">
                            <i class="ri-star-fill"></i>
                        </span>
                        <p class="text-gray-600">5-Star Ratings for Learning, Course Quality, and Student Success</p>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- Courses Categories Section  -->
    <section class="py-16 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">
                    Explore Top Courses
                    <span
                        class="bg-gradient-to-r from-yellow-600 to-yellow-300 bg-clip-text text-transparent">Categories</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Find the perfect course to enhance your skills and advance your career. Choose from our wide range
                    of professional courses designed by industry experts.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-palette-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Art Illustration</h3>
                            <p class="text-gray-500 text-sm">2 Courses</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-computer-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Digital Marketing</h3>
                            <p class="text-gray-500 text-sm">2 Courses</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-pen-nib-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Graphic Design</h3>
                            <p class="text-gray-500 text-sm">1 Course</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-robot-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Robotics</h3>
                            <p class="text-gray-500 text-sm">1 Course</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-layout-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Web Designing</h3>
                            <p class="text-gray-500 text-sm">1 Course</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100 hover:border-yellow-400 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-400 text-white rounded-lg">
                            <i class="ri-code-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Web Development</h3>
                            <p class="text-gray-500 text-sm">1 Course</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Courses Grid Section -->
    <section>
        <div class="py-10 md:px-12 px-6">
            <h2 class="text-4xl font-bold text-gray-800 mb-6 text-center md:mb-11">
                Explore Our <span
                    class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-600">Courses</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div
                            class="bg-white border border-yellow-400 rounded-lg shadow-md p-4 hover:scale-105 transition-transform">
                            <img src="../uploads/thumbnails/<?= htmlspecialchars($course['thumbnail']); ?>" alt="Course Image"
                                class="rounded-t-lg w-full">
                            <div class="py-3">
                                <p class="text-sm text-gray-500 flex items-center space-x-2">Created By <span
                                        class="font-bold ml-1"><?= htmlspecialchars($course['instructor_name']) ?></span>
                                </p>
                                <h3 class="text-lg font-semibold text-gray-800 mt-2"><?= htmlspecialchars($course['title']); ?>
                                </h3>
                                <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($course['description']); ?></p>
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-yellow-400 font-bold"><?= htmlspecialchars($course['price']); ?> $</p>
                                    <button class="font-bold underline text-yellow-400"><a
                                            href="./pages/course-preview.php?id=<?php echo $course['course_id']; ?>">View
                                            Course</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-500">No courses available</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section>
        <div class="py-10 md:px-12 px-6">
            <div class="flex flex-wrap justify-center items-center mb-12 text-center">
                <h2 class="text-3xl font-bold text-gray-800">
                    FAQs About <span
                        class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-600">Youdemy
                        Platform</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        What is Youdemy Platform?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Youdemy is a leading online learning platform offering courses in various domains to help
                            learners achieve their goals.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        How can I enroll in a Youdemy course?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            You can enroll by signing up on our platform, browsing courses, and selecting the one that
                            suits
                            your needs.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        Are the courses on Youdemy certified?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Yes, many of our courses provide certificates of completion that you can showcase
                            professionally.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        What is the pricing for Youdemy courses?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Youdemy offers competitive pricing with occasional discounts, making high-quality education
                            affordable for everyone.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        Can I access Youdemy courses offline?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Yes, our mobile app allows you to download course content and access it offline at your
                            convenience.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        What topics are covered on Youdemy?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Topics range from technology and business to arts, health, personal development, and more.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        Does Youdemy offer support for learners?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Yes, we provide 24/7 support for technical issues and a dedicated community for
                            course-related
                            discussions.
                        </p>
                    </div>
                </div>

                <div class="border border-yellow-400 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <button onclick="toggleAnswer(this)"
                        class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800">
                        Can I get a refund for Youdemy courses?
                        <i class="ri-arrow-down-s-line text-yellow-400"></i>
                    </button>
                    <div
                        class="answer hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out mt-2">
                        <p class="text-gray-600">
                            Yes, Youdemy has a refund policy allowing you to request refunds within 7 days of purchase.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <img src="./assets/images/Youdemy_Logo.svg" height="200" width="200">
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