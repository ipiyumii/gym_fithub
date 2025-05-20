<?php
    include_once 'dbUtil.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // $host = "localhost";
    // $username = "root";
    // $password = "root";
    // $database = "gym";

    // $conn = new mysqli($host, $username, $password, $database);

    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // }

    $conn = dbConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['user_id'];
    $message = '';
    $messageType = '';

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageType = $_SESSION['message_type'] ?? 'info';
        unset($_SESSION['message'], $_SESSION['message_type']);
    }

    // Process class booking submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule_id']) && isset($_POST['booking_date'])) {
        $scheduleId = $_POST['schedule_id'];
        $bookingDate = $_POST['booking_date'];
        
        // Validate booking date is in the future
        if (strtotime($bookingDate) < strtotime(date('Y-m-d'))) {
            $message = "Cannot book a class for a past date.";
            $messageType = "error";
        } else {
            // // Check if user has an active membership
            // $membershipQuery = "
            //     SELECT COUNT(*) as has_membership 
            //     FROM user_memberships 
            //     WHERE user_id = ? AND end_date >= CURDATE()
            // ";
            // $stmt = $conn->prepare($membershipQuery);
            // $stmt->bind_param("i", $userId);
            // $stmt->execute();
            // $membershipResult = $stmt->get_result();
            // $membership = $membershipResult->fetch_assoc();
            // $stmt->close();
            
            // if ($membership['has_membership'] == 0) {
            //     $message = "You need an active membership to book classes.";
            //     $messageType = "error";
            // } else {
                // Check if class is already booked by user on that date
                $checkBookingQuery = "
                    SELECT COUNT(*) as booked 
                    FROM class_bookings 
                    WHERE member_id = ? AND schedule_id = ? AND class_date = ? AND status = 'booked'
                ";
                $stmt = $conn->prepare($checkBookingQuery);
                $stmt->bind_param("iis", $userId, $scheduleId, $bookingDate);
                $stmt->execute();
                $bookingCheckResult = $stmt->get_result();
                $bookingCheck = $bookingCheckResult->fetch_assoc();
                $stmt->close();
                
                if ($bookingCheck['booked'] > 0) {
                    $message = "You have already booked this class on this date.";
                    $messageType = "error";
                } else {
                    // Check if class is full for that date
                    $capacityQuery = "
                        SELECT gc.max_capacity, 
                            (SELECT COUNT(*) FROM user_class_bookings 
                                WHERE schedule_id = ? AND booking_date = ? AND status = 'booked') as current_bookings
                        FROM class_schedules cs
                        JOIN gym_classes gc ON cs.class_id = gc.id
                        WHERE cs.id = ?
                    ";
                    $stmt = $conn->prepare($capacityQuery);
                    $stmt->bind_param("isi", $scheduleId, $bookingDate, $scheduleId);
                    $stmt->execute();
                    $capacityResult = $stmt->get_result();
                    $capacity = $capacityResult->fetch_assoc();
                    $stmt->close();
                    
                    if ($capacity['current_bookings'] >= $capacity['max_capacity']) {
                        $message = "This class is fully booked for the selected date.";
                        $messageType = "error";
                    } else {
                        // Insert booking
                        // $bookingQuery = "
                        //     INSERT INTO user_class_bookings (user_id, schedule_id, booking_date, status)
                        //     VALUES (?, ?, ?, 'booked')
                        // ";

                        $success = insertClassBooking($userId, $scheduleId, $bookingDate);
                        if ($success) {
                            $_SESSION['message'] = "Class booked successfully!";
                            $_SESSION['message_type'] = "success";
                        } else {
                            $_SESSION['message'] = "Error booking class. Please try again.";
                            $_SESSION['message_type'] = "error";
                        }

                        header("Location: book_class.php");
                        exit();
                        
                        // if ($stmt->execute()) {
                        //     $message = "Class booked successfully!";
                        //     $messageType = "success";
                        // } else {
                        //     $message = "Error booking class. Please try again.";
                        //     $messageType = "error";
                        // }
                        // $stmt->close();
                    }
                }
            }
        }
    // }

    // Get available class schedules
    $scheduleQuery = "
        SELECT cs.id, cs.day_of_week, cs.start_time, cs.end_time, cs.room,
            gc.id as class_id, gc.name as class_name, gc.description, gc.instructor
        FROM class_schedules cs
        JOIN gym_classes gc ON cs.class_id = gc.id
        WHERE cs.status = 'active' AND gc.status = 'active'
        ORDER BY 
            CASE 
                WHEN cs.day_of_week = 'Monday' THEN 1
                WHEN cs.day_of_week = 'Tuesday' THEN 2
                WHEN cs.day_of_week = 'Wednesday' THEN 3
                WHEN cs.day_of_week = 'Thursday' THEN 4
                WHEN cs.day_of_week = 'Friday' THEN 5
                WHEN cs.day_of_week = 'Saturday' THEN 6
                WHEN cs.day_of_week = 'Sunday' THEN 7
            END, 
            cs.start_time
    ";
    $scheduleResult = $conn->query($scheduleQuery);
    $schedules = [];
    while ($schedule = $scheduleResult->fetch_assoc()) {
        $schedules[] = $schedule;
    }

    $conn->close();
