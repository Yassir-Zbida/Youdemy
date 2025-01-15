<?php
require_once '../classes/user.php';
require_once '../classes/student.php';
require_once '../classes/instructor.php';
User::redirectIfLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];

    $db = new Database();

    try {
        if ($role === 'Student') {
            $student = new Student($db);
            $isRegistered = $student->register($username, $email, $password);
        } elseif ($role === 'Instructor') {
            $instructor = new Instructor($db);
            $isRegistered = $instructor->register($username, $email, $password);
        } else {
            throw new Exception("Invalid role selected");
        }

        if ($isRegistered === true) {
            header('Location: ./login.php');
            exit;
        } else {
            $error = $isRegistered;
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Youdemy Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="../assets/scripts/register.js" defer></script>
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
</head>


<body>
    <style>
        .selected {
            border-color: #fbbf24;
            color: #fbbf24;
        }
    </style>

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
                    <a href="../index.php">
                        <img src="../assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
                    </a>
                    <nav class="hidden md:flex items-center space-x-6">
                        <a href="../index.php" class="text-gray-900 hover:text-yellow-500 transition-colors">Home</a>
                        <a href="./courses.php"
                            class="text-gray-900 hover:text-yellow-500 transition-colors">Courses</a>
                        <a href="./pricing.php"
                            class="text-gray-900 hover:text-yellow-500 transition-colors">Pricing</a>
                        <a href="./features.php"
                            class="text-gray-900 hover:text-yellow-500 transition-colors">Features</a>
                        <a href="./blog.php" class="text-gray-900 hover:text-yellow-500 transition-colors">Blog</a>
                        <a href="./contact.php" class="text-gray-900 hover:text-yellow-500 transition-colors">Help
                            Center</a>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <button
                            class="p-2 hidden md:block px-4 bg-yellow-400 text-white rounded-full hover:bg-white hover:text-yellow-400 hover:border hover:border-yellow-400 transition-colors">
                            <a href="./login.php">Login</a>
                        </button>
                        <button
                            class="p-2 hidden md:block px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">
                            <a href="./register.php">Register</a>
                        </button>
                        <button id="mobile-menu-btn" class="p-2 hover:text-yellow-500 transition-colors md:hidden">
                            <i class="ri-menu-4-fill text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu Mobile -->
            <div id="sidebar-menu" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 hidden">
                <div class="fixed top-0 left-0 w-64 bg-white pt-2 h-full shadow-lg">
                    <div class="flex justify-end items-center px-4">
                        <button id="close-sidebar" class="text-gray-700 hover:text-yellow-500">
                            <i class="ri-close-line text-2xl"></i>
                        </button>
                    </div>
                    <nav class="flex flex-col space-y-4 px-4 py-6">
                        <a href="./index.php"
                            class="text-gray-700 hover:text-yellow-500 font-bold transition-colors">Home</a>
                        <a href="./courses.php"
                            class="text-gray-700 hover:text-yellow-500 transition-colors">Courses</a>
                        <a href="./pricing.php"
                            class="text-gray-700 hover:text-yellow-500 transition-colors">Pricing</a>
                        <a href="./features.php"
                            class="text-gray-700 hover:text-yellow-500 transition-colors">Features</a>
                        <a href="./blog.php" class="text-gray-700 hover:text-yellow-500 transition-colors">Blog</a>
                        <a href="./contact.php" class="text-gray-700 hover:text-yellow-500 transition-colors">Help
                            Center</a>
                        <div class="flex flex-col space-y-4 mt-6">
                            <button
                                class="p-2 px-4 bg-yellow-400 text-white rounded-full hover:bg-white hover:text-yellow-400 hover:border hover:border-yellow-400 transition-colors">
                                <a href="./login.php">Login</a>
                            </button>
                            <button
                                class="p-2 px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">
                                <a href="./register.php">Register</a>
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Register Form -->
        <section
            class="hero bg-bg-yellow-500/5 flex-grow flex justify-center items-center border-yellow-400 bg-opacity-20 bg-[url('../assets/images/hero-bg1.png')]  bg-cover bg-center">
            <div class="bg-white/10 backdrop-blur-lg rounded-lg p-8 md:shadow-lg w-full max-w-md">
                <h2 class="text-yellow-400 text-center text-3xl font-semibold mb-6">Register</h2>
                <form method="post" id="registerForm" enctype="multipart/form-data">


                    <div class="relative mb-4">
                        <i class="ri-user-line text-gray-300 absolute left-4 top-2.5 text-xl"></i>
                        <input type="text" placeholder="Username" name="username" required
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg bg-white/10 text-gray-600 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" />
                    </div>

                    <div class="relative mb-4">
                        <i class="ri-mail-line text-gray-300 absolute left-4 top-2.5 text-xl"></i>
                        <input type="email" placeholder="Email" name="email" required
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg bg-white/10 text-gray-600 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-border-yellow-400" />
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-6 w-full">
                        <label class="block text-gray-500 font-semibold mb-2">Choose Your Role:</label>
                        <div class="flex justify-center space-x-4 w-[100%]">
                            <label
                                class="role-option flex items-center justify-center w-[50%] border border-gray-300 rounded-lg cursor-pointer text-gray-300 bg-transparent hover:border-yellow-400 hover:text-yellow-400 focus:ring focus:ring-yellow-400 transition">
                                <input type="radio" name="role" value="Student" class="hidden radio-input" />
                                <span class="font-medium">Student</span>
                            </label>
                            <label
                                class="role-option flex items-center justify-center w-[50%] py-3 border border-gray-300 rounded-lg cursor-pointer text-gray-300 bg-transparent hover:border-yellow-400 hover:text-yellow-400 focus:ring focus:ring-yellow-400 transition">
                                <input type="radio" name="role" value="Instructor" class="hidden radio-input" />
                                <span class="font-medium">Instructor</span>
                            </label>
                        </div>
                    </div>

                    <div class="relative mb-4">
                        <i class="ri-lock-line text-gray-300 absolute left-4 top-2.5 text-xl"></i>
                        <input type="password" placeholder="Password" name="password" required id="password"
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg bg-white/10 text-gray-600 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" />
                    </div>

                    <div class="relative mb-6">
                        <i class="ri-lock-line text-gray-300 absolute left-4 top-2.5 text-xl"></i>
                        <input type="password" placeholder="Confirm Password" name="confirm_password"
                            id="confirm_password" required
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg bg-white/10 text-gray-600 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" />
                    </div>

                    <button type="submit" name="submit"
                        class="w-full py-2 bg-yellow-400 hover:bg-black text-white font-semibold rounded-lg transition duration-200 hover:bg-white hover:border hover:border-yellow-400 hover:text-yellow-400 hover:text-black">
                        Register
                    </button>

                    <div class="errorsContainer">
                        <?php if (!empty($error)): ?>
                            <p class="text-red-600 text-center mt-4"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                    </div>

                </form>

                <p class="text-center text-gray-600 mt-4">
                    Already have an account?
                    <a href="./login.php" class="text-black hover:underline text-black">Login</a>
                </p>
            </div>
        </section>
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