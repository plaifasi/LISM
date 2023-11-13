<?php
session_start();
include('dbcon.php');

if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];
    
    if (isset($_GET['id_package'])) {
        $id_package = $_GET['id_package'];
    
    // Check if the user has already bookmarked this package
    $checkBookmarkQuery = "SELECT id_users FROM user_bookmarks WHERE id_users = ? AND id_package = ?";
    $stmt = mysqli_prepare($con, $checkBookmarkQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $id_users, $id_package);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                // The package is already bookmarked, so remove the bookmark
                $deleteBookmarkQuery = "DELETE FROM user_bookmarks WHERE id_users = ? AND id_package = ?";
                $stmt = mysqli_prepare($con, $deleteBookmarkQuery);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $id_users, $id_package);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>';
            echo 'alert("Bookmark removed successfully.");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
                    } else {
                        echo "Error removing bookmark: " . mysqli_error($con);
                    }
                }
            } else {
                // The package is not bookmarked, so add a bookmark
                $insertBookmarkQuery = "INSERT INTO user_bookmarks (id_users, id_package) VALUES (?, ?)";
                $stmt = mysqli_prepare($con, $insertBookmarkQuery);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $id_users, $id_package);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>';
            echo 'alert("Bookmark successfully.");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
                    } else {
                        echo "Error adding bookmark: " . mysqli_error($con);
                    }
                }
            }
        }
    }

    // Redirect back to the package or any other page after handling the bookmark request
    
} else {
    echo "Invalid request.";
}
}
?>
