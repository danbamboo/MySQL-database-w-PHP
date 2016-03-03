<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database

if (file_exists("brett")){
  $dbhost = 'oniddb.cws.oregonstate.edu';
  $dbname = 'anderbre-db';
  $dbuser = 'anderbre-db';
  $dbpass = 'mkfCwxMsmsXjCDc7';
} else {
  $dbhost = 'oniddb.cws.oregonstate.edu';
  $dbname = 'mcmurroj-db';
  $dbuser = 'mcmurroj-db';
  $dbpass = 'uHM64jmm6DzuW1qr';
}

$mysqli = new mysqli($dbhost,$dbname,$dbpass,$dbuser);
if($mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project: Volleyball Database</title>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<html>
<body>

<h1>All about athletes</h1>
<p> Remember the following rules apply to athletes:</p>
<ul>
	<li>Athletes can only belong to one team.</li>
	<li>Athletes can have multiple positions.</li>
</ul>
<h3>Table 1: Athlete's Name and Team and Age </h3>

<div>
	<table>
		<tr>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Age </th>
			<th> Team Name </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, a.age, t.name FROM athletes a INNER JOIN teams t ON a.teamID = t.id")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $age, $tname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $age . "\n</td>\n<td>"  . $tname . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<h3>Table 2: All positions held by each athlete, ordered by team</h3>


<div>
	<table>
		<tr>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Team Name </th>
			<th> Position Type </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, t.name, p.type FROM athletes a Left JOIN athlete_position ap ON
ap.athleteID = a.id Left JOIN positions p ON
p.id = ap.positionID Left Join teams t ON a.teamID=t.id Order By t.name asc")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname,$tname, $type)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $tname . "\n</td>\n<td>\n" . $type . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br>
<h3>Update or Add Athlete</h3>
<p>*You may <b>update</b> athlete age and team.</p>
<p>**To manage positions, please go to the <a href="positions.php">positions</a> page.</p>
<form method="post" action="add_up_athlete.php">

		<fieldset>
			<legend>Athlete Name</legend>
			<p>First Name: <input type="text" name="first_name" /></p>
			<p>Last Name: <input type="text" name="last_name" /></p>
		</fieldset>

		<fieldset>
			<legend>Athlete Age</legend>
			<p>Age: <input type="text" name="age" /></p>
		</fieldset>

		<fieldset>
			<legend>Athlete's Team</legend>
			<select name="teamID">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM teams"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $teamname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $teamname . '</option>\n';
}
$stmt->close();
?>

		</select>
		</fieldset>
		<input type="submit" name="Add" value="Add Athlete" />
		<input type="submit" name="Update" value="Update Athlete" />
	</form>





</body>
</html>
