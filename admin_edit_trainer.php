<?php
// Initialize the session
session_start();



// Include database connection
require_once "config.php";

// Define variables and initialize with empty values
$full_name = $specialty = $bio = $price_per_session = $status = $current_image = "";
$full_name_err = $specialty_err = $bio_err = $price_err = $image_err = "";
$success_message = $error_message = "";
$upload_dir = "uploads/trainers/"; // Directory to store trainer images

// Check if trainer ID is provided
if(!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header("location: admin_trainers.php");
    exit;
}

$id = trim($_GET["id"]);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate full name
    if(empty(trim($_POST["full_name"]))) {
        $full_name_err = "Please enter trainer name.";
    } else {
        $full_name = trim($_POST["full_name"]);
    }
    
    // Validate specialty
    if(empty(trim($_POST["specialty"]))) {
        $specialty_err = "Please enter trainer specialty.";
    } else {
        $specialty = trim($_POST["specialty"]);
    }
    
    // Validate bio
    if(empty(trim($_POST["bio"]))) {
        $bio_err = "Please enter trainer bio.";
    } else {
        $bio = trim($_POST["bio"]);
    }
    
    // Validate price
    if(empty(trim($_POST["price_per_session"]))) {
        $price_err = "Please enter price per session.";
    } elseif(!is_numeric(trim($_POST["price_per_session"])) || floatval(trim($_POST["price_per_session"])) <= 0) {
        $price_err = "Price must be a positive number.";
    } else {
        $price_per_session = floatval(trim($_POST["price_per_session"]));
    }
    
    // Get status
    $status = $_POST["status"];
    
    // Get current image
    $current_image = $_POST["current_image"];
    
    // Process image upload if new image is provided
    $image_url = $current_image;
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed_types = array("jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png");
        $file_name = $_FILES["image"]["name"];
        $file_type = $_FILES["image"]["type"];
        $file_size = $_FILES["image"]["size"];
        
        // Verify file extension
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed_types)) {
            $image_err = "Please select a valid image format (JPG, JPEG, PNG).";
        }
        
        // Verify file type
        if(in_array($file_type, $allowed_types)) {
            // Verify file size - 5MB maximum
            if($file_size > 5 * 1024 * 1024) {
                $image_err = "Image size exceeds the 5MB limit.";
            }
        } else {
            $image_err = "Please select a valid image format.";
        }
        
        // If no errors, process upload
        if(empty($image_err)) {
            // Generate unique filename to prevent overwriting
            $new_filename = uniqid() . "." . $ext;
            $upload_path = $upload_dir . $new_filename;
            
            // Move uploaded file
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)) {
                $image_url = $upload_path;
                
                // Delete old image if it exists and is not a placeholder
                if(!empty($current_image) && file_exists($current_image) && strpos($current_image, "placeholder") === false) {
                    unlink($current_image);
                }
            } else {
                $image_err = "Error uploading image. Please try again.";
            }
        }
    }
    
    // Check input errors before updating the database
    if(empty($full_name_err) && empty($specialty_err) && empty($bio_err) && empty($price_err) && empty($image_err)) {
        
        // Prepare an update statement
        $sql = "UPDATE trainers SET full_name = ?, specialty = ?, bio = ?, price_per_session = ?, image_url = ?, status = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdssi", $param_name, $param_specialty, $param_price, $param_image, $param_status, $param_id);
            
            // Set parameters
            $param_name = $full_name;
            $param_specialty = $specialty;
            $param_bio = $bio;
            $param_price = $price_per_session;
            $param_image = $image_url;
            $param_status = $status;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Trainer updated successfully!";
            } else {
                $error_message = "Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
} else {
    // Get trainer data
    $sql = "SELECT * FROM trainers WHERE id = ?";
    
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
                $full_name = $row["full_name"];
                $specialty = $row["specialty"];
                $bio = $row["bio"];
                $price_per_session = $row["price_per_session"];
                $current_image = $row["image_url"];
                $status = $row["status"];
            } else {
                // Trainer with given ID not found
                header("location: admin_trainers.php");
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
    <title>Edit Trainer - FITHUB Admin</title>
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
                            <span class="text-gray-700"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Trainer: <?php echo htmlspecialchars($full_name); ?></h1>
                    <a href="admin_trainers.php" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Trainers
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

                <!-- Trainer Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Trainer Name -->
                            <div class="col-span-1">
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Trainer Name <span class="text-red-600">*</span></label>
                                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($full_name_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. John Smith">
                                <?php if(!empty($full_name_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $full_name_err; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Specialty -->
                            <div class="col-span-1">
                                <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Specialty <span class="text-red-600">*</span></label>
                                <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($specialty); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($specialty_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. HIIT & CrossFit">
                                <?php if(!empty($specialty_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $specialty_err; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Price per Session -->
                            <div class="col-span-1">
                                <label for="price_per_session" class="block text-sm font-medium text-gray-700 mb-1">Price per Session <span class="text-red-600">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500">$</span>
                                    </div>
                                    <input type="number" id="price_per_session" name="price_per_session" value="<?php echo htmlspecialchars($price_per_session); ?>" min="0" step="0.01" class="pl-7 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($price_err)) ? 'border-red-500' : ''; ?>" placeholder="e.g. 75.00">
                                </div>
                                <?php if(!empty($price_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $price_err; ?></p>
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
                            
                            <!-- Current Image Preview -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                                <div class="mt-1 relative">
                                    <?php if(!empty($current_image) && file_exists($current_image)): ?>
                                    <img src="<?php echo htmlspecialchars($current_image); ?>" alt="Current trainer image" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                    <?php else: ?>
                                    <div class="h-32 w-32 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-300">
                                        <i class="fas fa-user-tie text-gray-400 text-3xl"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                            </div>
                            
                            <!-- New Image -->
                            <div class="col-span-1">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                                <input type="file" id="image" name="image" accept="image/jpeg,image/png" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($image_err)) ? 'border-red-500' : ''; ?>">
                                <p class="mt-1 text-xs text-gray-500">Upload a new photo of the trainer (JPG, PNG, max 5MB)</p>
                                <?php if(!empty($image_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $image_err; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Trainer Bio -->
                            <div class="col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Trainer Bio <span class="text-red-600">*</span></label>
                                <textarea id="bio" name="bio" rows="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 <?php echo (!empty($bio_err)) ? 'border-red-500' : ''; ?>" placeholder="Enter trainer biography and qualifications..."><?php echo htmlspecialchars($bio); ?></textarea>
                                <?php if(!empty($bio_err)): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo $bio_err; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="admin_trainers.php" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                Update Trainer
                            </button>
                        </div>
                    </form>
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
        
        // Image preview for new image upload
        document.getElementById('image').addEventListener('change', function(e) {
            const fileInput = this;
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // If you want to show a preview of the new image
                    // You can add image preview functionality here
                };
                
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    </script>
</body>
</html>