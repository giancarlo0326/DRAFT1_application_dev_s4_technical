<?php
require_once 'database.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$welcome_message = "";
$subject = "";
$faculty = "";

// Function to fetch homepage content
function fetchHomePageContent($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM homepage_content WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch current content from database
$row = fetchHomePageContent($db);

if ($row) {
    $welcome_message = $row['welcome_message'];
    $subject = $row['subject'];
    $faculty = $row['faculty'];
} else {
    // Initialize with default values if no record found
    $welcome_message = "Welcome to our website!";
}

// Check for form submission to update content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_welcome_message = $_POST['welcome_message'];
    $new_subject = $_POST['subject'];
    $new_faculty = $_POST['faculty'];

    try {
        $stmt = $db->prepare("UPDATE homepage_content SET welcome_message = ?, subject = ?, faculty = ? WHERE id = 1");
        $stmt->execute([$new_welcome_message, $new_subject, $new_faculty]);
        
        // Redirect to index.php with success parameter and updated content
        header("Location: index.php?success=true");
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
    <title>Home - PUIHAHA Videos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-GVc61Aw9S4NbB32QZpjXe2z0Gzgpgj/YkKCP/pSvFKHlZ2s0s6P/h2NozH2BqljJx+3+2tJoHmxi6Cf2iG63KA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Additional styles specific to this page */
        .edit-section {
            display: none; /* Initially hide the edit section */
        }
    </style>
</head>
<body>
    <nav>
        <input type="checkbox" id="sidebar-active">
        <label for="sidebar-active" class="open-sidebar-button">
            <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
        </label>
        <label id="overlay" for="sidebar-active"></label>
        <div class="links-container">
            <label for="sidebar-active" class="close-sidebar-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
            </label>
            <a href="login.php">Sign In</a>
            <a href="signup.php">Sign Up</a>
            <a href="logout.php">Log Out</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="centered-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="greetings">
                                <h1>Edit This Home Page</h1>
                                
                                <!-- Display Content -->
                                <div class="content-section">
                                    <h5>Welcome Message:</h5>
                                    <p id="welcome_message_display"><?php echo htmlspecialchars($welcome_message); ?></p>
                                    <h5>Subject:</h5>
                                    <p id="subject_display"><?php echo htmlspecialchars($subject); ?></p>
                                    <h5>Faculty:</h5>
                                    <p id="faculty_display"><?php echo htmlspecialchars($faculty); ?></p>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn btn-primary mt-3" id="edit-button">Edit Content</button>
                                </div>
                                
                                <!-- Edit Form -->
                                <form class="edit-section mt-3" method="POST">
                                    <div class="form-group">
                                        <label for="welcome_message">Welcome Message</label>
                                        <textarea class="form-control" id="welcome_message" name="welcome_message"><?php echo htmlspecialchars($welcome_message); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="faculty">Faculty</label>
                                        <input type="text" class="form-control" id="faculty" name="faculty" value="<?php echo htmlspecialchars($faculty); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-danger ml-2" id="discard-button">Discard Changes</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="image-container">
                                <img src="https://wallpapers.com/images/hd/windows-default-background-ihuecjk2mhalw3nq.jpg" class="img-fluid" alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Script to toggle edit form visibility
        document.getElementById('edit-button').addEventListener('click', function() {
            document.querySelector('.content-section').style.display = 'none';
            document.querySelector('.edit-section').style.display = 'block';
        });

        // Script to toggle back to display content
        document.getElementById('discard-button').addEventListener('click', function() {
            document.querySelector('.content-section').style.display = 'block';
            document.querySelector('.edit-section').style.display = 'none';
        });
    </script>
</body>
</html>
