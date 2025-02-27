<?php
// Include the database connection file
include '../../Database/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $response_text = $_POST['response_text'];
    $task_id = $_POST['task_id'];
    $student_id = $_POST['student_id'];
    $file_path = null;

    // Check if a file is uploaded
    if (isset($_FILES['response_file']) && $_FILES['response_file']['error'] == 0) {
        $target_dir = "../../uploads/";
        $file_path = $target_dir . basename($_FILES["response_file"]["name"]);
        move_uploaded_file($_FILES["response_file"]["tmp_name"], $file_path);
    }

    // Check if the task_id exists in the tasks table
    $task_check_sql = "SELECT id FROM tasks WHERE id = ?";
    if ($task_stmt = $conn->prepare($task_check_sql)) {
        $task_stmt->bind_param("i", $task_id);
        $task_stmt->execute();
        $task_stmt->store_result();

        if ($task_stmt->num_rows == 0) {
            echo "<script>alert('Invalid Task ID');</script>";
            $task_stmt->close();
            exit();
        }
        $task_stmt->close();
    }

    // Prepare an insert statement
    $sql = "INSERT INTO responses (student_id, task_id, response_text, file_path) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the variables to the prepared statement as parameters
        $stmt->bind_param("iiss", $student_id, $task_id, $response_text, $file_path);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "<script>alert('Response submitted successfully');</script>";
        } else {
            echo "<script>alert('There was an error while submitting the response');</script>";
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Response</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Submit Response</h2>
        <form action="response.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="number" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="task_id">Task ID</label>
                <input type="number" class="form-control" id="task_id" name="task_id" required>
            </div>
            <div class="form-group">
                <label for="response_text">Response Text</label>
                <textarea class="form-control" id="response_text" name="response_text" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="response_file">Upload File</label>
                <input type="file" class="form-control-file" id="response_file" name="response_file">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
