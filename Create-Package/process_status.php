<?php
include('dbcon.php');

if (isset($_POST['status_package'])) {
    $status = $_POST['status_package'];
    $title = $_POST['title_package'];
    $destination = $_POST['destination_package'];
    $description = $_POST['description_package'];
    $image_id = $_POST['image_id'];
    $fileData = $_POST['file_package'];

    // Prepare the SQL statement
    if ($status == "draft") {
        $insert_package_query = "INSERT INTO package (title_package, destination_package, description_package, image_id, file_package, status) 
            VALUES (?, ?, ?, ?, ?, 'Draft')";
    } elseif ($status == "publish") {
        $insert_package_query = "INSERT INTO package (title_package, destination_package, description_package, image_id, file_package, status) 
            VALUES (?, ?, ?, ?, ?, 'Publish')";
    }
    
    $stmt = mysqli_prepare($con, $insert_package_query);
    
    if ($stmt) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ssssss", $title, $destination, $description, $image_id, $fileData, $status);
        $execute_result = mysqli_stmt_execute($stmt);
    
        if ($execute_result) {
            // Data inserted successfully, you can redirect or display a success message
            header("Location: main.php"); // Redirect to the main page
            exit;
        } else {
            echo "Error executing prepared statement: " . mysqli_error($con);
        }
    
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
    
} else {
    echo "Invalid request.";
}
?>
