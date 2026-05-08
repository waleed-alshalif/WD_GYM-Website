<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "WD_GYM"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['upload_data'])) {
    // Retrieve and sanitize form data
    $name = trim(mysqli_real_escape_string($conn, $_POST['name_player']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email_player']));
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phone_player']));
    $message = trim(mysqli_real_escape_string($conn, $_POST['message_player']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo "<p style='color: red;'>All fields are required!</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Invalid email format!</p>";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO players (player_name, player_email, player_phone, player_message) 
        VALUES ($name, $email, $phone, $message)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $phone, $message);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Data submitted successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>";
        }
    }
}

// Close the connection
$conn->close();
?>
