<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Sync</title>
    <link rel="icon" type="image/png" href="images\icon.png">
    <style>
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    background-color:#000;
}

header {
    background-color: #ccc;
    padding: 1em;
    text-align: center;
    height:120px;
}

section {
    flex: 1;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ccc;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    max-height: 400px; /* Set a maximum height to trigger the scrollbar */
}

footer {
    background-color: #ccc;
    color: #000;
    padding: 1em;
    text-align: center;
    height:120px;
}
        h1 {
            color: #fff;
            margin-top:-80px;
        }

        h2 {
            color: #657f99;
        }

        p {
            line-height: 1;
        }

        button a {
            color: #fff;
            text-decoration: none;
        }

        button {
            background-color: #3498db;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .link {
            color:  #007bff;
            text-decoration: none;
        }

        .link:hover {
            color:#5340ff;
        }

        /* Center the button */
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .logo {
    width: 200px;
    height: auto;
    margin-top:-50px;
}

      /* Additional media queries for mobile responsiveness */
@media only screen and (max-width: 768px) {
    section {
        max-width: 100%;
        padding: 10px;
        margin: 10px auto;
        max-height: none;
    }
       section p {
        font-size: 14px; /* Decrease font size to 14px */
    }
    section h2 {
        font-size: 16px; /* Decrease font size to 16px */
    }
    .logo {
        width: 150px;
        height: auto;
        margin-top: -30px;
    }
    button {
        padding: 8px 15px;
        font-size: 14px;
    }
    header {
        padding: 1em;
    }
    footer {
        padding: 1em;
    }

}

@media only screen and (max-width: 480px) {
    section {
        padding: 5px;
    }
    .logo {
        width: 100px;
        height: auto;
        margin-top: -20px;
    }
    button {
        padding: 6px 12px;
        font-size: 12px;
    }
    header {
        padding: 0.2em;
    }
    footer {
        padding: 0.2em;
    }
}
    </style>
</head>
<body>

<header>
    <img src ='images\dropsync.png' class='logo'>
    <h1></h1>
</header>

<section>
    <h2>About Drop Sync:</h2>
    <p>This allows you to securely store and organize your files. Each user has a unique folder where they can upload various file types, and even create additional folders to better organize their content.</p>

    <h2>How To Use:</h2>
    <p>To get started, you need to <a href="register.php" class='link'>register</a> or <a href="login.php" class='link'>login</a> to your account. Once logged in, you can upload files, create folders, and manage your stored content. The system provides an intuitive interface for a seamless experience.</p>

    <h2>Important Notes:</h2>
    <p>Please ensure your uploaded files adhere to our guidelines. Be mindful of file size limits, and only upload files with allowed extensions. Security is a top priority, and we recommend using strong passwords for your account.</p>

    <div class="button-container">
        <button><a href="login.php" >LOGIN</a></button>
</section>

<footer>
    <p>&copy; 2024 DropSync All Rigths Resered</p>
    <p>A Project From<a href='(link unavailable)' class='link'> Logic Nexus Lab</a></p>
</footer>

</body>
</html>