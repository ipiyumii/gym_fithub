<?php
// Initialize the session
session_start();



// Include database connection
require_once "config.php";

// Define variables and initialize with empty values
$class_id = $day_of_week = $start_time = $end_time = $room = "";
$class_id_err = $day_of_week_err = $start_time_err = $end_time_err = $room_err = "";
$success_message = $error_message = "";

// Pre-select class if passed in URL
if(isset($_GET['class_id']) && !empty($_GET['class_id'])) {
    $class_id = intval($_GET['class_id']);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate class
    if(empty($_POST["class_id"])) {
        $class_id_err = "Please select a class.";
    } else {
        $class_id = intval($_POST["class_id"]);
    }
    
    // Validate day of week
    if(empty($_POST["day_of_week"])) {
        $day_of_week_err = "Please select a day.";
    } else {
        $day_of_week = $_POST["day_of_week"];
    }
    
    // Validate start time
    if(empty($_POST["start_time"])) {
        $start_time_err = "Please enter a start time.";
    } else {
        $start_time = $_POST["start_time"];
    }
    
    // Validate end time
    if(empty($_POST["end_time"])) {
        $end_time_err = "Please enter an end time.";
    } else {
        $end_time = $_POST["end_time"];
        
        // Check if end time is after start time
        if(!empty($start_time) && strtotime($end_time) <= strtotime($start_time)) {
            $end_time_err = "End time must be after start time.";
        }
    }
    
    // Validate room
    if(empty($_POST["room"])) {
        $room_err = "Please enter a room.";
    } else {
        $room = $_POST["room"];
    }
    
    // Check for scheduling conflicts
    if(empty($class_id_err) && empty($day_of_week_err) && empty($start_time_err) && empty($end_time_err) && empty($room_err)) {
        // Check if room is already booked for the same day and time
        $conflict_sql = "SELECT id FROM class_schedules 
                        WHERE room = ? AND day_of_week = ? AND 
                        ((start_time <= ? AND end_time > ?) OR 
                         (start_time < ? AND end_time >= ?) OR 
                         (start_time >= ? AND end_time <= ?))";
        
        $conflict_stmt = mysqli_prepare($conn, $conflict_sql);
        mysqli_stmt_bind_param($conflict_stmt, "ssssssss", 
                              $room, $day_of_week, 
                              $end_time, $start_time, 
                              $end_time, $start_time, 
                              $start_time, $end_time);
        mysqli_stmt_execute($conflict_stmt);
        mysqli_stmt_store_result($conflict_stmt);
        
        if(mysqli_stmt_num_rows($conflict_stmt) > 0) {
            $error_message = "This room is already booked during the selected time on this day. Please choose a different time or room.";
        } else {
            // Prepare an insert statement
            $sql = "INSERT INTO class_schedules (class_id, day_of_week, start_time, end_time, room) VALUES (?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "issss", $param_class_id, $param_day, $param_start, $param_end, $param_room);
                
                // Set parameters
                $param_class_id = $class_id;
                $param_day = $day_of_week;
                $param_start = $start_time;
                $param_end = $end_time;
                $param_room = $room;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $success_message = "Schedule added successfully!";
                    
                    // Clear form fields after successful submission
                    $start_time = $end_time = $room = "";
                } else {
                    $error_message = "Something went wrong. Please try again later.";
                }
                
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        mysqli_stmt_close($conflict_stmt);
    }
}

