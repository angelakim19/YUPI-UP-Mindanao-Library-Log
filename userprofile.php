<?php
session_start();

// Check if user is logged in or just registered
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['studentnumber'])) {
    header("Location: register.php");
    exit();
}

$firstname = htmlspecialchars($_SESSION['firstname']);
$lastname = htmlspecialchars($_SESSION['lastname']);
$studentnumber = htmlspecialchars($_SESSION['studentnumber']);
$profilePicture = isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default_userp.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to YUPi University Library</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif; 
      background-image: url('YUPi_userprofile.png');
      background-size: cover;
      background-repeat: no-repeat;
      color: white;
      text-align: center;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .logo {
      display: flex;
      align-items: center;
    }
    
    .logo img {
      width: 80px;
      margin-left: 20px;
    }

    .logo-text {
      color: white;
      font-size: 24px;
      font-weight: bold;
    }

    .navigation-bar {
      background-color: #14533c;
      height: 80px;
      width: 100%;
      display: flex;
      justify-content: space-between; 
      align-items: center;
      position: fixed;
      top: 0;
      z-index: 1000;
    }

    .user-photo {
      width: 40px;
      height: 40px;
      border: 2px solid rgb(246, 241, 241); 
      border-radius: 50%; 
      margin-right: 30px; 
      cursor: pointer;
    }

    .about-us {
      display: flex;
      align-items: center;
      margin-left: 20px;
    }

    .about-us a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      margin-right: 20px;
      font-family: Arial, sans-serif;
      font-weight: bold; 
    }

    h1 {
      margin-top: 120px;
      font-size: 48px;
      font-weight: bold;
    }

    p {
      font-size: 24px;
      margin-top: 10px;
    }

    .profile-section {
      background-color: transparent;
      padding: 20px;
      border-radius: 0px;
      margin-top: 450px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 1500px;
      margin-left: auto;
      margin-right: auto;
      flex-direction: row-reverse; 
      position: relative; 
    }

    .profile-info-container {
      display: flex;
      align-items: center;
      width: 100%;
    }

    .profile-photo {
      width: 300px;
      height: 300px;
      border-radius: 50%;
      margin-right: 20px; 
      margin-left: 16px;
      margin-top: -140px;
      border: 5px solid whitesmoke; 
    }

    .profile-info {
      text-align: left;
      color: black;
    }

    .profile-info h2 {
      margin: 0;
      font-size: 24px;
      margin-top: -50px;
    }

    .profile-info p {
      margin: 5px 0 0;
      font-size: 18px;
    }

    .buttons-section {
      display: flex;
      justify-content: flex-start; 
      flex-wrap: wrap;
      width: 100%;
      margin-top: 0px;
      padding-left: 25%; 
    }

    .buttons-section button {
      background-color: #14533c;
      color: white;
      border: none;
      padding: 15px 100px;
      margin: 30px;
      border-radius: 25px;
      cursor: pointer;
      font-size: 18px;
      box-shadow: 0 4px #0e392b;
    }

    .buttons-section button:hover {
      background-color: #7a0422;
      transform: translateY(-2px); 
      box-shadow: 0 6px #0e392b; 
    }

    .buttons-section button:active {
      transform: translateY(2px); 
      box-shadow: 0 2px #0e392b; 
    }
    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }

  </style>
</head>

<body>
  <div class="navigation-bar">
    <div class="logo">
      <img src="yupilogo.png" alt="YUPi University Library Logo">
      <span class="logo-text">YUPI</span>
    </div>
    <nav>
      <ul style="list-style: none; padding: 0; margin: 0; display: flex;">
        <li class="about-us"><a href="aboutus.html" id="about-us-link">About Us</a></li>
        <li><img src="<?php echo $profilePicture; ?>" alt="User Photo" class="user-photo" id="user-photo"></li> 
      </ul>
    </nav>
  </div>

  <div class="clearfix">
    <div class="profile-section">
        <div class="profile-info-container">
            <img src="<?php echo $profilePicture; ?>" alt="User Photo" class="profile-photo">
            <div class="profile-info">
                <h2><?php echo $firstname . " " . $lastname; ?></h2>
                <p><?php echo $studentnumber; ?></p>
            </div>
        </div>
    </div>
  </div>

  <div class="buttons-section">
    <button onclick="window.location.href = 'profileinfo.php'">Profile</button>
    <button onclick="window.location.href = 'studentPanel_log_ofRecords.php'">Records of Log in</button>
    <button onclick="window.location.href = 'reserve_seat.php'">Library Places</button>
  </div>

  <script>
    // Add event listeners for navigation buttons
    function navigateTo(section) {
    switch (section) {
        case 'profile':
            window.location.href = 'profileinfo.html';
            break;
        case 'records':
            alert('Navigating to Records of Log in');
            // Add navigation logic here
            break;
        case 'library':
            alert('Navigating to Library Places');
            // Add navigation logic here
            break;
        default:
            alert('Unknown section');
    }
}

    // Fetch user data and update profile info
    document.addEventListener('DOMContentLoaded', function() {
      // Update profile info using PHP-generated JSON
      var userData = <?php echo json_encode($data); ?>;
      document.querySelector('.profile-info h2').textContent = userData.firstname + ' ' + userData.lastname;
      document.querySelector('.profile-info p').textContent = userData.studentnumber;
      document.querySelector('.profile-photo').src = userData.profilePicture;

      // Flash effect for user name and student number
      let nameElement = document.querySelector('.profile-info h2');
      let numberElement = document.querySelector('.profile-info p');
      let originalColor = nameElement.style.color;
      let flash = function(element) {
        element.style.transition = 'color 0.5s ease';
        element.style.color = '#ff0000';
        setTimeout(() => {
          element.style.color = originalColor;
        }, 500);
      }

      flash(nameElement);
      flash(numberElement);
    });
  </script>
</body>
</html>
