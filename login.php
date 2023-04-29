<?php
// Start the session to store user data
session_start();

// Define database connection variables
$servername = "localhost";
$username = "root";
$password = "Msfconsole1$";
$dbname = "attendance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Get the username and password submitted from the form
	$username = $_POST["username"];
	$password = $_POST["password"];

	// Prepare a SQL statement to retrieve the user with the given username and password
	$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	// Check if a user was found with the given username and password
	if ($result->num_rows == 1) {

		// Set session variables to store the user data
		$_SESSION["username"] = $username;

		// Redirect to the dashboard page
		header("Location: details.php");
		exit();

	} else {
		// Display an error message if the username or password is incorrect
		echo "<p>Invalid username or password.</p>";
	}
}
?>
