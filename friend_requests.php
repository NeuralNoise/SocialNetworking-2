
<?php include 'includes/header.php'; ?>

<?php 
	
	$friendRequest = mysqli_query($con,"SELECT * FROM friend_request WHERE touserId='$userId'");

	if(mysqli_num_rows($friendRequest) == 0) {
		echo '<div style="margin-top: 10px;font-weight: bold;">No Requests Yet!</div>';
	} else {

		?>
			<table style="width: 100%;">
				<tr>
					<th colspan="3">Friend Requests</th>
				</tr>
		<?php
		while($row = mysqli_fetch_assoc($friendRequest)) {
			?>
				<tr>
					<td>
						<?php 
							$fromuserId = $row['fromuserId'];
							$result = mysqli_query($con, "SELECT * FROM register WHERE userId='$fromuserId'");

							if(mysqli_num_rows($result) == 1) {
								$newrow = mysqli_fetch_assoc($result);

								$friendRequestFirstName = $newrow['firstName'];
								$friendRequestLastName = $newrow['lastName'];
								$friendRequestUsername = $newrow['userName'];
							}

						?>

						<?php 
			                $imageresult = mysqli_query($con,"SELECT * FROM profile_description WHERE userId = '$fromuserId'");

			                if(mysqli_num_rows($imageresult) == 0) {
			                    $imagename = "images/default.jpg";
			                } else {
			                    while($row = mysqli_fetch_assoc($imageresult)) {
			                        $imagename = $row['profile_image'];
			                    }
			                }
			            ?>

			           	<div style="width: 100%;">
                            <div style="width: 4%;float: left">
                                <img src="<?php echo $imagename; ?>" style="width: 50px;">
                            </div>

                            <div style="width: 96%;float: left">
                                <div class="friend_request_link">
                                    <a href="<?php echo $friendRequestUsername; ?>"><?php echo $friendRequestFirstName . " " . $friendRequestLastName; ?></a>
                                </div>

                                <form class="acceptRequestForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
									<input type="submit" name="acceptRequest" value="Accept Request">
								</form>

								<form class="deleteRequestForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
									<input type="submit" name="deleteRequest" value="Delete Request">
								</form>
                            </div>

                            <div style="clear: both;"></div>
                        </div>


					</td>
				</tr>
			<?php
		}

		?>
			</table>
		<?php
	}

?>

<?php include 'includes/footer.php'; ?>	