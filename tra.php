<?php include("./includes/header.php"); ?>
    <style>
        .bg-trainer {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/api/placeholder/1600/900');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white shadow-lg ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-dumbbell text-indigo-500 text-2xl mr-2"></i>
                        <a href="gym1.php" class="font-bold text-xl">FitHub</a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="home.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="class.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Classes</a>
                            <a href="#" class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium">Trainers</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <button type="button" class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-3 right-14 md:right-28 inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-xs font-bold text-white">3</span>
                    </button>
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="ml-3 relative">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">JD</span>
                                </div>
                                <span class="ml-2 text-sm text-gray-300">John Doe</span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 md:hidden">
                        <button type="button" class="text-gray-400 hover:text-white focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Membership Status Banner -->
    <div class="bg-indigo-600 text-white p-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-2 md:mb-0">
                <i class="fas fa-star-half-alt text-yellow-300 mr-2"></i>
                <span>Your <span class="font-semibold">Premium Membership</span> expires in <span class="font-bold">23 days</span></span>
            </div>
            <button id="renew-btn" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-1 px-4 rounded-lg transition duration-300">
                Renew Now
            </button>
        </div>
    </div>

    <?php include("./new2.php"); ?>

    <!-- Trainer Detail Modal -->
    <div id="trainer-modal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 my-8">
            <div id="trainer-modal-content" class="relative">
                <!-- Modal content will be loaded dynamically -->
                <div class="bg-trainer rounded-t-xl p-12 text-white relative">
                    <button id="close-modal" class="absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="h-32 w-32 rounded-full bg-white p-1 mb-6 md:mb-0 md:mr-8">
                            <img src="/api/placeholder/300/300" alt="Trainer" class="h-full w-full rounded-full">
                        </div>
                        <div>
                            <h2 id="modal-trainer-name" class="text-3xl font-bold mb-2">Mike Thompson</h2>
                            <p id="modal-trainer-specialty" class="text-xl text-indigo-200 mb-4">HIIT & CrossFit Specialist</p>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span id="modal-trainer-reviews" class="text-white">(48 reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">HIIT</span>
                        <span class="bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full">CrossFit</span>
                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">Strength</span>
                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">Weight Loss</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">About</h3>
                            <p class="text-gray-600 mb-4">
                                Certified CrossFit coach with 8+ years of experience helping clients achieve their fitness goals through high-intensity training. Specializing in functional movement, weight loss, and athletic performance.
                            </p>
                            <p class="text-gray-600">
                                My approach combines challenging workouts with proper form and technique to ensure safe and effective progress. I believe in pushing boundaries while adapting workouts to each client's individual needs and goals.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Credentials</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>CrossFit Level 3 Trainer</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>NASM Certified Personal Trainer</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Precision Nutrition Level 1 Coach</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>First Aid & CPR Certified</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Bachelor's in Exercise Science</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-indigo-600 mb-1">$85</div>
                            <div class="text-gray-500 text-sm">Per session (60 min)</div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-indigo-600 mb-1">$750</div>
                            <div class="text-gray-500 text-sm">Package (10 sessions)</div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-indigo-600 mb-1">85%</div>
                            <div class="text-gray-500 text-sm">Client success rate</div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4 mt-8">
                        <button class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition duration-300">
                            Book a Session
                        </button>
                        <button class="flex-1 border border-indigo-600 text-indigo-600 py-3 rounded-lg font-bold hover:bg-indigo-50 transition duration-300">
                            Send Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.js"></script>
    <script>
        // Modal functionality
        function openTrainerDetails(trainerId) {
            document.getElementById('trainer-modal').classList.remove('hidden');
            // In a real application, you would fetch trainer details based on ID
            // For this example, we're just showing the modal with placeholder content
        }

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('trainer-modal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('trainer-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Renew membership button
        document.getElementById('renew-btn').addEventListener('click', function() {
            alert('Taking you to membership renewal page...');
        });
    </script>
