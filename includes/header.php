<?php session_start(); ?>

<?php 

    include('includes/connect.php');
    
    if( !isset( $_SESSION["username"] ) ) {
        header("location: Index.php");
    } else {
        $username = $_SESSION["username"];
        $firstname = $_SESSION["firstname"];
        $lastname = $_SESSION["lastname"];
        $userId = $_SESSION["userId"];
    }
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title>findFriends</title>
        
        <link rel="stylesheet" href="stylesheets/style.css">
        
        <script src="scripts/main.js"></script>
        
    </head>
    
    <body>
        <div id="wrapper">
            <div id="header">
                findFriends
            </div>
            
            <div id="navigation-bar">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="<?php echo $username; ?>">Profile</a></li>
                    <li><a href="message.php">Message</a></li>
                    <li><a href="friend_requests.php">Friend Requests</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