// Get all classes for dropdown
$classes = [];
$classes_sql = "SELECT id, name FROM gym_classes WHERE status = 'active' ORDER BY name";
$classes_result = mysqli_query($conn, $classes_sql);
if($classes_result) {
    while($class_row = mysqli_fetch_assoc($classes_result)) {
        $classes[] = $class_row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class Schedule - FITHUB Admin</title>
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
                </a>
                
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
                    <h1 class="text-2xl font-semibold text-gray-900">Add New Class Schedule</h1>
                    <a href="admin_schedules.php<?php echo $class_id ? '?class_id=' . $class_id : ''; ?>" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Schedules
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

                <!-- Schedule Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($class_id ? '?class_id=' . $class_id : '')); ?>" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Class Selection -->
                            <div class="col-span-1">
                                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-600">*</span></label>
                                <select id="class_id" name="class_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($class_id_err)) ? 'border-red-500' : ''; ?>">
                                    <option value="">Select Class</option>
                                    <?php foreach($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $class_id == $class['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($class['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(!empty($class_id_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $class_id_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Day of Week -->
                            <div class="col-span-1">
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day of Week <span class="text-red-600">*</span></label>
                                <select id="day_of_week" name="day_of_week" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($day_of_week_err)) ? 'border-red-500' : ''; ?>">
                                    <option value="">Select Day</option>
                                    <option value="Monday" <?php echo $day_of_week == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                                    <option value="Tuesday" <?php echo $day_of_week == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                                    <option value="Wednesday" <?php echo $day_of_week == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                                    <option value="Thursday" <?php echo $day_of_week == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                                    <option value="Friday" <?php echo $day_of_week == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                                    <option value="Saturday" <?php echo $day_of_week == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                                    <option value="Sunday" <?php echo $day_of_week == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                                </select>
                                <?php if(!empty($day_of_week_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $day_of_week_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Start Time -->
                            <div class="col-span-1">
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-600">*</span></label>
                                <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($start_time_err)) ? 'border-red-500' : ''; ?>">
                                <?php if(!empty($start_time_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $start_time_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- End Time -->
                            <div class="col-span-1">
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-600">*</span></label>
                                <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($end_time); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($end_time_err)) ? 'border-red-500' : ''; ?>">
                                <?php if(!empty($end_time_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $end_time_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Room -->
                            <div class="col-span-1">
                                <label for="room" class="block text-sm font-medium text-gray-700 mb-1">Room <span class="text-red-600">*</span></label>
                                <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($room); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($room_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. Studio A">
                                <?php if(!empty($room_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $room_err; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="admin_schedules.php<?php echo $class_id ? '?class_id=' . $class_id : ''; ?>" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                Add Schedule
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Room Schedule Reference -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Room Schedule</h2>
                    <p class="text-gray-600 mb-4">Use this reference to avoid scheduling conflicts. The system will prevent you from double-booking a room, but this can help you plan more effectively.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Monday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Tuesday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Wednesday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Thursday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Friday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Saturday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sunday</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                // Get all rooms
                                $rooms = [];
                                $room_sql = "SELECT DISTINCT room FROM class_schedules ORDER BY room";
                                $room_result = mysqli_query($conn, $room_sql);
                                if($room_result) {
                                    while($room_row = mysqli_fetch_assoc($room_result)) {
                                        $rooms[] = $room_row['room'];
                                    }
                                }
                                
                                // For each room, get the schedule for each day
                                foreach($rooms as $current_room):
                                ?>
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($current_room); ?></td>
                                    
                                    <?php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    foreach($days as $day):
                                        // Get schedules for this room and day
                                        $schedule_sql = "SELECT cs.start_time, cs.end_time, gc.name as class_name 
                                                        FROM class_schedules cs
                                                        JOIN gym_classes gc ON cs.class_id = gc.id
                                                        WHERE cs.room = ? AND cs.day_of_week = ? AND cs.status = 'active'
                                                        ORDER BY cs.start_time";
                                        $schedule_stmt = mysqli_prepare($conn, $schedule_sql);
                                        mysqli_stmt_bind_param($schedule_stmt, "ss", $current_room, $day);
                                        mysqli_stmt_execute($schedule_stmt);
                                        $schedule_result = mysqli_stmt_get_result($schedule_stmt);
                                        
                                        $day_schedules = [];
                                        while($schedule_row = mysqli_fetch_assoc($schedule_result)) {
                                            $day_schedules[] = $schedule_row;
                                        }
                                        mysqli_stmt_close($schedule_stmt);
                                    ?>
                                    <td class="px-4 py-2 text-xs text-gray-600">
                                        <?php if(empty($day_schedules)): ?>
                                        <span class="text-gray-400">No classes</span>
                                        <?php else: ?>
                                            <?php foreach($day_schedules as $schedule): ?>
                                            <div class="mb-1 p-1 rounded bg-blue-50">
                                                <div class="font-medium"><?php echo htmlspecialchars($schedule['class_name']); ?></div>
                                                <div>
                                                    <?php echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                                         date('h:i A', strtotime($schedule['end_time'])); ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if(empty($rooms)): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No room schedules found yet.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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