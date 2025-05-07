<?php include("./includes/header.php"); ?>
    <style>
        .bg-gym {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/api/placeholder/1600/900');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white shadow-lg">
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
                            <a href="class.php" class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium">Classes</a>
                            <a href="tra.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Trainers</a>
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Class Booking Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Fitness Classes</h1>
            <p class="text-gray-600">Book your spot in our exciting fitness classes led by expert trainers.</p>
        </div>

        <!-- Class Filter and Search -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="class-type" class="block text-sm font-medium text-gray-700 mb-1">Class Type</label>
                    <select id="class-type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        <option value="">All Classes</option>
                        <option value="cardio">Cardio</option>
                        <option value="strength">Strength Training</option>
                        <option value="yoga">Yoga</option>
                        <option value="hiit">HIIT</option>
                        <option value="pilates">Pilates</option>
                        <option value="dance">Dance</option>
                    </select>
                </div>
                <div>
                    <label for="class-day" class="block text-sm font-medium text-gray-700 mb-1">Day of Week</label>
                    <select id="class-day" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        <option value="">All Days</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="saturday">Saturday</option>
                        <option value="sunday">Sunday</option>
                    </select>
                </div>
                <div>
                    <label for="class-search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="class-search" class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" placeholder="Search classes...">
                    </div>
                </div>
            </div>
        </div>
        <?php include("./new.php"); ?>
        <div class="flex flex-col md:flex-row gap-6">
            
            <!-- Left Column - Calendar View -->
            <div class="w-full md:w-2/3">
                

                <!-- Upcoming Classes -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Your Upcoming Classes</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-bolt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">HIIT Express</h3>
                                        <p class="text-sm text-gray-500">Thursday, May 5 • 7:00 AM • Mike T.</p>
                                    </div>
                                </div>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium focus:outline-none">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-bicycle text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Spin Cycle</h3>
                                        <p class="text-sm text-gray-500">Friday, May 6 • 9:00 AM • Alex P.</p>
                                    </div>
                                </div>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium focus:outline-none">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-dumbbell text-green-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Weekend Warrior</h3>
                                        <p class="text-sm text-gray-500">Saturday, May 7 • 8:00 AM • James R.</p>
                                    </div>
                                </div>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium focus:outline-none">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Featured Classes and Reminders -->
            <div class="w-full md:w-1/3 space-y-6">
                
                
                <!-- Class Stats Widget -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">Your Fitness Stats</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">12</div>
                                <div class="text-sm text-gray-500">Classes This Month</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">87%</div>
                                <div class="text-sm text-gray-500">Attendance Rate</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">8</div>
                                <div class="text-sm text-gray-500">Different Class Types</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">45</div>
                                <div class="text-sm text-gray-500">Total Classes</div>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: 75%"></div>
                        </div>
                        <div class="text-center mt-2 text-sm text-gray-500">
                            75% towards your monthly goal
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>

    <!-- Class Details Modal -->
    <div id="class-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gym bg-gray-900 text-white p-8 rounded-t-xl">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span id="class-tag" class="inline-block bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded mb-2">HIIT</span>
                        <h2 id="class-title" class="text-2xl font-bold">HIIT Express</h2>
                    </div>
                    <button onclick="closeClassDetails()" class="text-gray-300 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex items-center mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span id="class-date">Tuesday & Thursday, 7:00 AM</span>
                </div>
                <div class="flex items-center mb-2">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="class-duration">45 minutes</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-user-circle mr-2"></i>
                    <span id="class-instructor">Mike Thompson</span>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">About this class</h3>
                    <p id="class-description" class="text-gray-600">
                        A high-intensity interval training class designed to maximize calorie burn and improve cardiovascular fitness in just 45 minutes. Perfect for busy professionals looking for an efficient workout.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <div class="font-medium text-gray-900">Intensity</div>
                        <div id="class-intensity" class="text-indigo-600">High</div>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <div class="font-medium text-gray-900">Capacity</div>
                        <div id="class-capacity" class="text-indigo-600">12 spots left</div>
                    </div>
                </div>
                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">What to bring</h3>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>Water bottle</li>
                        <li>Towel</li>
                        <li>Athletic shoes</li>
                        <li>Comfortable workout clothes</li>
                    </ul>
                </div>
                <button id="book-class-btn" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
                    Book This Class
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6 text-center">
            <div class="h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Booked Successfully!</h2>
            <p class="text-gray-600 mb-6">You've successfully booked a spot in HIIT Express.</p>
            <div class="flex space-x-4">
                <button onclick="closeSuccessModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded font-medium hover:bg-gray-300 transition">
                    Close
                </button>
                <button onclick="viewBookings()" class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded font-medium hover:bg-indigo-700 transition">
                    View My Bookings
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.js"></script>
    <script>
        function openClassDetails(classId) {
            // In a real app, we would fetch class details based on the ID
            // For now, just displaying the modal with sample data
            document.getElementById('class-modal').classList.remove('hidden');
            
            // Update class details based on the classId
            if (classId === 'yoga-flow') {
                document.getElementById('class-title').textContent = 'Yoga Flow';
                document.getElementById('class-tag').textContent = 'YOGA';
                document.getElementById('class-tag').className = 'inline-block bg-red-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Sarah Johnson';
                document.getElementById('class-date').textContent = 'Monday, Wednesday & Friday, 6:00 AM';
                document.getElementById('class-duration').textContent = '60 minutes';
                document.getElementById('class-description').textContent = 'A flowing sequence of yoga poses linked with breath to build strength, flexibility, and balance. Suitable for all levels.';
                document.getElementById('class-intensity').textContent = 'Medium';
                document.getElementById('class-capacity').textContent = '8 spots left';
            } else if (classId === 'hiit-express') {
                document.getElementById('class-title').textContent = 'HIIT Express';
                document.getElementById('class-tag').textContent = 'HIIT';
                document.getElementById('class-tag').className = 'inline-block bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Mike Thompson';
                document.getElementById('class-date').textContent = 'Tuesday & Thursday, 7:00 AM';
                document.getElementById('class-duration').textContent = '45 minutes';
                document.getElementById('class-description').textContent = 'A high-intensity interval training class designed to maximize calorie burn and improve cardiovascular fitness in just 45 minutes. Perfect for busy professionals looking for an efficient workout.';
                document.getElementById('class-intensity').textContent = 'High';
                document.getElementById('class-capacity').textContent = '12 spots left';
            } else if (classId === 'pilates') {
                document.getElementById('class-title').textContent = 'Pilates';
                document.getElementById('class-tag').textContent = 'PILATES';
                document.getElementById('class-tag').className = 'inline-block bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Emma Lee';
                document.getElementById('class-date').textContent = 'Monday, Wednesday & Friday, 8:00 AM';
                document.getElementById('class-duration').textContent = '50 minutes';
                document.getElementById('class-description').textContent = 'Focus on developing core strength, flexibility, balance, and stamina through controlled movements. This class is perfect for improving posture and reducing stress.';
                document.getElementById('class-intensity').textContent = 'Medium';
                document.getElementById('class-capacity').textContent = '10 spots left';
            } else if (classId === 'spin-cycle') {
                document.getElementById('class-title').textContent = 'Spin Cycle';
                document.getElementById('class-tag').textContent = 'CARDIO';
                document.getElementById('class-tag').className = 'inline-block bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Alex Peterson';
                document.getElementById('class-date').textContent = 'Monday, Wednesday & Friday, 9:00 AM';
                document.getElementById('class-duration').textContent = '45 minutes';
                document.getElementById('class-description').textContent = 'High-energy indoor cycling class set to motivating music. Burn calories and increase cardiovascular endurance with interval training, climbs, and sprints.';
                document.getElementById('class-intensity').textContent = 'High';
                document.getElementById('class-capacity').textContent = '6 spots left';
            } else if (classId === 'body-pump') {
                document.getElementById('class-title').textContent = 'Body Pump';
                document.getElementById('class-tag').textContent = 'STRENGTH';
                document.getElementById('class-tag').className = 'inline-block bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Ryan King';
                document.getElementById('class-date').textContent = 'Tuesday & Thursday, 9:00 AM';
                document.getElementById('class-duration').textContent = '50 minutes';
                document.getElementById('class-description').textContent = 'A total body workout using light to moderate weights with lots of repetition. This class is scientifically proven to get you lean, toned and fit.';
                document.getElementById('class-intensity').textContent = 'Medium-High';
                document.getElementById('class-capacity').textContent = '15 spots left';
            } else if (classId === 'zumba') {
                document.getElementById('class-title').textContent = 'Zumba';
                document.getElementById('class-tag').textContent = 'DANCE';
                document.getElementById('class-tag').className = 'inline-block bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Maria Garcia';
                document.getElementById('class-date').textContent = 'Tuesday, Thursday & Saturday, 9:00 AM';
                document.getElementById('class-duration').textContent = '60 minutes';
                document.getElementById('class-description').textContent = 'A dance fitness class that combines Latin and international music with dance moves. Zumba uses interval training alternating fast and slow rhythms for a fun cardio workout.';
                document.getElementById('class-intensity').textContent = 'Medium';
                document.getElementById('class-capacity').textContent = '18 spots left';
            } else if (classId === 'weekend-warrior') {
                document.getElementById('class-title').textContent = 'Weekend Warrior';
                document.getElementById('class-tag').textContent = 'MIXED';
                document.getElementById('class-tag').className = 'inline-block bg-green-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'James Rodriguez';
                document.getElementById('class-date').textContent = 'Saturday & Sunday, 7:00 AM & 8:00 AM';
                document.getElementById('class-duration').textContent = '75 minutes';
                document.getElementById('class-description').textContent = 'A comprehensive weekend workout that combines strength, cardio, and flexibility training. Perfect for those who want to maximize their weekend workouts.';
                document.getElementById('class-intensity').textContent = 'High';
                document.getElementById('class-capacity').textContent = '10 spots left';
            } else if (classId === 'crossfit') {
                document.getElementById('class-title').textContent = 'CrossFit';
                document.getElementById('class-tag').textContent = 'CROSSFIT';
                document.getElementById('class-tag').className = 'inline-block bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'David Miller';
                document.getElementById('class-date').textContent = 'Monday, Wednesday & Friday, 5:00 PM';
                document.getElementById('class-duration').textContent = '60 minutes';
                document.getElementById('class-description').textContent = 'High-intensity functional movements constantly varied across broad time and modal domains. Includes weightlifting, gymnastics, and metabolic conditioning.';
                document.getElementById('class-intensity').textContent = 'Very High';
                document.getElementById('class-capacity').textContent = '8 spots left';
            } else if (classId === 'strength-conditioning') {
                document.getElementById('class-title').textContent = 'Strength & Conditioning';
                document.getElementById('class-tag').textContent = 'STRENGTH';
                document.getElementById('class-tag').className = 'inline-block bg-green-500 text-white text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Chris Brown';
                document.getElementById('class-date').textContent = 'Monday, Wednesday & Friday, 6:00 PM';
                document.getElementById('class-duration').textContent = '60 minutes';
                document.getElementById('class-description').textContent = 'Build strength and endurance through progressive resistance training and conditioning exercises. Focus on proper form and technique for safe and effective results.';
                document.getElementById('class-intensity').textContent = 'Medium-High';
                document.getElementById('class-capacity').textContent = '14 spots left';
            } else if (classId === 'hiit-fusion') {
                document.getElementById('class-title').textContent = 'HIIT Fusion';
                document.getElementById('class-tag').textContent = 'NEW CLASS';
                document.getElementById('class-tag').className = 'inline-block bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded mb-2';
                document.getElementById('class-instructor').textContent = 'Mike Thompson';
                document.getElementById('class-date').textContent = 'Tuesday & Thursday, 5:30 PM';
                document.getElementById('class-duration').textContent = '45 minutes';
                document.getElementById('class-description').textContent = 'A high-intensity interval training class that combines cardio and strength exercises for maximum calorie burn. This new fusion class incorporates elements of kickboxing, strength training, and core work.';
                document.getElementById('class-intensity').textContent = 'Very High';
                document.getElementById('class-capacity').textContent = '20 spots left';
            }
        }
        
        function closeClassDetails() {
            document.getElementById('class-modal').classList.add('hidden');
        }
        
        // Book class functionality
        document.getElementById('book-class-btn').addEventListener('click', function() {
            document.getElementById('class-modal').classList.add('hidden');
            document.getElementById('success-modal').classList.remove('hidden');
        });
        
        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }
        
        function viewBookings() {
            document.getElementById('success-modal').classList.add('hidden');
            // In a real app, we would navigate to bookings page
            // For now, just scroll to the upcoming classes section
            document.querySelector('h2.text-xl.font-bold:contains("Your Upcoming Classes")').scrollIntoView({
                behavior: 'smooth'
            });
        }
        
        // Renewal button handler
        document.getElementById('renew-btn').addEventListener('click', function() {
            alert('Redirecting to membership renewal page...');
        });
    </script>
