<?php
include('dbcon.php');

if (isset($_POST['update'])) {
    $id_package = $_POST['id']; // Get the ID of the item to update
    $title = $_POST['title'];
    $destination = $_POST['destination_package'];
    $description = $_POST['description_package'];
    $vehicle_package = $_POST['vehicle_package'];
    $hotel_package = $_POST['hotel_package'];

    // Update package information
    $update_query = "UPDATE package SET title_package = ?, destination_package = ?, description_package = ?, vehicle_package = ?, hotel_package = ? WHERE id_package = ?";


    $stmt = mysqli_prepare($con, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssi', $title, $destination, $description, $vehicle_package, $hotel_package, $id_package);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $affectedRows = mysqli_affected_rows($con);
            echo "Updated $affectedRows rows successfully.";

            // Handle image update
            if ($_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
                $newImageData = file_get_contents($_FILES['new_image']['tmp_name']);

                // Update the image in the 'images' table based on the 'image_id' associated with the 'package'
                $update_image_query = "UPDATE images SET image_data = ? WHERE image_id = (SELECT image_id FROM package WHERE id_package = ?)";
                $stmt_image = mysqli_prepare($con, $update_image_query);
                mysqli_stmt_bind_param($stmt_image, 'si', $newImageData, $id_package);

                if (mysqli_stmt_execute($stmt_image)) {
                    echo "Image updated successfully.";
                } else {
                    echo "Error updating image: " . mysqli_error($con);
                }
            }

            // Handle PDF file update
            if ($_FILES['new_file']['error'] === UPLOAD_ERR_OK) {
                $newFileData = file_get_contents($_FILES['new_file']['tmp_name']);

                // Update the PDF file in the 'files' table based on the 'file_id' associated with the 'package'
                $update_file_query = "UPDATE files SET file_data = ? WHERE file_id = (SELECT file_id FROM package WHERE id_package = ?)";
                $stmt_file = mysqli_prepare($con, $update_file_query);
                mysqli_stmt_bind_param($stmt_file, 'si', $newFileData, $id_package);

                if (mysqli_stmt_execute($stmt_file)) {
                    echo "PDF file updated successfully.";
                } else {
                    echo "Error updating PDF file: " . mysqli_error($con);
                }
            }

            // Redirect to the details page after updating
            header("Location: details.php?id_package=$id_package");
            exit;
        } else {
            echo "Error updating package data: " . mysqli_error($con);
        }
    } else {
        echo "Error preparing the package update statement: " . mysqli_error($con);
    }
}
?>
