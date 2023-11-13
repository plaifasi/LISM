<?php
include('./authentication.php');
include('dbcon.php');

if (isset($_POST['search_destination']) || (isset($_POST['session_start_date']) && isset($_POST['session_end_date']))) {
    $search_destination = isset($_POST['search_destination']) ? $_POST['search_destination'] : null;
    $session_start_date = isset($_POST['session_start_date']) ? $_POST['session_start_date'] : null;
    $session_end_date = isset($_POST['session_end_date']) ? $_POST['session_end_date'] : null;

    // Construct the query dynamically based on the filled input fields
    $search_query = "SELECT DISTINCT p.* FROM package p
                    LEFT JOIN sessions s ON p.id_package = s.id_package
                    WHERE 1 = 1";

    $paramTypes = '';
    $params = array();

    if (!empty($search_destination)) {
        $search_query .= " AND p.destination_package = ?";
        $paramTypes .= 's';
        $params[] = $search_destination;
    }

    if (!empty($session_start_date) && !empty($session_end_date)) {
        $search_query .= " AND s.session_start_date >= ? AND s.session_end_date <= ?";
        $paramTypes .= 'ss';
        $params[] = $session_start_date;
        $params[] = $session_end_date;
    }

    // Include packages with 'Publish' status in the search
    $search_query .= " AND p.status_package = 'Publish'";

    $stmt_search = mysqli_prepare($con, $search_query);

    if ($stmt_search) {
        // Bind parameters based on the provided input
        if (!empty($paramTypes)) {
            $bindParams = array($stmt_search, $paramTypes);
            foreach ($params as $key => $param) {
                $bindParams[] = &$params[$key];
            }
            call_user_func_array('mysqli_stmt_bind_param', $bindParams);
        }

        if (mysqli_stmt_execute($stmt_search)) {
            $result = mysqli_stmt_get_result($stmt_search);

            if (mysqli_num_rows($result) > 0) {
                $search_results = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $search_results[] = $row;
                }

                mysqli_close($con);

                header("Location: displayuser.php?search_results=" . urlencode(serialize($search_results)));
                exit;
            } else {
                // No packages found, show an alert box
                echo '<script>';
                echo 'var confirmation = confirm("No packages found. Do you want to go back?");';
                echo 'if (confirmation) {';
                echo '   history.go(-1);';
                echo '}';
                echo '</script>';
            }
        } else {
            echo "Error executing the search query: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt_search);
    } else {
        echo "Error preparing statement for search: " . mysqli_error($con);
    }
} else {
    
    echo "Please provide a destination or a date range for the search.";
}

// Close the database connection
mysqli_close($con);
?>
