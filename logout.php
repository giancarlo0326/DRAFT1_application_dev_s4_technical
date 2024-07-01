<?php
session_start();

if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    session_destroy(); // Destroy all session data
    header('Location: login.php'); // Redirect to login page
    exit;
} elseif (isset($_POST['confirm']) && $_POST['confirm'] == 'no') {
    header('Location: index.php'); // Redirect to home page
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Are you sure you want to log out?</p>
            <form action="logout.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <button type="submit" name="confirm" value="yes" class="btn btn-primary btn-block">Yes</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" name="confirm" value="no" class="btn btn-secondary btn-block">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>