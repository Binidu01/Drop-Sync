<?php
include 'config.php'; // Make sure this file establishes a valid database connection ($conn)
session_start(); // Start the session

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Keep MD5() hashing

    // Query to retrieve user details (use prepared statements for security)
    $query = "SELECT * FROM `users` WHERE username = ? AND password = MD5(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $row['userid']; // Store user ID in session
        header('location: storage.php'); // Redirect to storage.php
        exit; // Important: Terminate script execution after redirection
    } else {
        $message[] = 'Incorrect username or password!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
    <link rel="icon" type="image/png" href="images/icon.png">
   <link rel="stylesheet" href="css/style.css">
   <script src="https://kit.fontawesome.com/5ee1250f7c.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data" >
      <h1>Login Now</h1>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
  ?>

      <input type="text" name="username" placeholder="Username" class="box" required> 
	  <div class="wrapper">
     <input type="password" name="password" placeholder="Password" id="password" class="box" required>
	    <span>
			<i class="fa fa-eye" aria-hidden="true" id="eye" onClick="toggle()"></i>
		</span>
		</div>
	<script>
		var state= false;
		function toggle(){
			if(state){
				document.getElementById("password").setAttribute("type","password");
				document.getElementById("eye").style.color='#7a797e';
				state = false;
		}
			else{
				document.getElementById("password").setAttribute("type","text");
				document.getElementById("eye").style.color='#5887ef';
				state = true;
			}
		}
	</script>
	  
	  <br><br><br><br>
      <button type="submit" name="submit"  class="btn"><i class="fa-solid fa-right-to-bracket"></i>&nbsp;&nbsp;Login Now</button>
     <br>
      <p>Don't have an Account? <a href="register.php" class='link'>Register Now</a></p>
      </form>
    </div>
  </body>
 </html>