?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>FitLife Gym - Book a Class</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        </head>
        <body class="bg-gray-100 min-h-screen">
            <!-- Navigation -->
            <nav class="bg-blue-600 text-white shadow-lg">
                <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                    <div class="text-xl font-bold">FitLife Gym</div>
                    <div class="flex items-center space-x-4">
                        <a href="dashboard.php" class="hover:text-gray-200">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        <a href="logout.php" class="hover:text-gray-200">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-2xl font-bold mb-8">Book a Class</h1>
                
                <?php if ($message): ?>
                <div class="mb-6 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <!-- Class Schedule -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Available Classes</h2>
                    
                    <!-- Filter options -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-3 mb-4">
                            <button id="all-filter" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm filter-btn active">
                                All Classes
                            </button>
                            <?php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($days as $day) {
                                echo "<button id=\"{$day}-filter\" class=\"bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm filter-btn\" data-day=\"{$day}\">{$day}</button>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Classes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($schedules as $schedule): ?>
                        <div class="class-card border rounded-lg overflow-hidden shadow-sm day-<?php echo strtolower($schedule['day_of_week']); ?>">
                            <div class="bg-blue-50 px-4 py-2 border-b">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($schedule['class_name']); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($schedule['day_of_week']); ?>, 
                                    <?php echo date('g:i A', strtotime($schedule['start_time'])); ?> - 
                                    <?php echo date('g:i A', strtotime($schedule['end_time'])); ?>
                                </p>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars($schedule['description']); ?></p>
                                <div class="flex items-center text-sm mb-3">
                                    <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                    <span><?php echo htmlspecialchars($schedule['instructor']); ?></span>
                                </div>
                                <div class="flex items-center text-sm mb-4">
                                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                    <span><?php echo htmlspecialchars($schedule['room']); ?></span>
                                </div>
                                
                                <form action="book_class.php" method="POST" class="mt-3" autocomplete="off">
                                    <input type="hidden" name="schedule_id" value="<?php echo $schedule['id']; ?>">
                                    <div class="mb-3">
                                        <label for="booking_date_<?php echo $schedule['id']; ?>" class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                                        <input 
                                            type="date" 
                                            id="class_date_<?php echo $schedule['id']; ?>" 
                                            name="booking_date" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            min="<?php echo date('Y-m-d'); ?>"
                                            max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                                            required
                                        >
                                    </div>
                                    <button 
                                        type="submit" 
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                                        Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    </div>
        </div>
</div>
                    
               
            
            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-6 mt-8">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-semibold mb-2">FitLife Gym</h3>
                            <p class="text-sm text-gray-400">Your fitness journey starts here</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Quick Links</h4>
                                <ul class="text-sm text-gray-400">
                                    <li class="mb-1"><a href="index.php" class="hover:text-white">Home</a></li>
                                    <li class="mb-1"><a href="classes.php" class="hover:text-white">Classes</a></li>
                                    <li class="mb-1"><a href="memberships.php" class="hover:text-white">Memberships</a></li>
                                    <li class="mb-1"><a href="contact.php" class="hover:text-white">Contact Us</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Support</h4>
                                <ul class="text-sm text-gray-400">
                                    <li class="mb-1"><a href="faq.php" class="hover:text-white">FAQ</a></li>
                                    <li class="mb-1"><a href="terms.php" class="hover:text-white">Terms of Service</a></li>
                                    <li class="mb-1"><a href="privacy.php" class="hover:text-white">Privacy Policy</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Contact</h4>
                                <ul class="text-sm text-gray-400">
                                    <li class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i> 123 Fitness St, Gym City</li>
                                    <li class="mb-1"><i class="fas fa-phone mr-2"></i> (555) 123-4567</li>
                                    <li class="mb-1"><i class="fas fa-envelope mr-2"></i> info@fitlifegym.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 mt-6 pt-6 text-sm text-center text-gray-400">
                        <p>&copy; <?php echo date('Y'); ?> FitLife Gym. All rights reserved.</p>
                    </div>
                </div>
            </footer>

            <script>
                // Filter classes by day
                document.addEventListener('DOMContentLoaded', function() {
                    const filterButtons = document.querySelectorAll('.filter-btn');
                    const classCards = document.querySelectorAll('.class-card');
                    
                    filterButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            // Remove active class from all buttons
                            filterButtons.forEach(btn => {
                                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                                btn.classList.add('bg-gray-200', 'text-gray-700');
                            });
                            
                            // Add active class to clicked button
                            this.classList.remove('bg-gray-200', 'text-gray-700');
                            this.classList.add('active', 'bg-blue-600', 'text-white');
                            
                            const day = this.getAttribute('data-day');
                            
                            // Show all classes if "All Classes" button is clicked
                            if (this.id === 'all-filter') {
                                classCards.forEach(card => {
                                    card.style.display = 'block';
                                });
                            } else {
                                // Show only classes for selected day
                                classCards.forEach(card => {
                                    if (card.classList.contains('day-' + day.toLowerCase())) {
                                        card.style.display = 'block';
                                    } else {
                                        card.style.display = 'none';
                                    }
                                });
                            }
                        });
                    });
                    
                    // Date input validation - set date to next matching day of the week
                    classCards.forEach(card => {
                        const dayOfWeek = card.className.match(/day-(\w+)/)[1]; // Extract day from class
                        const dateInputs = card.querySelectorAll('input[type="date"]');
                        
                        dateInputs.forEach(input => {
                            input.addEventListener('click', function() {
                                // Get the next date for this day of the week
                                const nextDateForDay = getNextDayOfWeek(dayOfWeek);
                                this.value = nextDateForDay;
                            });
                        });
                    });
                    
                    // Function to get the next date for a specific day of the week
                    function getNextDayOfWeek(dayName) {
                        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        const dayIndex = days.indexOf(dayName.toLowerCase());
                        
                        if (dayIndex === -1) return '';
                        
                        const today = new Date();
                        const todayIndex = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
                        
                        let daysUntilNext = dayIndex - todayIndex;
                        if (daysUntilNext <= 0) {
                            daysUntilNext += 7; // If today or in the past, get next week
                        }
                        
                        const nextDate = new Date();
                        nextDate.setDate(today.getDate() + daysUntilNext);
                        
                        // Format as YYYY-MM-DD for date input
                        return nextDate.toISOString().split('T')[0];
                    }
                });
            </script>
    </body>
</html>