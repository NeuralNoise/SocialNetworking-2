<?php include 'includes/header.php'; ?>

<?php 
    if(isset($_GET['u'])) {
        $uname = $_GET['u'];

        $query = mysqli_query($con, "SELECT * FROM register WHERE userName = '$uname'");

        if(mysqli_num_rows($query) == 1) {
           
           $row = mysqli_fetch_assoc($query);

           $fname = $row['firstName'];
           $lname = $row['lastName'];
           $uId = $row['userId'];

           echo $fname . " " . $lname . " " . $uId . " -> " . $uname;  

        } else {
            die('User Not Registered!');
        }
    } else {
        die('Unable to find the user!');
    }
?>

<?php 

//for the bio of user
    if(isset($_POST['submitbio']) && $username==$uname) {
        $user_bio = $_POST['user_bio'];

        if($user_bio != "") {
            $sql = "UPDATE profile_description SET profile_bio='$user_bio' WHERE userId='$userId'";

            $query = mysqli_query($con,$sql);

            if(!$query) {
                die('Couldn\'t update your bio!');
            }

        } else {
            echo "Bio Empty!";
        }
    }
?>
<?php
// for the profile picture
if(isset($_POST['submitimage']) && $username==$uname) {
        $profileimage = $_FILES['profileimage']['name'];
        if($profileimage == "") {
            echo "Not Ok";
        } else {
            if(($_FILES['profileimage']['type'] == "image/jpeg") || ($_FILES['profileimage']['type'] == "image/png") || ($_FILES['profileimage']['type'] == "image/bmp")) {
                if($_FILES['profileimage']['size'] < 3145728) {

                    $folderName = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $dirName = substr(str_shuffle($folderName),0,15);

                    mkdir("profile/images/$dirName");
                    if(file_exists("profile/images/$dirName".$profileimage)) {
                        echo "File Already Exits";
                    } else {
                        move_uploaded_file($_FILES['profileimage']['tmp_name'], "profile/images/$dirName/".$profileimage);
                        
                        $query = mysqli_query($con, "INSERT INTO profile_description (profile_image,userId) VALUES ('profile/images/$dirName/$profileimage','$userId')");

                        if(!$query) {
                            die("Sorry, Couldn't upload your profile photo");
                        }
                    }

                } else {
                    echo "very Large image";
                }
            }
            else {
                echo "Invalid Image";
            }
        }
    }
?>

<?php 
    //for the post in timeline
    
    if(isset($_POST['submituserpost'])) {
        $userpost = $_POST['userpost'];
        $date = date('y-m-d');
        $likes = 0;
        $comments = 0;

        if($userpost && $username==$uname) {
            
            $query = mysqli_query($con, "INSERT INTO userpost VALUES (NULL,'$userpost','$date','$likes','$comments','$userId','$uId')");

            echo "Posted!";

        } else {
            echo "Empty field!";
        }
    }
    
?>

<?php 
    //for the post in others timeline
    
    if(isset($_POST['submituserpostto'])) {
        $userpost = $_POST['userpost'];
        $date = date('y-m-d');
        $likes = 0;
        $comments = 0;

        if($userpost && $username!=$uname) {
            
            $query = mysqli_query($con, "INSERT INTO userpost VALUES (NULL,'$userpost','$date','$likes','$comments','$userId','$uId')");

            echo "Posted!";

        } else {
            echo "Empty field!";
        }
    }
    
?>

<?php 
    //sending friend request 
    if(isset($_POST['sendrequest'])) {
        $result = mysqli_query($con, "SELECT * FROM friend_request WHERE fromuserId='$userId' AND touserId='$uId'");

        if(mysqli_num_rows($result) == 0) {
            $query = mysqli_query($con, "INSERT INTO friend_request VALUES (NULL,'$userId','$uId')");
        } else {
            echo "Request already sent!";
        }

    } 
?>

