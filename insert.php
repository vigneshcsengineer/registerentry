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
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Check if there is an existing entry with a null outtime for the given register number and date
    $sql = "SELECT * FROM intime WHERE register_number = :registerNumber AND DATE(intime_date) = :date AND outtime IS NULL LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':registerNumber', $registerNumber);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $result = $stmt->fetch();

    // If an existing entry with a null outtime already exists, display an error message in a popup window
    if ($result) {
        echo "<script>alert('Attendance already marked for register number $registerNumber on $date')</script>";
        echo "<script>window.location.href='intime.html';</script>";
        exit;
    } else {
        // Prepare the SQL statement to insert the values into the intime table
        $sql = "INSERT INTO intime (register_number, email, intime_date, intime_time) VALUES (:registerNumber, :email, :intime_date, :intime_time)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':registerNumber', $registerNumber);
        $stmt->bindParam(':email', $email);

        // Combine the date and time inputs into a single datetime string
        $datetimeString = $date . ' ' . $time;

        // Convert the datetime string to a PHP DateTime object
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeString);

        // Format the datetime object in the required format for the database
        $isoDate = $datetime->format('Y-m-d');
        $isoTime = $datetime->format('H:i:s');

        // Bind the ISO date and time strings to the intime_date and intime_time parameters and execute the SQL statement
        $stmt->bindParam(':intime_date', $isoDate);
        $stmt->bindParam(':intime_time', $isoTime);
        $stmt->execute();

        // Display an alert message and redirect the user back to the form page
        echo "<script>alert('Entry has been successfully updated.')</script>";
        echo "<script>window.location.href='index.html';</script>";
        exit;
    }
}
?>
