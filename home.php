
<?php 
    include("./includes/header.php"); 
    // include_once 'session.php';
    // include 'dbUtil.php'; 

    // session_start();
    // $email = getSession('email');

    // $user = getUserData($email);

    // if ($user) {
    //     $fullName = trim($user['fullname']); 
    //     $firstName = explode(" ", $fullName)[0];
    //     echo $firstName; 
    //     exit();
    // } else {
    //     echo "User not found.";
    //     exit();
    // }

?>
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
                            <a href="#" class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="class.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Classes</a>
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
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column - Membership Details -->
            <div class="w-full md:w-2/3 space-y-6">
                <!-- Welcome Banner -->
                <div class="bg-gym text-white rounded-xl overflow-hidden shadow-lg">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold mb-2">Welcome back, john</h2>
                        <p class="text-gray-300 mb-6">Track your fitness journey and manage your membership all in one place.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                                Book a Class
                            </a>
                            <a href="#" class="bg-transparent hover:bg-white hover:text-indigo-700 text-white font-semibold py-2 px-6 border border-white rounded-lg transition duration-300">
                                View Progress
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Membership Details Card -->
                <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                    <div class="border-b border-gray-200">
                        <div class="flex justify-between items-center p-6">
                            <h3 class="text-xl font-bold text-gray-800">Membership Details</h3>
                            <button id="edit-membership" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 mb-1">Membership Plan</p>
                                    <p class="text-lg font-semibold text-gray-800">Premium Membership</p>
                                </div>
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 mb-1">Membership ID</p>
                                    <p class="text-lg font-semibold text-gray-800">FH-2023-7856</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                                    <div class="flex items-center">
                                        <i class="fab fa-cc-visa text-blue-600 text-xl mr-2"></i>
                                        <p class="text-lg font-semibold text-gray-800">•••• 5678</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 mb-1">Start Date</p>
                                    <p class="text-lg font-semibold text-gray-800">March 10, 2025</p>
                                </div>
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 mb-1">Expiry Date</p>
                                    <p class="text-lg font-semibold text-gray-800">May 25, 2025</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Auto-Renewal</p>
                                    <div class="flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            <span class="ml-3 text-lg font-semibold text-gray-800">Enabled</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Membership Benefits</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">Unlimited Gym Access</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">4 Free Personal Training Sessions</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">Access to All Group Classes</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">Nutritional Consultation</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">Locker Access</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">Free Parking</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity History -->
                <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                    <div class="border-b border-gray-200">
                        <div class="flex justify-between items-center p-6">
                            <h3 class="text-xl font-bold text-gray-800">Membership Activity</h3>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-credit-card text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Payment Processed</p>
                                    <p class="text-sm text-gray-500">Your monthly payment of $49.99 was processed successfully.</p>
                                    <p class="text-xs text-gray-400 mt-1">May 1, 2025</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-bell text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Membership Renewal Reminder</p>
                                    <p class="text-sm text-gray-500">Your membership will expire in 30 days. Consider renewing to avoid interruption.</p>
                                    <p class="text-xs text-gray-400 mt-1">April 25, 2025</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user-edit text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Profile Updated</p>
                                    <p class="text-sm text-gray-500">You updated your contact information and notification preferences.</p>
                                    <p class="text-xs text-gray-400 mt-1">April 15, 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions and Notifications -->
            <div class="w-full md:w-1/3 space-y-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-2 gap-3">
            <a href="class.php" class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                    <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 text-center">Book Class</span>
            </a>
            <div class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center mb-2">
                    <i class="fas fa-user-plus text-orange-600 text-xl"></i>
                </div>
                <div href="tra.php" class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-50 transition duration-300">
    
                <a href="tra.php" class="text-sm font-medium text-gray-800 text-center">Trainer</a> <!-- Updated topic -->
</div>
            </div>
        </div>
    </div>
