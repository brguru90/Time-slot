<?php
date_default_timezone_set("Asia/Calcutta");
		$today=date("Y-m-d");
		//echo $today;
include('db.php');

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br />";
}
$conn->close();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
$sql = "create table allocated_time
(
name VARCHAR(20) NOT NULL,
address VARCHAR(20),
mob_no DECIMAL(15) NOT NULL,
dates date NOT NULL,
alc_time varchar(5),
time_expire varchar(5),
duration INT
);";
if ($conn->query($sql) === TRUE) {
    echo "table created successfully<br />";
	$conn->query("INSERT INTO allocated_time (name, address, mob_no, dates, alc_time, duration) VALUES ('guru', 'srb', '9482399078', '$today', '9', '30');");
	$conn->query("INSERT INTO allocated_time (name, address, mob_no, dates, alc_time, duration) VALUES ('maruthi', 'bdvt', '0000000000', '$today', '9:30', '60');");
	$conn->query("INSERT INTO allocated_time (name, address, mob_no, dates, alc_time, duration) VALUES ('harsha', 'shikaripura', '9999999999', '$today', '10:30', '30');");
}
	//else {echo "Error: " . $sql . "<br>" . $conn->error;}
if(isset($_GET['time']) && isset($_GET['date']) && $_GET['time']!="")
{
$time=$_GET['time'];
$sql="select * from allocated_time where alc_time='$time' and dates='$today'";
}
else
if(isset($_GET['date']) && $_GET['time']=="")
{
$today=$_GET['date'];
$sql="select * from allocated_time where dates='$today'";
}
else
{
	header('Location: '.'view.html');
}
$conn->query($sql);
$res=$conn->query($sql);
if ($res->num_rows > 0) 
{
	echo "<table border=1>
	<th>name</th><th>address</th><th>mobile number</th><th>Date</th><th>Time allcated</th><th>time expires</th><th>duration</th>";
	while($row = $res->fetch_assoc()) 
	{
		$name=$row['name'];
		$address=$row['address'];
		$mob_no=$row['mob_no'];
		$date=$row['dates'];
		$alc_time=$row['alc_time'];
		$time_expire=$row['time_expire'];
		$duration=$row['duration'];
		echo "<tr>
				<td>$name</td>
				<td>$address</td>
				<td>$mob_no</td>
				<td>$date</td>
				<td>$alc_time</td>
				<td>$time_expire</td>
				<td>$duration</td>
			</tr>";
	}
	echo "</table>";
}
else
{
	echo "<b>Not allocated</b><br />
	<a href='add.html'>click here to add</a>";		
}
?>