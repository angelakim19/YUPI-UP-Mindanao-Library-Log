<?php
session_start();

// Check if necessary session variables are set
if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['studentnumber'])) {
    header("Location: register.php");
    exit();
}

$firstname = htmlspecialchars($_SESSION['firstname']);
$lastname = htmlspecialchars($_SESSION['lastname']);
$studentnumber = htmlspecialchars($_SESSION['studentnumber']);
$profilePicture = isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default_userp.png';

$servername = "localhost"; 
$username = "root"; 
$dbpassword = ""; // Use the correct password for the MySQL root user
$dbname = "registration_db";

// Create connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$stmt = $conn->prepare("SELECT id, firstname, middlename, lastname, email, college, program, phonenumber, position, profile_picture FROM users WHERE studentnumber = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $studentnumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
} else {
    die("User not found.");
}

// Fetch Lemito reservations
$sql_lemito_events = "SELECT event_date, event_name, event_time, person FROM lemito_events WHERE event_id = ?";
$stmt_lemito_events = $conn->prepare($sql_lemito_events);
$stmt_lemito_events->bind_param("i", $user_id);
$stmt_lemito_events->execute();
$result_lemito_events = $stmt_lemito_events->get_result();

$stmt->close();
$stmt_lemito_events->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #fbc130;
            background-image: url('YUPi Student Panel.png');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            overflow-x: hidden;
            overflow-y: auto;
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
            border: 2px solid rgb(91, 88, 88);
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

        .reservations-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 120px auto 40px;
            text-align: left;
            color: black;
        }

        .reservations-container h2 {
            font-family: 'Quiapo', sans-serif;
            color: #7a0422;
            font-size: 36px;
            text-align: center;
        }

        .reservations-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reservations-table th, .reservations-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .reservations-table th {
            background-color: #14533c;
            color: white;
        }

        .reservations-table td {
            background-color: #f9f9f9;
        }

        .reservations-container .print-button {
            background-color: #007b70;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Fredoka One', sans-serif;
            display: inline-block;
            margin-top: 20px;
        }

        .reservations-container .print-button:hover {
            background-color: #005f50;
        }

        .search-reservation {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px 0 0 5px;
            border: 1px solid #ccc;
            outline: none;
        }

        .search-bar button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-left: none;
            border-radius: 0 5px 5px 0;
            background-color: #ccc;
            cursor: pointer;
        }

        .log-button {
            background-color: #7a0422;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Fredoka One', sans-serif;
        }

        .log-button:hover {
            background-color: #5a0219;
        }
    </style>
</head>
<body>
    <div class="navigation-bar">
        <div class="logo">
            <img src="yupilogo.png" alt="YUPI Logo">
            <span class="logo-text">YUPI</span>
        </div>
        <nav>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex;">
                <li class="about-us"><a href="#" id="about-us-link">ABOUT US</a></li>
                <li><img src="default_userp.png" alt="User Photo" class="user-photo" id="user-photo"></li>
            </ul>
        </nav>
    </div>
    <div class="reservations-container">
        <h2>Lemito Reservations</h2>
        <div class="user-info" style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="default_userp.png" alt="User Photo" class="user-photo" style="border: 5px solid white; margin-right: 20px;">
            <div>
                <div style="font-size: 24px; font-weight: bold; color: black;"><?php echo $firstname . " " . $lastname; ?></div>
                <div style="font-size: 18px; color: black;"><?php echo $studentnumber; ?></div>
            </div>
        </div>
        <div class="search-reservation">
            <div class="search-bar">
                <input type="text" placeholder="Search..." id="search-input">
                <button onclick="searchRecords()">🔍</button>
            </div>
            <button class="log-button" onclick="window.location.href='studentPanel_log_ofRecords.php'">LOG RECORDS</button>
        </div>
        <table class="reservations-table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Person</th>

                </tr>
            </thead>
            <tbody id="reservations-body">
                <?php
                while ($row_lemito_events = $result_lemito_events->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row_lemito_events['event_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_lemito_events['event_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_lemito_events['event_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_lemito_events['person']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="print-button" onclick="window.print()">Print</button>
    </div>
    <script>
        function searchRecords() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#reservations-body tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(query));
                row.style.display = match ? '' : 'none';
            });
        }
    </script>
</body>
</html>
