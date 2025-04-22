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

$username = '';
$password = '';
$fieldsInvalid = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once '../function/Model.php';
    $auth = new Model();
    $auth->setDatabaseTable('accounts');
    $fieldsInvalid = $auth->authentication($username, $password);

    switch ($fieldsInvalid) {
        case 'Invalid Password.':
            $password = ''; // clear password only
            break;
        case 'Invalid Username and Password.':
            $username = '';
            $password = ''; // clear both
            break;
    }
}

if (isset($_POST['reset'])) {
    $username = '';
    $password = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>

    <!-- CSS LINKS -->
    <link rel="stylesheet" href="../public/css/general.css">
    <link rel="stylesheet" href="../public/css/login.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>LOGIN</h2>
        <form action="login.php" method="POST">

            <!-- USERNAME CONTAINER -->
            <div class="input-container">
                <label for="username">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo $username ?>">
            </div>

            <!-- PASSWORD CONTAINER -->
            <div class="input-container">
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" id="password" name="password" placeholder="Enter your password" value="<?php echo $password ?>">
            </div>

            <p class="error-message"><?php echo $fieldsInvalid == '' ? '' : $fieldsInvalid ?></p>

            <div class="button-container">
                <button type="submit" name="reset">
                    <i class="fas fa-rotate-left"></i>
                    RESET
                </button>
                <button type="submit" name="login">
                    <i class="fas fa-right-to-bracket"></i>
                    LOG IN
                </button>
            </div>
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