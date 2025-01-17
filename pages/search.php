<?php 
require_once '../classes/course.php';
require_once '../classes/user.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'default') : 'default';
$menuItems = User::getMenuItems($userRole);

$db = new Database();
$searchQuery = $_GET['query'] ?? '';
$course = new Course();
$searchResults = $course->search($searchQuery);
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
    <!-- Main container -->
    <div class="min-h-screen flex flex-col">
        <!-- Top bar -->
        <div class="hidden md:block w-full bg-[#f2b212] text-white">
            <div class="container mx-auto px-4 py-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-6">
                        <span><i class="ri-phone-line mr-2"></i> +212 772508881</span>
                        <span><i class="ri-mail-line mr-2"></i> contact@youdemy.com</span>
                    </div>
                    <span><i class="ri-map-pin-line mr-2"></i> Massira N641 Safi, Morocco</span>
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
                            <a href="<?= htmlspecialchars($item[1]) ?>" class="text-gray-900 hover:text-yellow-500 transition-colors">
                                <?= htmlspecialchars($item[0]) ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <?php if (!$isLoggedIn): ?>
                            <a href="./login.php" class="p-2 px-4 bg-yellow-400 text-white rounded-full hover:bg-white hover:text-yellow-400 hover:border hover:border-yellow-400 transition-colors">Login</a>
                            <a href="./register.php" class="p-2 px-4 border border-yellow-400 text-yellow-400 rounded-full hover:bg-yellow-400 hover:text-white transition-colors">Register</a>
                        <?php else: ?>
                            <a href="./logout.php" class="p-2 px-4 bg-red-400 text-white rounded-full hover:bg-white hover:text-red-400 hover:border hover:border-red-400 transition-colors">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
                <!-- Hero Section -->
                <section
            class="hero bg-bg-yellow-500/5 flex-grow flex items-center bg-opacity-20 bg-[url('../assets/images/hero-bg1.png')]  bg-cover bg-center">
            <div class="container mx-auto flex flex-col items-center py-12 px-6 md:px-12">
                <div class="text-center space-y-6">
                    <h1 class="text-4xl md:text-4xl font-bold">
                    Find Your Course
                    </h1>
                </div>
                <!-- Search Form -->
                <div class="mt-8 w-[40%]">
                        <form action="./search.php" method="GET" class="relative">
                            <input type="text" name="query" placeholder="What Do You Need To Learn?"
                                class="w-full p-3 pl-4 rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                            <button type="submit"
                                class="bg-yellow-400 absolute right-1 top-1 bottom-1 px-4 text-white rounded-full hover:bg-yellow-500">
                                Search
                            </button>
                        </form>
                    </div>
            </div>
        </section>

        <!-- Courses Grid Section -->
        <section class="py-10">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl text-center text-gray-800 mb-10">Search result for : <span class="text-gradient"><?= htmlspecialchars( $_GET['query']); ?></span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (!empty($searchResults)): ?>
                        <?php foreach ($searchResults as $result): ?>
                            <div
                            class="bg-white border border-yellow-400 rounded-lg shadow-md p-4 hover:scale-105 transition-transform">
                            <img src="../uploads/thumbnails/<?= htmlspecialchars($result['thumbnail']); ?>" alt="Course Image"
                                class="rounded-t-lg w-full">
                            <div class="py-3">
                                <p class="text-sm text-gray-500 flex items-center space-x-2">Created By <span
                                        class="font-bold ml-1"><?= htmlspecialchars($result['instructor_name']) ?></span>
                                </p>
                                <h3 class="text-lg font-semibold text-gray-800 mt-2"><?= htmlspecialchars($result['title']); ?>
                                </h3>
                                <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($result['description']); ?></p>
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-yellow-400 font-bold"><?= htmlspecialchars($result['price']); ?> $</p>
                                    <button class="font-bold underline text-yellow-400"><a
                                            href="./course-preview.php?id=<?php echo $result['course_id']; ?>">View
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
    </div>
</body>

</html>