<div id="profile-section">
	<div id="left-profile-section">
    
    	<div id="profile-image">

            <?php 
                $imageresult = mysqli_query($con,"SELECT * FROM profile_description WHERE userId = '$uId'");

                if(mysqli_num_rows($imageresult) == 0) {
                    ?>
                        <img src="images/default.JPG" alt="<?php echo $fname .  "'s Image"; ?>" title="<?php echo $fname .  "'s Image"; ?>">
                    <?php 
                } else {
                    while($row = mysqli_fetch_assoc($imageresult)) {
                        $imagename = $row['profile_image'];
                    }

                     ?>
                        <img src="<?php echo "$imagename"; ?>" alt="<?php echo $fname .  "'s Image"; ?>" title="<?php echo $fname .  "'s Image"; ?>">
                    <?php 
                }
            ?>
                    	
            <?php 
                if($uname == $username) {
                    ?>
                        <form id="uploadimage" action="<?php echo $username; ?>" method="post" enctype="multipart/form-data">
                            <input id="profileimage" type="file" name="profileimage">
                            <label for="profileimage" title="Change your profile picture">Choose a File</label>
                            <input type="submit" name="submitimage" value="Change">
                            <div style="clear: both;"></div>
                        </form>
                    <?php
                } else {
                    ?>
                        <form id="requestuser" action="<?php echo $uname; ?>" method="post">
                            <?php 
                                //checking request is sent or not
                               
                                $result = mysqli_query($con, "SELECT * FROM friend_request WHERE fromuserId='$userId' AND touserId='$uId'");

                                if(mysqli_num_rows($result) == 0) {
                                    ?>
                                        <input type="submit" name="sendrequest" value="Send Request">
                                        <input style="cursor: not-allowed;" type="submit" name="cancelrequest" value="Cancel Request" disabled="on">
                                    <?php 
                                } else {
                                    ?>
                                        <input style="cursor: not-allowed;"type="submit" name="sendrequest" value="Request Sent!" disabled="on">
                                        <input type="submit" name="cancelrequest" value="Cancel Request">
                                    <?php 
                                }
                            ?>
                            <div style="clear: both;"></div>
                        </form>
                    <?php
                }
            ?>
            
        </div>
        
        <div id="user-bio">
        
        	<div id="user-bio-header">
                <?php echo $fname . "'s Bio"; ?>
                
                <?php
                    if($uname == $username) {
                        ?>
                            <span style="float: right;"><a href="profile.php?u=<?php echo $username . "&"; ?>edit=bioedit">Edit</a></span>
                        <?php
                    }
                ?>
                
            </div>
            
            <div id="user-bio-description">
               <?php 
                    $result = mysqli_query($con,"SELECT profile_bio FROM profile_description WHERE userId='$uId'");
                    $biography = "";

                    while($row = mysqli_fetch_assoc($result)) {
                        $biography = $row['profile_bio'];
                    }

                    if($biography == "") {
                        echo "User Bio is empty...";
                    } else {
                        echo $biography;
                    }
                ?>

                <?php 

                    if( isset($_GET['edit']) &&  $_GET['edit'] == "bioedit") {
                        ?> 
                        <form action="<?php echo $username; ?>" method="post">
                            <textarea name="user_bio" placeholder="Your Bio..."></textarea>
                            <input type="submit" name="submitbio" value="Update">
                            <div style="clear: both;"></div>
                        </form>
                        <?php 
                    }

                ?>
            </div>
        	
        </div>
    
    </div>

    <div id="right-profile-section">
        
        <div id="usertimeline">

            <?php 
                if($username == $uname) {
                    ?>
                        <div id="timelineheader">Your TimeLine Below: </div>

                        <form method="post" action="<?php echo $username; ?>">
                            <textarea name="userpost" placeholder="Write something to your timeline..."></textarea>
                            <input type="submit" name="submituserpost" value="POST">
                            <div style="clear: both;"></div>
                        </form>
                    <?php
                } else {
                    ?>
                        <div id="timelineheader"><?php echo $fname . "'s" ?> TimeLine Below: </div>

                        <form method="post" action="<?php echo $uname; ?>">
                            <textarea name="userpost" placeholder="Write something to his/her timeline..."></textarea>
                            <input type="submit" name="submituserpostto" value="POST">
                            <div style="clear: both;"></div>
                        </form>
                    <?php
                }
            ?>
            

            <div id="timelinedisplay">

                <div id="timelinedisplayheader">
                    Posts:  
                </div>

                <div id="timelinedisplayarea">

                    <!-- Each Post
                    <div class="eachposts">
                        <div class="eachpostsimage">
                            <img src="images/default.jpg" style="width: 50px;">
                        </div>

                        <div class="eachpostdata">
                            <div class="eachpostdatausername">
                                <a href="javascript: void(0);">Dipesh Rai</a>

                                <div>
                                    Likes: 0 | Comments: 0 <br>
                                    Posted on: 2017-02-5
                                </div>
                            </div>

                            <div class="eachpostdataposts">
                                Hello, This is my first post on my own timeline!
                            </div>
                        </div>

                        <div style="clear: both;"></div>
                    </div>
                    Each Post -->

                    <?php 
                        $result = mysqli_query($con, "SELECT * FROM userpost ORDER BY id DESC");
                        $count = 0;

                        if(mysqli_num_rows($result) == 0) {
                            //do nothing!
                        } else {
                            while($row = mysqli_fetch_assoc($result)) {
                                $userfrom = $row['userId'];
                                $userto = $row['touserId'];
                                $date = $row['date'];
                                $likes = $row['likes'];
                                $comments = $row['comments'];
                                $post = $row['post'];

                                if($userto == $uId) {
                                    $count++;
                                    $resultpost = mysqli_query($con, "SELECT firstName, lastName,userName FROM register WHERE userId='$userfrom'");

                                        if(mysqli_num_rows($resultpost) == 1) {
                                            $newrow = mysqli_fetch_assoc($resultpost);

                                            $userfromfirstname = $newrow['firstName'];
                                            $userfromlastname = $newrow['lastName'];
                                            $userfromusername = $newrow['userName'];
                                        }
                                    ?>

                                    <div class="eachposts">
                                        <div class="eachpostsimage">
                                        <?php 
                                            $resultimage = mysqli_query($con, "SELECT profile_image FROM profile_description WHERE userId='$userfrom'");

                                            if(mysqli_num_rows($resultimage) == 1) {
                                                $newrowimage = mysqli_fetch_assoc($resultimage);

                                                $profileimage = $newrowimage['profile_image'];
                                            } else {
                                                $profileimage = "images/default.jpg";
                                            }
                                        ?>

                                            <img src="<?php echo $profileimage; ?>" style="width: 50px;">
                                        </div>

                                        <div class="eachpostdata">
                                            <div class="eachpostdatausername">
                                                <a href="<?php echo $userfromusername; ?>"><?php echo $userfromfirstname . " " . $userfromlastname; ?></a>

                                                <div>
                                                    Likes: <?php echo $likes; ?> | Comments: <?php echo $comments; ?> <br>
                                                    Posted on: <?php echo $date; ?>
                                                </div>
                                            </div>

                                            <div class="eachpostdataposts">
                                                <?php echo $post; ?>
                                            </div>
                                        </div>

                                        <div style="clear: both;"></div>
                                    </div>
                                    <?php
                                }
                            }

                            if($count == 0) {
                                echo '<div>No Post Yet!</div>';
                            }
                        }
                    ?>

                </div>

            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
