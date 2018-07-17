<?php
date_default_timezone_set("Asia/Calcutta");
		$datee=date("Y-m-d"); 
		//echo $datee;
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
}
	//else {echo "Error: " . $sql . "<br>" . $conn->error;}
if(isset($_POST['time']) && isset($_POST['date']) && isset($_POST['name']) && isset($_POST['address']) && isset($_POST['mobile_no']) && isset($_POST['duration']) && isset($_POST['tm']))
{
$time=$_POST['time'];
$datee=$_POST['date'];
$name=$_POST['name'];
$address=$_POST['address'];
$mobile_no=$_POST['mobile_no'];
$duration=$_POST['duration'];
$tm=$_POST['tm'];
}
else
{
	echo "<script>alert('invalid input');history.go(-1);</script>";
	//header('Location: '.'add.html');
}
if($duration>120)
{
	echo "<script>alert('the time duration is larger than expected');history.go(-1);</script>";
}

//-----------------------------------------------function find equality between time----------------------------------------
function compare($tim1,$tim2)
{
	$tt1=explode(':',$tim1);
	$tt2=explode(':',$tim2);
	$h1=$tt1[0];
	$m1=$tt1[1];
	$h2=$tt2[0];
	$m2=$tt2[1];
	$nn=0;
	if($h1>$h2)
	{
		$nn=0;
	}
	else
	if($h1==$h2)
	{
		if($m1>$m2)
		{
			$nn=0;
		}
		else
		if($m1==$m2)
		{
			$nn=2;
		}
		else
		{
			$nn=1;
		}
	}
	else
	{
			$nn=1;
	}
	/*
	<------------------->
		returns if,
	 *	t1>t2 =>0
	 *	t1<t2 =>1
	 *	t1=t2 =>2
	<------------------->
	*/
	return $nn;
}
//-----------------------------------------------end of function----------------------------------------------


//--------------------------------------converting into 24hrs clock-----------------------------------------
$tt=explode(':',$time);
if($tm=='pm')
{
	$tt[0]=$tt[0]+12;
}
$time=implode(':',$tt);
if(compare($time,"17:30")==0 || compare($time,"9:30")==1)
{
	echo "<script>alert('this is not a working time');history.go(-1);</script>";
}
if(compare($time,"13:00")==0 || compare($time,"14:00")==1)
{
	echo "<script>alert('this is time for mill');history.go(-1);</script>";
}
$duration2=$duration;

//-------------------------calculating expired time on the basis of duration minutes-------------------------
$k=0;
while($duration2>=60)
{
	$duration2=$duration2-60;
	$k++;
}
$expire="$k:$duration2";
$t1=explode(':',$time);
$t2=explode(':',$expire);
$t0[0]=$t1[0]+$t2[0];
$t0[1]=$t1[1]+$t2[1];
$l=0;
while($t0[1]>=60)
{
	$t0[1]=$t0[1]-60;
	$l++;
}
$t0[0]=$t0[0]+$l;
$exp=implode(':',$t0);

//---------------------------------------check availability of appointment time----------------------------------------------
$flag=0;
$sql="select * from allocated_time";
$conn->query($sql);
$res=$conn->query($sql);
if ($res->num_rows > 0) 
{
	while($row = $res->fetch_assoc()) 
	{
		//check whether start time in between the appointment time
		if((compare($time,$row['alc_time'])==0 || compare($time,$row['alc_time'])==2) && (compare($time,$row['time_expire'])==1))
		{
			$flag++;//means aleready alerted
		}
		//check whether end time in between the appointment time
		if((compare($exp,$row['alc_time'])==0) && (compare($exp,$row['time_expire'])==1 || compare($exp,$row['time_expire'])==2))
		{
			$flag++;//means aleready alerted
		}
	}
}
/*
 <----------------------------------------------------->
 *	if flag>1 then there is an appointment
 *	else if the flag is 0 then there is no appointment
 <----------------------------------------------------->
*/
if ($flag==0) 
{
	$sql="INSERT INTO allocated_time (name, address, mob_no, dates, alc_time, time_expire, duration) VALUES ('$name', '$address', '$mobile_no', '$datee', '$time','$exp', '$duration');";
	if ($conn->query($sql) === TRUE)
	{
		echo "<script>alert('appointed');history.go(-1);</script><br />";
	}
	else
	{
		echo "<script>alert('probleom occured!!! please check your input...');history.go(-1);</script><br />";
	}
	
}
else
{
    echo "<script>alert('aleready appointed');history.go(-1);</script><br />";
}
?>