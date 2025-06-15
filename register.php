<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateCode();
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'. $image;

    if ($pass!= $cpass) {
        $message[] = 'Confirm password not matched!';
    } elseif ($image_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $check_username = mysqli_query($conn, "SELECT * FROM users WHERE username = '$name'") or die('query failed');
        if (mysqli_num_rows($check_username) > 0) {
            $message[] = 'Username already exists!';
        } else {
            $insert = mysqli_query($conn, "INSERT INTO `users`(userid,username,email,password,profilepicture) VALUES('$id','$name', '$email', '$pass', '$image')") or die('query failed');
            if ($insert) {
                $folder_name = substr($id, 0);
                mkdir('storage/'. $folder_name, 0755, true);
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully!';
                session_start();
                $_SESSION['userid'] = $id;
                header('location:storage.php');
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}
function generateCode($current_id = '') {
    $digits = str_split('0123456789');

    if (!empty($current_id)) {
        $last_digits = substr($current_id, 1, 4);

        if ($last_digits == '9999') {
            if ($current_id < '9999') {
                $next_digits = str_pad((int)$last_digits + 1, 4, '0', STR_PAD_LEFT);
                $new_id = $next_digits;
            }
            else {
                return false;
            }
        }
        else {
            $next_digits = str_pad((int)$last_digits + 1, 4, '0', STR_PAD_LEFT);
            $new_id = $next_digits;
        }
    }
    else {
        $new_id = '0001';
    }
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $dbname = 'online storage';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$conn) {
        die('Could not connect: '. mysqli_error($conn));
    }

    $sql = "SELECT userid FROM users WHERE userid = '$new_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If the ID already exists, generate a new ID recursively
        $new_id = generateCode($new_id);
    }

    mysqli_close($conn);

    return $new_id;
}

// Generate a guest login
$current_id = '0000';
$id = generateCode($current_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style2.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/5ee1250f7c.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="form-container">
        <form action="" method="post" id="myForm" enctype="multipart/form-data">
            <h1>Register Now</h1>
           <?php
              if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">'. $message. '</div>';
                }
            }?>
                <div>
                    <center>
                    <div class="profile-pic">
                        <img src="images/default-avatar.png" alt="" title="Add a profile picture">
                        <input type="file" name="image" id="image" class="box" accept="image/jpg,image/jpeg, image/png">
                        <label for="image" id="upload-btn"><i class="fa fa-camera" title="Add a profile picture"></i></label>
                    </div>
                    </center>
                    <script>
                        $(document).ready(function () {
                            $('#image').change(function () {
                                var input = this;
                                var url = URL.createObjectURL(input.files[0]);
                                var img = $(input).siblings('img');
                                img.attr('src', url);
                            });
                        });
                    </script>
                </div>
            <?php   
            echo '<div class="id" title="This is your ID">'. $id. '</div>';
            ?>
            <input type="text" class="box" name="name" placeholder="Enter Username" required>
            <input type="email" name="email" id="email" placeholder="Enter Email" class="box" required>
            <input type="password" name="password" placeholder="Enter Password" class="box" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" class="box" required>
            <button type="submit" id="submit-button" class="btn"><i class="fa-solid fa-registered"></i>&nbsp;Register Now</button>
            <p>Already have an Account? <a href="login.php" class='link'>Login Now</a></p>
        </form>
    </div>
</body>
</html>