<?php include 'includes/header.php'; ?>	
			
<?php 
	echo "Hello ," . $firstname . " " . $lastname;
	echo "<br>Would you like to logout? <a href='logout.php'>Logout</a>";


	$folderName = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_/*^{}[].@#%&";
    $dirName = substr(str_shuffle($folderName),0,10);

    echo "<br>".$dirName;
?>

<?php include 'includes/footer.php'; ?>	