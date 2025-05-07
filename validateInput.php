<?php
//piyumi
require 'dbUtil.php';

function validateRegistrationInput($fullName, $email, $password, $confirmPassword, $phone) {
    $errors = [];

    // Validate username
    if (empty($fullName)) {
        $errors['fullName'] = "fullName is required.";
    } 
    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format. Please enter a valid email address.";
    }  elseif (!validateEmail($email)) {
            $errors['email'] = "email already taken. Please choose a different username.";
        } 
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    // Confirm password
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    return $errors;
}

?>