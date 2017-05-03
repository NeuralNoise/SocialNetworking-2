<?php 
	session_start();

	include('includes/connect.php');
	
	if( !isset( $_SESSION["username"] ) ) {
		
	} else {
		header("Location: home.php");
	}
?>
<?php 
	
	$reg = @$_POST['signup-submit'];
	$fname = "";
	$lname = "";
	$email = "";
	$emal2 = "";
	$password = "";
	$password2 = "";
	$username = "";
	
	//for testing
	$testinfo = "";

    $fname = strip_tags(@$_POST['signup-firstname']);
    $lname = strip_tags(@$_POST['signup-lastname']);
    $email = strip_tags(@$_POST['signup-email']);
    $email2 = strip_tags(@$_POST['signup-email2']);
    $password = strip_tags(@$_POST['signup-password']);
    $password2 = strip_tags(@$_POST['signup-password2']);
    $username = strip_tags(@$_POST['signup-username']);
		
	if( $reg ) {
		if( $fname && $lname && $email && $email2 && $password && $password2 && $username ) {
			if( !strcmp( $email, $email2 ) ) {
				if( $password == $password2 ) {
					
					// check either the username exists on the database
					
					$sql = "SELECT userName FROM `register` WHERE userName='$username'";
					$query = mysqli_query($con, $sql) or die('Couldn\'t check the username');
					
					if( mysqli_num_rows( $query ) == 0 ) {
						
						$password_md5 = md5( $password );
						
						$insert = mysqli_query($con, "INSERT INTO register (firstName, lastName, emailAddress, password, userName) VALUES ('$fname', '$lname', '$email', '$password_md5', '$username')")
						
						or
						
						die("Couldn't register your account due to some problems!");
						;
						
					} else {
						$testinfo = "Username Already taken";
					}
					
				} else {
					$testinfo = "Password Doesnot Match";
				}
			} else {
				$testinfo = "Email Doesnot match";
			}
		} else {
			$testinfo = "Fill Up all the fields";
		}
	}
	
	if( isset( $_POST['login-submit'] ) ) {
		$user_login = $_POST['login-username'];
		$password_login = $_POST['login-password'];
		
		if( $user_login && $password_login ) {
			
			//Look for the registered account
			
			$password_login_md5 = md5( $password_login );
			
			$sql_login = "SELECT * FROM register WHERE userName='$user_login' AND password='$password_login_md5' LIMIT 1";
			
			$query_login = mysqli_query($con, $sql_login);
			
			$row = mysqli_fetch_assoc( $query_login );
			
			if( mysqli_num_rows( $query_login ) == 1 ) {
				$_SESSION["username"] = $user_login;
				$_SESSION["firstname"] = $row['firstName'];
				$_SESSION["lastname"] = $row['lastName'];
				$_SESSION["userId"] = $row['userId'];
				header("Location: home.php");
			} 
			
			if( mysqli_num_rows( $query_login ) == 0 ) {
				$testinfo = "Not registered!";
			}
			
		} else {
			$testinfo = "First fill both username and password";
		}
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
            
            <div id="main-section">
            	
            	<!-- Login Form-->
                <div id="login-form">
				
                    <div id="login-form-header">Login to your account!</div>
					
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					
						
						<table>
							<tr>
								<td>
									Username:
								</td>
								<td>
									<input type="text" name="login-username" placeholder="Type Username ...">
								</td>
							</tr>
							
							<tr>
								<td>
									Password:
								</td>
								<td>
									<input type="text" name="login-password" placeholder="Type Password ...">
								</td>
							</tr>
							
							<tr>
								<td colspan="2" align="right">
									<input type="submit" name="login-submit" value="Log In">
								</td>
							</tr>
                        </table>
                    </form>
                </div>
                       
                       
                <!--Sign Up form--> 
                <div id="signup-form">
				
                	<div id="signup-form-header">If you are not a member! It's completely free.</div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    
                        <table>
                            <tr>
                                <td>
                                    First Name:
                                </td>
                                <td>
                                    <input type="text" name="signup-firstname" placeholder="Enter First Name">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Last Name:
                                </td>
                                <td>
                                    <input type="text" name="signup-lastname" placeholder="Enter Last Name">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    E-mail:
                                </td>
                                <td>
                                    <input type="text" name="signup-email" placeholder="Enter Email">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Re-E-mail:
                                </td>
                                <td>
                                    <input type="text" name="signup-email2" placeholder="Re-Enter Email">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Password:
                                </td>
                                <td>
                                    <input type="password" name="signup-password" placeholder="Choose a Password">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Re-Password:
                                </td>
                                <td>
                                    <input type="password" name="signup-password2" placeholder="Re-Enter Password">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Username:
                                </td>
                                <td>
                                    <input type="text" name="signup-username" placeholder="Choose a Username">
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2" align="right">
                                    <input type="submit" name="signup-submit" value="Sign Up">
                                </td>
                            </tr>
                        </table>
                        
                    </form>
               </div>
            

            </div>
			
			<div id="testInfo"><?php echo $testinfo; ?></div>
        </div>
    </body>
</html>