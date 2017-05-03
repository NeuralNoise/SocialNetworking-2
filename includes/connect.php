<?php 
	$con = mysqli_connect("localhost","root","imatolymph") or die("Couldn't connect to the localhost");
	mysqli_select_db($con,"findfriends") or die("Cannot select the database");
?>