<?php
require_once 'database.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$welcome_message = $subject = $faculty = '';

// Retrieve current content from the database
try {
    $stmt = $db->prepare("SELECT * FROM homepage_content WHERE id = 1");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $welcome_message = $row['welcome_message'];
        $subject = $row['subject'];
        $faculty = $row['faculty'];
    } else {
        // Initialize with default values if no record found
        $welcome_message = "Welcome to our website!";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle form submission to update content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_welcome_message = $_POST['welcome_message'];
    $new_subject = $_POST['subject'];
    $new_faculty = $_POST['faculty'];

    try {
        $stmt = $db->prepare("UPDATE homepage_content SET welcome_message = ?, subject = ?, faculty = ? WHERE id = 1");
        $stmt->execute([$new_welcome_message, $new_subject, $new_faculty]);
        
        // Redirect with success message
        header("Location: edit.php?success=true");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page - PUIHAHA Videos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit This Home Page</h1>
        
        <!-- Form to edit content -->
        <form method="POST">
            <div class="mb-3">
                <label for="welcome_message" class="form-label">Welcome Message</label>
                <textarea class="form-control" id="welcome_message" name="welcome_message"><?php echo htmlspecialchars($welcome_message); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
            </div>
            <div class="mb-3">
                <label for="faculty" class="form-label">Faculty</label>
                <input type="text" class="form-control" id="faculty" name="faculty" value="<?php echo htmlspecialchars($faculty); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <!-- Optional: Display success message if redirected -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            <div class="alert alert-success mt-3" role="alert">
                Changes saved successfully!
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
