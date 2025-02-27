
<?php


// CREATE TABLE users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(100) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     role ENUM('student', 'faculty', 'admin') NOT NULL DEFAULT 'student',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );


include 'Database/db.php';

// Check if the form is submitted

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a select statement
    $sql = "SELECT * FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the variables to the prepared statement as parameters
        $stmt->bind_param("s", $email);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows == 1) {
                // Fetch the result
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, so start a new session
                    session_start();

                    // Store data in session variables
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect user to the dashboard page
                    if ($user['role'] == 'student') {
                        header("location: Dashboard/student.php?uid=" . $user['id']);
                    } elseif ($user['role'] == 'faculty') {

                        header("location: Dashboard/faculty.php?uid=" . $user['id']);
                    } elseif ($user['role'] == 'admin') {
                        header("location: Dashboard/admin.php?uid=" . $user['id']);
                    }
                } else {
                    echo "<script>alert('Invalid email or password');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password');</script>";
            }
        } else {
            echo "<script>alert('There was an error while logging in');</script>";
        }

        // Close the statement
        $stmt->close();
    }
}








?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center">Login</h2>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
