<?php
// Initialize the session
session_start();



// Include database connection
require_once "config.php";

// Define variables and initialize with empty values
$name = $description = $instructor = $max_capacity = $status = "";
$name_err = $description_err = $instructor_err = $max_capacity_err = "";
$success_message = $error_message = "";

// Check if class ID is provided
if(!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header("location: admin_classes.php");
    exit;
}

$id = trim($_GET["id"]);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate name
    if(empty(trim($_POST["name"]))) {
        $name_err = "Please enter the class name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Validate description
    if(empty(trim($_POST["description"]))) {
        $description_err = "Please enter the class description.";
    } else {
        $description = trim($_POST["description"]);
    }
    
    // Validate instructor
    if(empty(trim($_POST["instructor"]))) {
        $instructor_err = "Please enter instructor name.";
    } else {
        $instructor = trim($_POST["instructor"]);
    }
    
    // Validate max capacity
    if(empty(trim($_POST["max_capacity"]))) {
        $max_capacity_err = "Please enter maximum capacity.";
    } elseif(!is_numeric(trim($_POST["max_capacity"])) || intval(trim($_POST["max_capacity"])) <= 0) {
        $max_capacity_err = "Maximum capacity must be a positive number.";
    } else {
        $max_capacity = intval(trim($_POST["max_capacity"]));
    }
    
    // Get status
    $status = $_POST["status"];
    
    // Check input errors before updating the database
    if(empty($name_err) && empty($description_err) && empty($instructor_err) && empty($max_capacity_err)) {
        
        // Prepare an update statement
        $sql = "UPDATE gym_classes SET name = ?, description = ?, instructor = ?, max_capacity = ?, status = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_description, $param_instructor, $param_max_capacity, $param_status, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description;
            $param_instructor = $instructor;
            $param_max_capacity = $max_capacity;
            $param_status = $status;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Class updated successfully!";
            } else {
                $error_message = "Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
} else {
    // Get class data
    $sql = "SELECT * FROM gym_classes WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                
                // Retrieve individual field values
                $name = $row["name"];
                $description = $row["description"];
                $instructor = $row["instructor"];
                $max_capacity = $row["max_capacity"];
                $status = $row["status"];
            } else {
                // Class with given ID not found
                header("location: admin_classes.php");
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - FITHUB Admin</title>
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
                
                <a href="admin_classes.php" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-800 text-white">
                    <i class="fas fa-dumbbell mr-2"></i>Classes
                </a>
                <a href="admin_schedules.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-calendar-alt mr-2"></i>Schedules
                </a>
                <a href="admin_trainers.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
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
                            <span class="text-gray-700"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Class: <?php echo htmlspecialchars($name); ?></h1>
                    <a href="admin_classes.php" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Classes
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

                <!-- Class Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Class Name -->
                            <div class="col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Class Name <span class="text-red-600">*</span></label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. Yoga Flow">
                                <?php if(!empty($name_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $name_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Instructor -->
                            <div class="col-span-1">
                                <label for="instructor" class="block text-sm font-medium text-gray-700 mb-1">Instructor <span class="text-red-600">*</span></label>
                                <input type="text" id="instructor" name="instructor" value="<?php echo htmlspecialchars($instructor); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($instructor_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. Sarah Johnson">
                                <?php if(!empty($instructor_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $instructor_err; ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Maximum Capacity -->
                            <div class="col-span-1">
                                <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-1">Maximum Capacity <span class="text-red-600">*</span></label>
                                <input type="number" id="max_capacity" name="max_capacity" value="<?php echo htmlspecialchars($max_capacity); ?>" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($max_capacity_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. 20">
                                <?php if(!empty($max_capacity_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $max_capacity_err; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" id="status-active" name="status" value="active" <?php echo $status == "active" ? "checked" : ""; ?> class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="status-active" class="ml-2 text-sm text-gray-700">Active</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="status-inactive" name="status" value="inactive" <?php echo $status == "inactive" ? "checked" : ""; ?> class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="status-inactive" class="ml-2 text-sm text-gray-700">Inactive</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-600">*</span></label>
                                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($description_err)) ? 'border-red-500' : ''; ?>" placeholder="Describe the class..."><?php echo htmlspecialchars($description); ?></textarea>
                                <?php if(!empty($description_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $description_err; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="admin_classes.php" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                Update Class
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Class Schedules -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Class Schedules</h2>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <?php
                        // Re-establish connection since we closed it earlier
                        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                        
                        // Get class schedules
                        $schedule_sql = "SELECT * FROM class_schedules WHERE class_id = ? ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";
                        $schedule_stmt = mysqli_prepare($conn, $schedule_sql);
                        mysqli_stmt_bind_param($schedule_stmt, "i", $id);
                        mysqli_stmt_execute($schedule_stmt);
                        $schedule_result = mysqli_stmt_get_result($schedule_stmt);
                        
                        $has_schedules = mysqli_num_rows($schedule_result) > 0;
                        ?>
                        
                        <?php if($has_schedules): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php while($schedule = mysqli_fetch_assoc($schedule_result)): ?>
                                    <tr>
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
                                                <a href="admin_schedules.php?toggle_status=<?php echo $schedule['id']; ?>" class="<?php echo $schedule['status'] == 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900'; ?>" title="<?php echo $schedule['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>">
                                                    <i class="fas <?php echo $schedule['status'] == 'active' ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                </a>
                                                <a href="admin_schedules.php?delete=<?php echo $schedule['id']; ?>" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Are you sure you want to delete this schedule?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-6">
                            <p class="text-gray-500 mb-4">No schedules have been created for this class yet.</p>
                            <a href="admin_add_schedule.php?class_id=<?php echo $id; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Schedule
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Close statement and connection
                        mysqli_stmt_close($schedule_stmt);
                        mysqli_close($conn);
                        ?>
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