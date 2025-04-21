<?php

// require_once '../config/config.php';

session_start();

if (isset($_SESSION['id'])) {
    echo $_SESSION['name'];
    echo $_SESSION['role'];
    
    switch ($_SESSION['role']) {
        case "Admin":
            header("Location: ../admin/adminPanel.php");
            exit;
        case "User":
            header("Location: ../client/standard.php");
            exit;
    }
}

$fieldsInvalid = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once '../function/Model.php';
    $auth = new Model();
    $auth->setDatabaseTable('accounts');
    $fieldsInvalid = $auth->authentication($username, $password);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    
    <link rel="stylesheet" href="../design/assets/login.css">
</head>
<body>
    <div class="container">
        <h2>LOGIN</h2>
        <form action="login.php" method="POST">
            <div class="input-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username">
            </div>

            <div class="input-container">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <p class="error-message"><?php echo $fieldsInvalid == '' ? '' : $fieldsInvalid ?></p>

            <button type="submit" name="login">LOGIN</button>
        </form>
    </div>

    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>