</div>

                <!-- Upgrade Membership -->
                <div class="bg-gradient-to-r from-indigo-700 to-purple-700 rounded-xl overflow-hidden shadow-lg text-white">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3">Upgrade Your Experience</h3>
                        <p class="mb-4 text-indigo-100">Enjoy exclusive benefits with our Elite membership plan.</p>
                        <ul class="mb-6 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-indigo-300"></i>
                                <span>8 Personal Training Sessions</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-indigo-300"></i>
                                <span>Priority Class Booking</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-indigo-300"></i>
                                <span>Exclusive Spa Access</span>
                            </li>
                        </ul>
                        <button class="w-full bg-white text-indigo-700 font-bold py-2 px-4 rounded-lg hover:bg-indigo-100 transition duration-300">
                            Upgrade Now
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                    <div class="border-b border-gray-200">
                        <div class="flex justify-between items-center p-6">
                            <h3 class="text-xl font-bold text-gray-800">Notifications</h3>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Mark All Read</a>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-exclamation-circle text-red-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Membership Expiring Soon</p>
                                    <p class="text-xs text-gray-500 mt-1">Your membership expires in 23 days</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-dumbbell text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">New HIIT Class Added</p>
                                    <p class="text-xs text-gray-500 mt-1">Check out our new high-intensity class</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-tag text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">25% Off Personal Training</p>
                                    <p class="text-xs text-gray-500 mt-1">Limited time offer for members</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Membership Registration Modal -->
    <div id="registration-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Register for Membership</h3>
                    <button id="close-registration" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Membership Plan</label>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-500 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Basic Plan</h4>
                                        <p class="text-sm text-gray-500">Gym access only</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">$29.99<span class="text-sm text-gray-500">/month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-2 border-indigo-500 rounded-lg p-4 cursor-pointer bg-indigo-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <h4 class="font-semibold text-gray-900">Premium Plan</h4>
                                            <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded">Popular</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Gym + Classes + Limited PT</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">$49.99<span class="text-sm text-gray-500">/month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-500 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Elite Plan</h4>
                                        <p class="text-sm text-gray-500">All access + unlimited PT</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">$79.99<span class="text-sm text-gray-500">/month</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Membership Duration</label>
                        <select id="duration" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option value="1">1 Month</option>
                            <option value="3">3 Months (5% off)</option>
                            <option value="6">6 Months (10% off)</option>
                            <option value="12">12 Months (15% off)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <input id="card-payment" name="payment-type" type="radio" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="card-payment" class="ml-2 block text-sm text-gray-700">Credit/Debit Card</label>
                                </div>
                                <div class="flex space-x-2">
                                    <i class="fab fa-cc-visa text-blue-600 text-2xl"></i>
                                    <i class="fab fa-cc-mastercard text-red-500 text-2xl"></i>
                                    <i class="fab fa-cc-amex text-blue-800 text-2xl"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="card-number" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                    <input type="text" id="card-number" placeholder="1234 5678 9012 3456" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="expiry" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                        <input type="text" id="expiry" placeholder="MM/YY" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                                    </div>
                                    <div>
                                        <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" id="cvv" placeholder="123" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="auto-renew" type="checkbox" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="auto-renew" class="ml-2 block text-sm text-gray-700">Enable auto-renewal for uninterrupted service</label>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-500">Premium Plan (Monthly)</span>
                            <span class="text-sm font-medium text-gray-900">$49.99</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-500">Registration Fee</span>
                            <span class="text-sm font-medium text-gray-900">$10.00</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="text-base font-medium text-gray-900">Total</span>
                            <span class="text-base font-bold text-gray-900">$59.99</span>
                        </div>
                    </div>
                    
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                        Complete Registration
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Membership Renewal Modal -->
<div id="renewal-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Renew Your Membership</h3>
                <button id="close-renewal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="space-y-4">
                <p class="text-gray-700">Your Premium Membership is set to expire on <span class="font-semibold">May 25, 2025</span>. Renew now to maintain your benefits without interruption.</p>
                
                <div>
                    <label for="renewal-duration" class="block text-sm font-medium text-gray-700 mb-1">Renewal Duration</label>
                    <select id="renewal-duration" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        <option value="1">1 Month ($49.99)</option>
                        <option value="3">3 Months ($142.47 - 5% off)</option>
                        <option value="6">6 Months ($269.94 - 10% off)</option>
                        <option value="12" selected>12 Months ($509.90 - 15% off)</option>
                    </select>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">Premium Plan (12 Months)</span>
                        <span class="text-sm font-medium text-gray-900">$509.90</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">Discount (15%)</span>
                        <span class="text-sm font-medium text-green-600">-$76.49</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="text-base font-medium text-gray-900">Total</span>
                        <span class="text-base font-bold text-gray-900">$433.41</span>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input id="renewal-auto" type="checkbox" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="renewal-auto" class="ml-2 block text-sm text-gray-700">Continue with auto-renewal</label>
                </div>
                
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    Confirm Renewal
                </button>
            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.js"></script>
<script>
    // Modal functionality
    document.getElementById('renew-btn').addEventListener('click', function() {
        document.getElementById('renewal-modal').classList.remove('hidden');
    });
    
    document.getElementById('close-renewal').addEventListener('click', function() {
        document.getElementById('renewal-modal').classList.add('hidden');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const renewalModal = document.getElementById('renewal-modal');
        if (event.target === renewalModal) {
            renewalModal.classList.add('hidden');
        }
    });
</script>
