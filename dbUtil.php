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

    
         $insertQuery = "INSERT INTO users (full_name, password, email, phone) VALUES (?, ?, ?, ?)";
         $stmt = $conn->prepare($insertQuery);

         if ($stmt) {
             $stmt->bind_param("ssss", $fullName, $hashedPassword, $email, $phone);
             $stmt->execute();

             $userId = $stmt->insert_id;

             $stmt->close();
             $conn->close();

             return $userId;
         } else {
             $conn->close();
             return "Error in prepared statement: " . $conn->error;
         }

}

function validateEmail($email) {
    $conn = dbConnection();

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $isAvailable = ($result->num_rows === 0);
    return $isAvailable;

}


function getUserData($email) {
    $conn = dbConnection();

    $query = "SELECT * FROM users WHERE email = ?";
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



?>
