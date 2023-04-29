<!DOCTYPE html>
<html>
<head>
    <title>Register details of IOT laboratory</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
            color: #588c7e;
            font-family: monospace;
            font-size: 25px;
            text-align: left;
        }
        th {
            background-color: #588c7e;
            color: white;
        }
        tr:nth-child(even) {background-color: #f2f2f2}
    </style>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="search" placeholder="Search by Register Number">
        <input type="date" name="date_search">
        <input type="submit" value="Search">
    </form>
    <br>
    <table>
        <tr>
            <th>Register Number</th>
            <th>Email</th>
            <th>In Time Date</th>
            <th>In Time</th>
            <th>Out Time</th>
        </tr>
        <?php
        $conn = mysqli_connect("localhost","root","Msfconsole1$","attendance");
        if($conn-> connect_error){
            die("Connection failed:". $conn-> connect_error);
        }
        if(isset($_POST['search']) || isset($_POST['date_search'])){
            $search = isset($_POST['search']) ? $_POST['search'] : '';
            $date_search = isset($_POST['date_search']) ? $_POST['date_search'] : '';
            if (!empty($search) && !empty($date_search)) {
                $sql = "SELECT * FROM intime WHERE register_number LIKE '%".$search."%' AND intime_date = '".$date_search."'";
            } elseif (!empty($search)) {
                $sql = "SELECT * FROM intime WHERE register_number LIKE '%".$search."%'";
            } elseif (!empty($date_search)) {
                $sql = "SELECT * FROM intime WHERE intime_date = '".$date_search."'";
            } else {
                $sql = "SELECT * FROM intime";
            }
        } else {
            $sql = "SELECT * FROM intime";
        }
        $result = $conn-> query($sql);
        if ($result-> num_rows > 0){
            while ($row = $result-> fetch_assoc()){
                echo "<tr><td>". $row["register_number"] ."</td><td>". $row["email"] ."</td><td>". $row["intime_date"] ."</td><td>". $row["intime_time"] ."</td><td>". $row["outtime"] ."</td></tr>";
            }
            echo "</table>";
        }
        else{
            echo "0 result";
        }
        $conn-> close();
        ?>

        <form action="index.html" method="post">
            <input type="submit" value="Logout">
        </form>

    </table>
</body>
</html>
