<?php
include('dbcon.php');
session_start();

// Assuming you've retrieved and stored id_company in $_SESSION['id_company'] already.

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = isset($_POST['title_package']) ? $_POST['title_package'] : '';
    $destination = isset($_POST['destination_package']) ? $_POST['destination_package'] : '';
    $description = isset($_POST['description_package']) ? $_POST['description_package'] : '';
    $vehicle = isset($_POST['vehicle_package']) ? $_POST['vehicle_package'] : '';
    $hotel = isset($_POST['hotel_package']) ? $_POST['hotel_package'] : '';

    // Handle the image file upload
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    if ($image && is_uploaded_file($image['tmp_name'])) {
        $imageData = file_get_contents($image['tmp_name']);

        // Prepare and execute a statement to insert the image into the images table
        $insert_image_query = "INSERT INTO images (image_data) VALUES (?)";
        $stmt_image = mysqli_prepare($con, $insert_image_query);

        if ($stmt_image) {
            mysqli_stmt_bind_param($stmt_image, "s", $imageData);
            if (mysqli_stmt_execute($stmt_image)) {
                $image_id = mysqli_insert_id($con); // Get the image ID

                // Handle the file_package file upload
                $file_package = isset($_FILES['file_package']) ? $_FILES['file_package'] : null;
                $fileData = null;

                if ($file_package && is_uploaded_file($file_package['tmp_name'])) {
                    $fileData = file_get_contents($file_package['tmp_name']);

                    // Insert the file data into the files table
                    $insert_file_query = "INSERT INTO files (file_data) VALUES (?)";
                    $stmt_file = mysqli_prepare($con, $insert_file_query);

                    if ($stmt_file) {
                        mysqli_stmt_bind_param($stmt_file, "s", $fileData);
                        if (mysqli_stmt_execute($stmt_file)) {
                            $file_id = mysqli_insert_id($con); // Get the file ID

                            // Insert the package with the associated image and file
                            $insert_package_query = "INSERT INTO package (id_company, title_package, destination_package, description_package, image_id, file_id, vehicle_package, hotel_package) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_package = mysqli_prepare($con, $insert_package_query);

                            if ($stmt_package) {
                                mysqli_stmt_bind_param($stmt_package, "isssiiss", $_SESSION['id_company'], $title, $destination, $description, $image_id, $file_id, $vehicle, $hotel);

                                if (mysqli_stmt_execute($stmt_package)) {
                                    // Retrieve the last inserted package ID
                                    $id_package = mysqli_insert_id($con);

                                    // Insert sessions into the Sessions table
                                    if (isset($_POST['session_start_date']) && isset($_POST['session_end_date']) && isset($_POST['available_spots_adult']) && isset($_POST['available_spots_child']) && isset($_POST['price_package_adult']) && isset($_POST['price_package_child'])) {
                                        $session_start_dates = $_POST['session_start_date'];
                                        $session_end_dates = $_POST['session_end_date'];
                                        $available_spots_adult = $_POST['available_spots_adult'];
                                        $available_spots_child = $_POST['available_spots_child'];
                                        $price_package_adult = $_POST['price_package_adult'];
                                        $price_package_child = $_POST['price_package_child'];

                                        $insert_session_query = "INSERT INTO sessions (id_package, session_start_date, session_end_date, available_spots_adult, available_spots_child, price_package_adult, price_package_child) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                        $stmt_session = mysqli_prepare($con, $insert_session_query);

                                        if ($stmt_session) {
                                            for ($i = 0; $i < count($session_start_dates); $i++) {
                                                $start_date = $session_start_dates[$i];
                                                $end_date = $session_end_dates[$i];
                                                $spots_adult = $available_spots_adult[$i];
                                                $spots_child = $available_spots_child[$i];
                                                $price_adult = $price_package_adult[$i];
                                                $price_child = $price_package_child[$i];

                                                mysqli_stmt_bind_param($stmt_session, "isssiii", $id_package, $start_date, $end_date, $spots_adult, $spots_child, $price_adult, $price_child);
                                                mysqli_stmt_execute($stmt_session);
                                            }

                                            header("Location: main.php");
                                            exit;
                                        } else {
                                            echo "Error preparing statement for sessions: " . mysqli_error($con);
                                            exit;
                                        }
                                    }
                                } else {
                                    echo "Error inserting package: " . mysqli_error($con);
                                    exit;
                                }
                            } else {
                                echo "Error preparing statement for package: " . mysqli_error($con);
                                exit;
                            }
                        } else {
                            echo "Error inserting file: " . mysqli_error($con);
                            exit;
                        }
                    } else {
                        echo "Error preparing statement for file: " . mysqli_error($con);
                        exit;
                    }
                } else {
                    echo '<script>';
                    echo 'alert("You need to upload File package and condition");';
                    echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
                    echo '</script>';
                    exit;
                }
            } else {
                echo "Error inserting image: " . mysqli_error($con);
                exit;
            }
        } else {
            echo "Error preparing statement for image: " . mysqli_error($con);
            exit;
        }
    } else {
        echo '<script>';
            echo 'alert("You need to upload Image");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
