<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "Msfconsole1$";
$dbname = "attendance";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values from the form
    $registerNumber = $_POST['register-number'];
    $date = $_POST['date'];

    // Prepare the SQL statement to update the outtime field in the intime table
    $sql = "UPDATE intime SET outtime = CURRENT_TIMESTAMP WHERE register_number = :registerNumber AND intime_date = :date AND outtime IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':registerNumber', $registerNumber);
    $stmt->bindParam(':date', $date);

    // Execute the SQL statement
    $stmt->execute();

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        echo '<script>alert("Outtime updated successfully.");window.location.href="index.html";</script>';
    } else {
        echo '<script>alert("No matching intime record found for the given register number and date.");window.location.href="outtime.html";</script>';
    }
}
?>
