<?php 

session_start();

if (isset($_POST['logout'])) {
    require_once '../function/Model.php';
    $logout = new Model();
    $logout->logout();
}

if (isset($_POST['status'])) {
    ob_start();
    include('navigation/status.php');
    $content = ob_get_clean();
} else if (isset($_POST['result'])) {
    ob_start();
    include('navigation/result.php');
    $content = ob_get_clean();
} else if (isset($_POST['dashboard'])) {
    ob_start();
    include('navigation/dashboard.php');
    $content = ob_get_clean();
} else if (!isset($_POST['dashboard'])) {
    ob_start();
    include('navigation/dashboard.php'); // FOR DEFAULT
    $content = ob_get_clean();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../public/css/general.css">
    <link rel="stylesheet" href="../public/css/admin/admin.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- <title>CANDIDATES | STATUS</title> -->
</head>
<body>
    
    <!-- SIDEBAR NAVIGATION -->
    <nav>
        <form action="adminPanel.php" method="POST">
            <button type="submit" name="admin">
                <i class="fas fa-columns"></i> Dashboard
            </button>
            <button type="submit" name="status">
                <i class="fas fa-circle-notch"></i> Status
            </button>
            <button type="submit" name="result">
                <i class="fas fa-chart-line"></i> Result
            </button>
        </form>

        <form action="adminPanel.php" method="POST"> 
            <hr>
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </nav>

    <?php echo $content ?>
    
    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    <script src="../public/js/status.js"></script>
</body>
</html>