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
    $mysqli = dbConnection();

     $fullName = mysqli_real_escape_string($mysqli, $fullName);
     $password = mysqli_real_escape_string($mysqli, $hashedPassword);
     $email = mysqli_real_escape_string($mysqli, $email);
     $phone = mysqli_real_escape_string($mysqli, $phone);

    
         $insertQuery = "INSERT INTO users (fullname, password, email, phone) VALUES (?, ?, ?, ?)";
         $stmt = $mysqli->prepare($insertQuery);

         if ($stmt) {
             $stmt->bind_param("ssss", $fullName, $hashedPassword, $email, $phone);
             $stmt->execute();

             $userId = $stmt->insert_id;

             $stmt->close();
             $mysqli->close();

             return $userId;
         } else {
             $mysqli->close();
             return "Error in prepared statement: " . $mysqli->error;
         }

}

function validateEmail($email) {
    $mysqli = dbConnection();

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $isAvailable = ($result->num_rows === 0);
    return $isAvailable;

}


function getUserData($email) {
    $mysqli = dbConnection();

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($query);
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
