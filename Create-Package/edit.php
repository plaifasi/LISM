<?php
session_start();
include('dbcon.php');
$main_css_file = "views/editstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

if (isset($_GET['id_package'])) {
    $id_package = $_GET['id_package']; // Get the ID of the item to edit from the URL

    // Retrieve the data to edit from the database based on the ID
    $edit_query = "SELECT * FROM package WHERE id_package = $id_package"; // Replace 'package' with your table name
    $edit_result = mysqli_query($con, $edit_query);

    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $row = mysqli_fetch_assoc($edit_result);

        // Check if the status_package is 'Pause' or 'Draft' before allowing editing
        if ($row['status_package'] === 'Pause' || $row['status_package'] === 'Draft') {
            // Display a form with the retrieved data for editing
            echo "<form method='post' action='update.php' enctype='multipart/form-data' class='form-container'>";
            echo "<input type='hidden' name='id' value='$id_package'>";
            echo "Title: <input type='text' name='title' value='" . $row['title_package'] . "'><br>";
            echo "Destination: <select name='destination_package'>";

            $destinations = array(
                "กรุงเทพมหานคร",
                "กระบี่",
                "กาญจนบุรี",
                "กาฬสินธุ์",
                "กำแพงเพชร",
                "ขอนแก่น",
                "จันทบุรี",
                "ฉะเชิงเทรา",
                "ชลบุรี",
                "ชัยนาท",
                "ชัยภูมิ",
                "ชุมพร",
                "เชียงราย",
                "เชียงใหม่",
                "ตรัง",
                "ตราด",
                "ตาก",
                "นครนายก",
                "นครปฐม",
                "นครพนม",
                "นครราชสีมา",
                "นครศรีธรรมราช",
                "นครสวรรค์",
                "นนทบุรี",
                "นราธิวาส",
                "น่าน",
                "บึงกาฬ",
                "บุรีรัมย์",
                "ปทุมธานี",
                "ประจวบคีรีขันธ์",
                "ปราจีนบุรี",
                "ปัตตานี",
                "พะเยา",
                "พระนครศรีอยุธยา",
                "พังงา",
                "พัทลุง",
                "พิจิตร",
                "พิษณุโลก",
                "เพชรบุรี",
                "เพชรบูรณ์",
                "แพร่",
                "ภูเก็ต",
                "มหาสารคาม",
                "มุกดาหาร",
                "แม่ฮ่องสอน",
                "ยโสธร",
                "ยะลา",
                "ร้อยเอ็ด",
                "ระนอง",
                "ระยอง",
                "ราชบุรี",
                "ลพบุรี",
                "ลำปาง",
                "ลำพูน",
                "เลย",
                "ศรีสะเกษ",
                "สกลนคร",
                "สงขลา",
                "สตูล",
                "สมุทรปราการ",
                "สมุทรสงคราม",
                "สมุทรสาคร",
                "สระแก้ว",
                "สระบุรี",
                "สิงห์บุรี",
                "สุโขทัย",
                "สุพรรณบุรี",
                "สุราษฎร์ธานี",
                "สุรินทร์",
                "หนองคาย",
                "หนองบัวลำภู",
                "อ่างทอง",
                "อำนาจเจริญ",
                "อุดรธานี",
                "อุตรดิตถ์",
                "อุทัยธานี",
                "อุบลราชธานี"
                
            );

            foreach ($destinations as $destination) {
                $selected = ($destination == $row['destination_package']) ? 'selected' : '';
                echo "<option value='$destination' $selected>$destination</option>";
            }

            echo "</select>";
            echo 'Description: <textarea rows="6" cols="60" name="description_package" id="description_package" required>' . $row['description_package'] . '</textarea><br>';
            echo "New Image: <input type='file' name='new_image'><br>";
            echo "New PDF File: <input type='file' name='new_file'><br>";

            // Add an input field to store the image_id
            echo "<input type='hidden' name='image_id' value='" . $row['image_id'] . "'>";
            echo "<input type='hidden' name='file_id' value='" . $row['file_id'] . "'>";

            echo "Vehicle: <select name='vehicle_package'>";
            $vehicle_package = array(
                "bus",
                "van",
                "airplane",
                "minibus",
                "boat",
            );

            foreach ($vehicle_package as $vehicle) {
                $selected = ($vehicle == $row['vehicle_package']) ? 'selected' : ''; // Compare the current value to the value in the database
                echo "<option value='$vehicle' $selected>$vehicle</option>";
            }

            echo "</select>";

            echo "Vehicle: <select name='hotel_package'>";
            $hotel_package = array(
                "onestar",
                "twostar",
                "treestar",
                "fourstar",
                "fivestar",
                "no stay"

            );

            foreach ($hotel_package as $vehicle) {
                $selected = ($vehicle == $row['hotel_package']) ? 'selected' : ''; // Compare the current value to the value in the database
                echo "<option value='$vehicle' $selected>$vehicle</option>";
            }

            echo "</select>";

            echo "<input type='submit' name='update' value='Update'>";
            echo "</form>";
        } else {
            echo '<script>';
            echo 'alert("Editing is only allowed for packages with \'Pause\' or \'Draft\' status.");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
        }
    } else {
        echo "Item not found.";
    }
} else {
    echo "Invalid request.";
}
?>
<?php
include('./includes/footer.php'); ?>