<?php
session_start();

// initializing variables
$fname = "";
$lname = "";
$email = "";
$dob = "";
$gender = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'userform');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $dob = mysqli_real_escape_string($db, $_POST['dob']);
  $gender = mysqli_real_escape_string($db, $_POST['gender']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled
  if (empty($fname)) { array_push($errors, "First name is required"); }
  if (empty($lname)) { array_push($errors, "Last name is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($dob)) { array_push($errors, "Date of Birth is required"); }
  if (empty($gender)) { array_push($errors, "Gender is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
  }

  // check if a user already exists with the same email
  $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists
    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  // register user if no errors
  if (count($errors) == 0) {
    $password = md5($password_1); // encrypt password before saving

    // Insert user data into the database
    $query = "INSERT INTO users (fname, lname, email, dob, gender, password) 
              VALUES('$fname', '$lname', '$email', '$dob', '$gender', '$password')";
    mysqli_query($db, $query);

    // Start session and redirect to homepage
    $_SESSION['email'] = $email;
    $_SESSION['success'] = "You are now logged in";
    header('location: index.php');
  }
}

// Predefined admin credentials
$admin_email = 'admin@example.com'; // Admin email
$admin_password = md5('Adm@2024'); // Admin password (hashed with md5)


// LOGIN USER
if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password); // encrypt the password before comparing
    
    // Check if the user is the predefined admin
    if ($email == $admin_email && $password == $admin_password) {
      // Admin login successful, redirect to admin dashboard
      $_SESSION['admin'] = $email;
      header('location: _adminpage\index.php'); // Redirect to admin page
      exit();
    }

    // Check email and password
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) > 0) {
      $user = mysqli_fetch_assoc($results);
  
      // Check if email is verified
      if ($user['email_verified'] == 1) {
          // Log the user in
          $_SESSION['email'] = $email;
          header('location: index.php');
      }else {
          array_push($errors, "Please verify your email address before logging in.");
      }
  } else {
      array_push($errors, "Incorrect email or password.");
  }
  }
}






?>
