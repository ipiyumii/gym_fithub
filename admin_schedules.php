<?php
// Initialize the session
session_start();



// Include database connection
require_once "config.php";

// Process schedule operations
$success_message = $error_message = "";
$class_filter = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

// Delete schedule
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $schedule_id = $_GET['delete'];
    
    // Check if schedule exists
    $check_sql = "SELECT id FROM class_schedules WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $schedule_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if(mysqli_stmt_num_rows($check_stmt) > 0) {
        // Delete schedule
        $delete_sql = "DELETE FROM class_schedules WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $schedule_id);
        
        if(mysqli_stmt_execute($delete_stmt)) {
            $success_message = "Schedule deleted successfully.";
        } else {
            $error_message = "Error deleting schedule. Please try again.";
        }
        
        mysqli_stmt_close($delete_stmt);
    } else {
        $error_message = "Schedule not found.";
    }
    
    mysqli_stmt_close($check_stmt);
}

// Toggle schedule status
if(isset($_GET['toggle_status']) && !empty($_GET['toggle_status'])) {
    $schedule_id = $_GET['toggle_status'];
    
    // Get current status
    $status_sql = "SELECT status FROM class_schedules WHERE id = ?";
    $status_stmt = mysqli_prepare($conn, $status_sql);
    mysqli_stmt_bind_param($status_stmt, "i", $schedule_id);
    mysqli_stmt_execute($status_stmt);
    mysqli_stmt_bind_result($status_stmt, $current_status);
    mysqli_stmt_fetch($status_stmt);
    mysqli_stmt_close($status_stmt);
    
    // Toggle status
    $new_status = ($current_status == "active") ? "inactive" : "active";
    
    $update_sql = "UPDATE class_schedules SET status = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "si", $new_status, $schedule_id);
    
    if(mysqli_stmt_execute($update_stmt)) {
        $success_message = "Schedule status updated successfully.";
    } else {
        $error_message = "Error updating schedule status. Please try again.";
    }
    
    mysqli_stmt_close($update_stmt);
}

// Get all classes for filter dropdown
$classes = [];
$classes_sql = "SELECT id, name FROM gym_classes ORDER BY name";
$classes_result = mysqli_query($conn, $classes_sql);
if($classes_result) {
    while($class_row = mysqli_fetch_assoc($classes_result)) {
        $classes[] = $class_row;
    }
}

// Get schedules with class info and booking counts
$schedules = [];
$sql = "SELECT cs.*, gc.name as class_name, gc.instructor, 
               (SELECT COUNT(*) FROM user_class_bookings WHERE schedule_id = cs.id AND booking_date >= CURDATE()) as upcoming_bookings
        FROM class_schedules cs
        JOIN gym_classes gc ON cs.class_id = gc.id";

// Add class filter if specified
if($class_filter > 0) {
    $sql .= " WHERE cs.class_id = " . $class_filter;
}

$sql .= " ORDER BY FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), cs.start_time";
$result = mysqli_query($conn, $sql);

if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $schedules[] = $row;
    }
}

