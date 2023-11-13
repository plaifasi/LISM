<?php
include('dbcon.php');

if (isset($_GET['id_package'])) {
    $id_package = $_GET['id_package']; // Get the ID of the item to delete from the URL

    // Check if the status_package is 'Pause' before allowing deletion
    $status_check_query = "SELECT status_package FROM package WHERE id_package = $id_package";
    $status_check_result = mysqli_query($con, $status_check_query);

    if ($status_check_result && mysqli_num_rows($status_check_result) > 0) {
        $row = mysqli_fetch_assoc($status_check_result);
        $status_package = $row['status_package'];
        var_dump($status_package);
        if ($status_package === 'Pause' || $status_package === 'Draft') {
            // Add a check here to see if the user has confirmed the deletion
            if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
                // Retrieve the image_id associated with the package
                $image_query = "SELECT image_id FROM package WHERE id_package = $id_package";
                $image_result = mysqli_query($con, $image_query);

                if ($image_result && mysqli_num_rows($image_result) > 0) {
                    $row = mysqli_fetch_assoc($image_result);
                    $image_id = $row['image_id'];

                    echo "Image ID to delete: $image_id";

                    // Delete the image data from the 'images' table
                    $delete_image_query = "DELETE FROM images WHERE image_id = $image_id";
                    $delete_image_result = mysqli_query($con, $delete_image_query);

                    if ($delete_image_result) {
                        echo "Image data deleted successfully.";
                    } else {
                        echo "Error deleting image: " . mysqli_error($con);
                    }
                } else {
                    echo "Image not found for ID: $id_package";
                }

                // Perform the delete query for the package
                $delete_query = "DELETE FROM package WHERE id_package = $id_package"; // Replace 'package' with your table name
                $delete_result = mysqli_query($con, $delete_query);

                if ($delete_result) {
                    // Redirect to main.php after successful deletion
                    header("Location: main.php");
                    exit;
                } else {
                    echo "Error deleting data: " . mysqli_error($con);
                }
            } else {
                // If the user hasn't confirmed the deletion, show a confirmation dialog
                echo '<script>';
                echo 'var confirmation = confirm("Please make sure you status package!Delete is only allowed for packages with \'Pause\' or \'Draft\' status. Do you want to proceed with the deletion?");';
                echo 'if (confirmation) {';
                echo '   window.location.href = "delete.php?id_package=' . $id_package . '&confirm=true";';
                echo '} else {';
                echo '   history.go(-1);'; // Go back to the previous page if the user clicks Cancel
                echo '}';
                echo '</script>';
            }
        } else {
            echo "Deletion is only allowed for packages with 'Pause' status.";
        }
    } else {
        echo "Package not found.";
    }
} else {
    echo "Invalid request.";
}
?>
