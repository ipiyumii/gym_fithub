<?php
//piyumi
    function dbConnection(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "gym";

        $mysqli = new mysqli($servername, $username, $password, $dbname);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        return $mysqli;
    }
    
    function saveUserToDatabase($fullName, $hashedPassword, $email ,$phone) {
        $conn = dbConnection();
        if ($conn) {
            echo "Connected successfully!";
        } else {
            echo "Connection failed!";
        }

        $fullName = mysqli_real_escape_string($conn, $fullName);
        $password = mysqli_real_escape_string($conn, $hashedPassword);
        $email = mysqli_real_escape_string($conn, $email);
        $phone = mysqli_real_escape_string($conn, $phone);

        
            $insertQuery = "INSERT INTO members (fullname, password, email, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt) {
                $stmt->bind_param("ssss", $fullName, $hashedPassword, $email, $phone);
                if ($stmt->execute()){
                    $userId = $stmt->insert_id;

                    $stmt->close();
                    $conn->close();
                    return $userId;
                } else {
                    $stmt->close();
                    $conn->close();
                    return false;
                }
            } else {
                $conn->close();
                return false;
            }

    }

    function validateEmail($email) {
        $conn = dbConnection();

        $query = "SELECT * FROM members WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $isAvailable = ($result->num_rows === 0);
        return $isAvailable;

    }


    function getUserData($email) {
        $conn = dbConnection();

        $query = "SELECT * FROM members WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }



    // //admin
    // function getTotalAdmin() {
    //     $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
    //     $result = mysqli_query($conn, $sql);
    //     if ($result) {
    //         $row = mysqli_fetch_assoc($result);
    //         return $row['total'];
    //     }
    //     return 0;
    // }

    // function getTotalActiveMemberships() {
    //     $conn = dbConnection();
    //     $sql = "SELECT COUNT(*) as total FROM user_memberships WHERE end_date >= CURDATE()";
    //     $result = mysqli_query($conn, $sql);
    //     if ($result) {
    //         $row = mysqli_fetch_assoc($result);
    //         return $row['total'];
    //     }
    //     return 0;
    // }

    function getTotalClasses() {
        $conn = dbConnection();

        $sql = "SELECT COUNT(*) as total FROM gym_classes";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }
        return 0;
    }

    // function getTotalBookings() {
    //     $conn = dbConnection();

    //     $sql = "SELECT COUNT(*) as total FROM user_class_bookings WHERE booking_date >= CURDATE()";
    //     $result = mysqli_query($conn, $sql);
    //     if ($result) {
    //         $row = mysqli_fetch_assoc($result);
    //         return $row['total'];
    //     }
    //     return 0;
    // }

    // function getRecentBookings() {
    //     $conn = dbConnection();

    //     $sql = "SELECT ucb.id, u.full_name, gc.name as class_name, cs.day_of_week, cs.start_time, ucb.booking_date, ucb.status
    //             FROM user_class_bookings ucb
    //             JOIN users u ON ucb.user_id = u.id
    //             JOIN class_schedules cs ON ucb.schedule_id = cs.id
    //             JOIN gym_classes gc ON cs.class_id = gc.id
    //             ORDER BY ucb.created_at DESC
    //             LIMIT 5";
    //     $result = mysqli_query($conn, $sql);
    //     $bookings = [];
    //     if ($result) {
    //         while ($row = mysqli_fetch_assoc($result)) {
    //             $bookings[] = $row;
    //         }
    //     }
    //     return $bookings;
    // }

?>
