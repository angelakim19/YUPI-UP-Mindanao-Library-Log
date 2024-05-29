<?php
function uploadProfilePicture($file) {
    $upload_dir = 'uploads/';
    $uploaded_file = $upload_dir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploaded_file)) {
        return $uploaded_file;
    } else {
        throw new Exception("Failed to upload profile picture.");
    }
}
?>
