<?php
// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'hotel_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the rooms table
$sql = "SELECT room_number, type, status, price FROM rooms";
$result = $conn->query($sql);
?>
<h2>Rooms</h2>
<table border="1">
    <thead>
        <tr>
            <th>Room Number</th>
            <th>Type</th>
            <th>Status</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['room_number']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['price']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No rooms available</td></tr>";
        }
        ?>
    </tbody>
</table>
