<?php
include 'db.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['firstname']}</td>
                <td>{$row['middlename']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['studentnumber']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phonenumber']}</td>
                <td>{$row['position']}</td>
                <td>{$row['college']}</td>
                <td>{$row['program']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='no-records'>No records found</td></tr>";
}

$conn->close();
?>
