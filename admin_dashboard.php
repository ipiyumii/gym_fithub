<?php
// Initialize the session
session_start();


// Include database connection
require_once "config.php";

// Get some stats for the dashboard
$total_users = 0;
$total_active_memberships = 0;
$total_classes = 0;
$total_bookings = 0;

// Count total users
$sql = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$result = mysqli_query($conn, $sql);
if($result) {
    $row = mysqli_fetch_assoc($result);
    $total_users = $row['total'];
}

// Count active memberships
$sql = "SELECT COUNT(*) as total FROM user_memberships WHERE end_date >= CURDATE()";
$result = mysqli_query($conn, $sql);
if($result) {
    $row = mysqli_fetch_assoc($result);
    $total_active_memberships = $row['total'];
}

// Count total classes
$sql = "SELECT COUNT(*) as total FROM gym_classes";
$result = mysqli_query($conn, $sql);
if($result) {
    $row = mysqli_fetch_assoc($result);
    $total_classes = $row['total'];
}

// Count total bookings
$sql = "SELECT COUNT(*) as total FROM user_class_bookings WHERE booking_date >= CURDATE()";
$result = mysqli_query($conn, $sql);
if($result) {
    $row = mysqli_fetch_assoc($result);
    $total_bookings = $row['total'];
}

// Get recent bookings
$recent_bookings = [];
$sql = "SELECT ucb.id, u.full_name, gc.name as class_name, cs.day_of_week, cs.start_time, ucb.booking_date, ucb.status
        FROM user_class_bookings ucb
        JOIN users u ON ucb.user_id = u.id
        JOIN class_schedules cs ON ucb.schedule_id = cs.id
        JOIN gym_classes gc ON cs.class_id = gc.id
        ORDER BY ucb.created_at DESC
        LIMIT 5";
$result = mysqli_query($conn, $sql);
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $recent_bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FITHUB Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-2 px-4">
                <i class="fas fa-dumbbell text-blue-400 text-2xl"></i>
                <span class="text-2xl font-extrabold text-blue-400">FITHUB</span>
            </div>
            
            <nav>
                <a href="admin_dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-800 text-white">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="admin_classes.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-dumbbell mr-2"></i>Classes
                </a>
                <a href="admin_schedules.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-calendar-alt mr-2"></i>Schedules
                </a>
                <a href="admin_trainers.php" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-800 text-white">
                    <i class="fas fa-user-tie mr-2"></i>Trainers
                </a>
                
                
            </nav>
            
            <div class="px-4 mt-auto">
                <div class="border-t border-gray-700 pt-4 mt-4">
                    <a href="admin_logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-3">
                    <button class="md:hidden focus:outline-none">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="focus:outline-none">
                                <i class="fas fa-bell text-gray-600 text-lg"></i>
                                <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                A
                            </div>
                            <span class="text-gray-700"></span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-6">Admin Dashboard</h1>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Classes</p>
                                <h3 class="text-3xl font-bold text-gray-900"><?php echo $total_classes; ?></h3>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-dumbbell text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                
                
                
                <!-- Quick Actions and System Status -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                        </div>
                        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                            <a href="admin_add_class.php" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Add Class</span>
                            </a>
                            <a href="admin_add_schedule.php" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <div class="h-12 w-12 bg-green-500 rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Add Schedule</span>
                            </a>
                            <a href="admin_add_trainer.php" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                <div class="h-12 w-12 bg-purple-500 rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Add Trainer</span>
                            </a>
                            
                            
                            </a>
                        </div>
                    </div>
                    
                   
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('button.md\\:hidden').addEventListener('click', function() {
            const sidebar = document.querySelector('.bg-gray-900');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>