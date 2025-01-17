
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course View - Social Media Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto">
            <!-- Top bar -->
            <div class="bg-yellow-500 text-white px-4 py-2 text-sm">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="ri-phone-line mr-2"></i>
                            <span>+212 772508881</span>
                        </div>
                        <div class="flex items-center">
                            <i class="ri-mail-line mr-2"></i>
                            <span>contact@youdemy.com</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="ri-map-pin-line mr-2"></i>
                        <span>Massira N641 Safi, Morocco</span>
                    </div>
                </div>
            </div>

            <!-- Main navigation -->
            <nav class="px-4 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-UZbEFA1P35IbhX8Olq3HJ7xPyMiGk1.png" alt="Youdemy Logo" class="h-10">
                    </div>
                    <div class="flex items-center space-x-8">
                        <a href="#" class="text-gray-700 hover:text-yellow-500">Home</a>
                        <a href="#" class="text-gray-700 hover:text-yellow-500">Courses</a>
                        <a href="#" class="text-gray-700 hover:text-yellow-500">My Courses</a>
                        <a href="#" class="text-gray-700 hover:text-yellow-500">Help Center</a>
                        <button class="bg-red-400 text-white px-4 py-2 rounded-md hover:bg-red-500">
                            Logout
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class=" mx-auto px-4 py-2">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="../index.php" class="hover:text-yellow-500">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="./mycourses.php" class="hover:text-yellow-500">My Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-gray-900">Social Media Marketing</span>
        </div>
    </div>

    <main class=" mx-auto px-4 py-8">
        <div class="space-y-8">
            <div class="space-y-4">
                <h1 class="text-3xl font-bold">Social Media Marketing</h1>
            </div>

            <!-- Course Content -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Tabs -->
                <div class="flex border-b">
                    <button class="flex-1 py-4 px-6 bg-gray-100 text-gray-700 font-medium border-b-2 border-yellow-500">
                        <i class="ri-play-circle-line mr-2"></i>
                        Video Lecture
                    </button>
                    <button class="flex-1 py-4 px-6 text-gray-500 hover:text-gray-700">
                        <i class="ri-file-text-line mr-2"></i>
                        Course PDF
                    </button>
                </div>

                <!-- Video Section -->
                <div class="p-6 space-y-6">
                    <div class="relative aspect-video bg-black rounded-lg overflow-hidden">
                        <img src="/placeholder.svg?height=720&width=1280" alt="Video Placeholder" class="w-full h-full object-cover">
                        <!-- Custom Video Controls -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                            <div class="flex flex-col gap-2">
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 33%"></div>
                                </div>
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex items-center gap-2">
                                        <button class="p-1 hover:bg-white/20 rounded">
                                            <i class="ri-skip-back-fill"></i>
                                        </button>
                                        <button class="p-1 hover:bg-white/20 rounded">
                                            <i class="ri-play-fill"></i>
                                        </button>
                                        <button class="p-1 hover:bg-white/20 rounded">
                                            <i class="ri-skip-forward-fill"></i>
                                        </button>
                                        <span class="text-sm">12:34 / 45:00</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="p-1 hover:bg-white/20 rounded">
                                            <i class="ri-volume-up-fill"></i>
                                        </button>
                                        <button class="p-1 hover:bg-white/20 rounded">
                                            <i class="ri-fullscreen-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Understanding Social Media Platforms</h2>
                        <p class="text-gray-600">Learn about different social media platforms and their unique characteristics for marketing.</p>
                    </div>
                </div>
            </div>

            <!-- Course Navigation -->
            <div class="flex justify-between">
                <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="ri-arrow-left-s-line"></i>
                    Previous Lesson
                </button>
                <button class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                    Next Lesson
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>

            <!-- Course Sections -->
            <div class="grid grid-cols-3 gap-8">
                <div class="col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="space-y-4">
                            <div class="border rounded-lg">
                                <div class="p-4 border-b bg-gray-50">
                                    <h3 class="font-semibold">Section 1: Introduction</h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="ri-play-circle-line text-xl text-yellow-500"></i>
                                        <span>Welcome to the Course</span>
                                        <span class="ml-auto text-green-500">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="ri-play-circle-line text-xl text-yellow-500"></i>
                                        <span>Course Overview</span>
                                        <span class="ml-auto text-gray-400">12:00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg">
                                <div class="p-4 border-b bg-gray-50">
                                    <h3 class="font-semibold">Section 2: Strategy Development</h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="ri-play-circle-line text-xl text-yellow-500"></i>
                                        <span>Understanding Your Audience</span>
                                        <span class="ml-auto text-gray-400">15:30</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="ri-file-text-line text-xl text-blue-500"></i>
                                        <span>Strategy Worksheet</span>
                                        <span class="ml-auto">
                                            <i class="ri-download-line text-gray-500"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <div>
                            <h3 class="font-semibold mb-2">Course Info</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <i class="ri-folder-line w-5 text-gray-500"></i>
                                    <span class="text-gray-600">Category:</span>
                                    <span class="ml-auto">Web Development</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-bar-chart-line w-5 text-gray-500"></i>
                                    <span class="text-gray-600">Level:</span>
                                    <span class="ml-auto">Intermediate</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-time-line w-5 text-gray-500"></i>
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="ml-auto">6 hours</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold mb-2">Resources</h3>
                            <div class="space-y-2">
                                <button class="w-full flex items-center justify-center space-x-2 bg-blue-50 text-blue-600 p-2 rounded hover:bg-blue-100">
                                    <i class="ri-download-cloud-line"></i>
                                    <span>Course Materials</span>
                                </button>
                                <button class="w-full flex items-center justify-center space-x-2 bg-green-50 text-green-600 p-2 rounded hover:bg-green-100">
                                    <i class="ri-chat-1-line"></i>
                                    <span>Discussion Forum</span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold mb-2">Instructor</h3>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="ri-user-line text-gray-500 text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-medium">John Smith</div>
                                    <div class="text-sm text-gray-500">Digital Marketing Expert</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>