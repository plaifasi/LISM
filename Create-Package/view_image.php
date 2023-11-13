<?php
include('dbcon.php');

if (isset($_GET['id_payment'])) {
    $id_payment = $_GET['id_payment'];

    // Retrieve the image_id associated with the payment
    $image_id_query = "SELECT image_id FROM payment WHERE id_payment = ?";
    $stmt_image_id = mysqli_prepare($con, $image_id_query);

    if ($stmt_image_id) {
        mysqli_stmt_bind_param($stmt_image_id, "i", $id_payment);

        if (mysqli_stmt_execute($stmt_image_id)) {
            $result_image_id = mysqli_stmt_get_result($stmt_image_id);

            if ($row_image_id = mysqli_fetch_assoc($result_image_id)) {
                $image_id = $row_image_id['image_id'];

                // Retrieve the image data from the 'images' table using the image_id
                $image_data_query = "SELECT image_data FROM images WHERE image_id = ?";
                $stmt_image_data = mysqli_prepare($con, $image_data_query);

                if ($stmt_image_data) {
                    mysqli_stmt_bind_param($stmt_image_data, "i", $image_id);

                    if (mysqli_stmt_execute($stmt_image_data)) {
                        $result_image_data = mysqli_stmt_get_result($stmt_image_data);

                        if ($row_image_data = mysqli_fetch_assoc($result_image_data)) {
                            // Set the appropriate headers to display the image
                            header("Content-type: image/jpeg"); // Adjust the content type based on your image format

                            // Output the image data
                            echo $row_image_data['image_data'];
                        } else {
                            echo "Image data not found.";
                        }
                    } else {
                        echo "Error executing image data query: " . mysqli_error($con);
                    }

                    mysqli_stmt_close($stmt_image_data);
                } else {
                    echo "Error preparing image data query: " . mysqli_error($con);
                }
            } else {
                echo "Image not found for the payment. Please check the 'image_id' in the 'payment' table.";
            }
        } else {
            echo "Error executing image ID query: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt_image_id);
    } else {
        echo "Error preparing image ID query: " . mysqli_error($con);
    }

    mysqli_close($con);
} else {
    echo "Invalid request.";
}
