<?php
session_start();
include 'database.php';

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

$signup_error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username
    if (!preg_match('/^[a-zA-Z]{5,20}$/', $username)) {
        $signup_error = 'Username must be 5-20 letters.';
    }
    // Validate password
    elseif (strlen($password) < 8 || strlen($password) > 20 || !preg_match('/[0-9]/', $password)) {
        $signup_error = 'Password must be 8-20 characters long and contain at least one number.';
    }
    else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // Username does not exist, insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ss", $username, $hashed_password);
                    if ($stmt->execute()) {
                        $user_id = $stmt->insert_id; // Get the inserted user ID
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user_id; // Store user ID in session
                        header('Location: index.php');
                        exit;
                    } else {
                        $signup_error = 'Error creating account.';
                    }
                }
            } else {
                $signup_error = 'Username already taken.';
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Sign</b>Up</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Create your account</p>
            <p class="login-box-msg"><a href="login.php">or log in your account</a></p>

            <?php if ($signup_error != ''): ?>
            <p class="text-danger"><?= $signup_error ?></p>
            <?php endif; ?>

            <form action="signup.php" method="post" id="signupForm">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="showPassword">
                            <label for="showPassword">
                                Show Password
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script>
document.getElementById('signupForm').addEventListener('submit', function(event) {
    var username = document.querySelector('input[name="username"]').value;
    var password = document.querySelector('input[name="password"]').value;
    
    var usernamePattern = /^[a-zA-Z]{5,20}$/;
    var passwordPattern = /^(?=.*[0-9])[A-Za-z\d@$!%*?&]{8,20}$/; // Requires at least one number

    if (!usernamePattern.test(username)) {
        alert('Username must be 5-20 letters.');
        event.preventDefault();
    } else if (!passwordPattern.test(password)) {
        alert('Password must be 8-20 characters long and contain at least one number.');
        event.preventDefault();
    }
});

document.getElementById('showPassword').addEventListener('change', function() {
    var passwordInput = document.getElementById('password');
    if (this.checked) {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
});
</script>
</body>
</html>