// Get filtered class name if filter is active
$filtered_class_name = "";
if($class_filter > 0) {
    $class_name_sql = "SELECT name FROM gym_classes WHERE id = ?";
    $class_name_stmt = mysqli_prepare($conn, $class_name_sql);
    mysqli_stmt_bind_param($class_name_stmt, "i", $class_filter);
    mysqli_stmt_execute($class_name_stmt);
    mysqli_stmt_bind_result($class_name_stmt, $filtered_class_name);
    mysqli_stmt_fetch($class_name_stmt);
    mysqli_stmt_close($class_name_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedules - FITHUB Admin</title>
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
                <a href="admin_dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-home mr-2"></i>Dashboard
                
                <a href="admin_classes.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-dumbbell mr-2"></i>Classes
                </a>
                <a href="admin_schedules.php" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-800 text-white">
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
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900"><?php echo $class_filter > 0 ? 'Schedules for "' . htmlspecialchars($filtered_class_name) . '"' : 'Manage All Schedules'; ?></h1>
                        <?php if($class_filter > 0): ?>
                        <a href="admin_schedules.php" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-arrow-left mr-1"></i> View All Schedules
                        </a>
                        <?php endif; ?>
                    </div>
                    <a href="admin_add_schedule.php<?php echo $class_filter > 0 ? '?class_id=' . $class_filter : ''; ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Schedule
                    </a>
                </div>

                <?php if(!empty($success_message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo $success_message; ?></p>
                </div>
                <?php endif; ?>

                <?php if(!empty($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error_message; ?></p>
                </div>
                <?php endif; ?>

                <!-- Filter and Search -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="search-schedules" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Search by class or instructor...">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            <button id="search-button" class="bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 px-5 rounded-lg transition">
                                Search
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="class-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="">All Classes</option>
                                <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>" <?php echo $class_filter == $class['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($class['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <select id="day-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="">All Days</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                            <button id="filter-button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-5 rounded-lg transition">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Schedules Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Day
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Room
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Instructor
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bookings
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($schedules as $schedule): ?>
                                <tr class="schedule-row" data-day="<?php echo htmlspecialchars($schedule['day_of_week']); ?>" data-class="<?php echo htmlspecialchars($schedule['class_name']); ?>" data-instructor="<?php echo htmlspecialchars($schedule['instructor']); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($schedule['class_name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($schedule['day_of_week']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php 
                                            echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                                 date('h:i A', strtotime($schedule['end_time'])); 
                                            ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($schedule['room']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($schedule['instructor']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php echo $schedule['upcoming_bookings']; ?> upcoming
                                            <?php if($schedule['upcoming_bookings'] > 0): ?>
                                            <a href="admin_bookings.php?schedule_id=<?php echo $schedule['id']; ?>" class="text-xs text-blue-600 hover:text-blue-800 block">View Bookings</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($schedule['status'] == 'active'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                        <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="admin_edit_schedule.php?id=<?php echo $schedule['id']; ?>" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="admin_schedules.php?toggle_status=<?php echo $schedule['id']; ?><?php echo $class_filter ? '&class_id=' . $class_filter : ''; ?>" class="<?php echo $schedule['status'] == 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900'; ?>" title="<?php echo $schedule['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>">
                                                <i class="fas <?php echo $schedule['status'] == 'active' ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-900 delete-schedule" data-id="<?php echo $schedule['id']; ?>" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($schedules)): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No schedules found. <a href="admin_add_schedule.php<?php echo $class_filter > 0 ? '?class_id=' . $class_filter : ''; ?>" class="text-blue-600 hover:underline">Add a new schedule</a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Delete Confirmation Modal -->
                <div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
                            <p class="text-gray-600 mb-6">Are you sure you want to delete this schedule? This action cannot be undone and will remove all associated bookings.</p>
                            <div class="flex justify-end space-x-3">
                                <button id="cancel-delete" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                                    Cancel
                                </button>
                                <a href="#" id="confirm-delete" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">
                                    Delete
                                </a>
                            </div>
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
        
        // Class filter redirect
        document.getElementById('class-filter').addEventListener('change', function() {
            const classId = this.value;
            if(classId) {
                window.location.href = `admin_schedules.php?class_id=${classId}`;
            }
        });
        
        // Search functionality
        document.getElementById('search-button').addEventListener('click', function() {
            const searchTerm = document.getElementById('search-schedules').value.toLowerCase();
            const rows = document.querySelectorAll('.schedule-row');
            
            rows.forEach(row => {
                const className = row.dataset.class.toLowerCase();
                const instructor = row.dataset.instructor.toLowerCase();
                
                if(className.includes(searchTerm) || instructor.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Day filter
        document.getElementById('filter-button').addEventListener('click', function() {
            const dayFilter = document.getElementById('day-filter').value;
            const rows = document.querySelectorAll('.schedule-row');
            
            rows.forEach(row => {
                const day = row.dataset.day;
                
                if(!dayFilter || day === dayFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Delete confirmation
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteButton = document.getElementById('confirm-delete');
        let scheduleToDelete = null;
        
        document.querySelectorAll('.delete-schedule').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                scheduleToDelete = this.dataset.id;
                deleteModal.classList.remove('hidden');
            });
        });
        
        document.getElementById('cancel-delete').addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            scheduleToDelete = null;
        });
        
        confirmDeleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            if(scheduleToDelete) {
                window.location.href = `admin_schedules.php?delete=${scheduleToDelete}<?php echo $class_filter ? '&class_id=' . $class_filter : ''; ?>`;
            }
        });
    </script>
</body>
</html>