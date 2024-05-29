<?php
session_start();

if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['studentnumber'])) {
    header("Location: register.php");
    exit();
}

$firstname = htmlspecialchars($_SESSION['firstname']);
$lastname = htmlspecialchars($_SESSION['lastname']);
$studentnumber = htmlspecialchars($_SESSION['studentnumber']);
$profilePicture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default_userp.png';
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YUPi Registration Landing Page</title>
  <style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif; 
        background-image: url('YUPi_register.png');
        background-size: cover;
        background-repeat: no-repeat;
        margin-bottom: 100px;
    }

    .logo {
        display: flex;
        align-items: center;
    }
    
    .logo img {
        width: 80px;
        margin-left: -150px;
    }

    .logo-text {
        color: white;
        font-size: 24px;
        font-weight: bold;
        margin-left: 10px;
    }

    .navigation-bar {
        background-color: #14533c; 
        height: 80px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #navigation-container {
        width: 100%;
        max-width: 1200px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .container {
        width:max-content;
        margin-left: 20px;
        margin-right: 150px;
        background-color: #f8dd8aa1;
        padding: 100px;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        float: right;
        margin-top: 70px;
    }

    .profile {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile img {
        width: 200px;
        height: 200px;
        border-radius: 80%;
        border: 5px solid #070707;
        margin-bottom: 10px;
    }

    .profile h2 {
        margin: 0;
        margin-bottom: 10px;
        font-size: 24px;
        text-align: left;
        margin-left: -50px;
    }

    .student-number {
        font-size: 18px;
        text-align: left;
        margin-left: -50px;
    }

    .buttons {
        text-align: center;
        margin-top: 20px;
        display: flex;
        justify-content: space-around;
    }

    .buttons button {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        background-color: #08542a;
        color: #fff;
        transition: background-color 0.3s;
        flex: 1;
      margin-right: 10px;
    }
   
    .buttons button:last-child {
    margin-right: 0;
    }

    .buttons .skip:hover, .buttons .select:hover, .buttons .proceed:hover {
        background-color: #b11e2a;
    }

    .buttons .select, .buttons .proceed {
        width: 200px;
    }

    .form-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .form-group {
        flex: 1;
        margin-right: 10px;
    }

    .form-group:last-child {
        margin-right: 0;
    }

    .profile-picture-container {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .profile-picture-container input[type="file"] {
        display: none;
    }

    .choose-file-button {
        background-color: #08542a;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        padding: 10px 20px;
        margin-top: 10px;
    }

    .choose-file-button:hover {
        background-color: #b11e2a;
    }
  </style>
</head> 
<body>
  <div class="navigation-bar">
    <div id="navigation-container">
      <div class="logo">
        <img src="yupilogo.png" alt="YUPi Logo">
        <div class="logo-text">YUPi</div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="profile">
      <h2>Welcome, <?php echo $firstname . " " . $lastname; ?>!</h2>
      <p class="student-number">Student Number: <?php echo $studentnumber; ?></p>
      <div class="profile-picture-container">
        <img id="profilePicture" src="<?php echo $profilePicture; ?>" alt="Profile Picture">
        <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
          <input type="file" id="file-upload" name="profilePicture" accept="image/*" style="display: none;">
          <button type="button" class="choose-file-button" id="uploadButton">Upload</button>
          <button type="submit" class="select" id="submitButton" style="display: none;">Upload</button>
        </form>
      </div>
    </div>
    <div class="buttons">
      <button class="skip" onclick="window.location.href='welcomepage.html'">Skip</button>
      <button class="proceed" onclick="window.location.href='welcomepage.html'">Proceed</button>
    </div>
  </div>

  <script>
    const uploadButton = document.getElementById('uploadButton');
    const fileInput = document.getElementById('file-upload');
    const submitButton = document.getElementById('submitButton');

    uploadButton.addEventListener('click', function() {
      fileInput.click();
    });

    fileInput.addEventListener('change', function(event) {
      const reader = new FileReader();
      reader.onload = function() {
        document.getElementById('profilePicture').src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    });
  </script>
</body>
</html>
