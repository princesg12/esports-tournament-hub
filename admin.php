<?php
session_start();
require_once 'Database.php';

$message = "";
$score_message = "";
$db = new Database();
$conn = $db->getConnection();

// 1. Handle Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $sql = "SELECT * FROM admin_users WHERE username='$username' AND password_hash='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin_user'] = $username;
    } else {
        $message = "<h4 style='color: #ff003c; margin-bottom: 15px;'>Invalid Username or Password.</h4>";
    }
}

// 2. Handle Score Assignment (Using Secure Prepared Statements)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_score'])) {
    $player_id = $_POST['player_id'];
    $score = $_POST['score'];

    // Prepare and bind to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO match_scores (player_id, score) VALUES (?, ?)");
    $stmt->bind_param("ii", $player_id, $score);
    
    if ($stmt->execute()) {
        $score_message = "<p style='color: var(--neon-green); margin-bottom: 10px;'>Score assigned successfully!</p>";
    } else {
        $score_message = "<p style='color: var(--neon-red); margin-bottom: 10px;'>Error assigning score.</p>";
    }
    $stmt->close();
}

// 3. Handle Logout Action
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal - Esports Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .admin-panel { background: var(--panel-bg); padding: 25px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 20px;}
        hr { border: 0; height: 1px; background: var(--border-color); margin: 20px 0; }
    </style>
</head>
<body>
    <header>
        <h1>Esports Tournament Hub</h1>
    </header>
    
    <div class="main-container">
        <!-- 20% Sidebar -->
        <nav class="sidebar">
            <ul>
                <li><a href="leaderboard.php?game_id=1">Trackmania</a></li>
                <li><a href="leaderboard.php?game_id=2">Mecha BREAK</a></li>
                <li><a href="leaderboard.php?game_id=3">Blood Strike</a></li>
                <li><a href="index.php">Dashboard Home</a></li>
                <br>
                <li><a href="register.php">Register Player</a></li>
                <li><a href="feedback.php">Player Support</a></li>
                <li><a href="admin.php" class="highlight">Admin Login</a></li>
            </ul>
        </nav>

        <!-- 80% Content Area -->
        <section class="content">
            
            <?php
            if (isset($_SESSION['admin_user'])) {
                // SECURE ADMIN DASHBOARD
                echo "<h2>Admin Control Panel</h2>";
                echo "<div class='admin-panel'>";
                echo "<p style='color: var(--neon-green); font-size: 1.2em; margin-bottom: 15px;'>Welcome back, <strong>" . $_SESSION['admin_user'] . "</strong>!</p>";
                echo "<p>Today's Date: <strong>" . date("d-m-Y") . "</strong></p>";
                echo "<br><a href='admin.php?action=logout' class='btn' style='background: var(--bg-dark); border: 1px solid var(--primary-red);'>Secure Logout</a>";
                echo "</div>";

                // SCORE ASSIGNMENT SECTION
                echo "<div class='admin-panel'>";
                echo "<h3>Assign Match Scores</h3>";
                echo "<hr>";
                echo $score_message;
                ?>
                
                <form action="admin.php" method="POST">
                    <div class="form-group">
                        <label for="player_id">Select Player:</label>
                        <select name="player_id" id="player_id" required>
                            <option value="">-- Choose a Registered Player --</option>
                            <?php
                            // Fetch players for the dropdown
                            $players_query = "SELECT player_id, player_name FROM registered_players ORDER BY player_name ASC";
                            $players_result = $conn->query($players_query);
                            if ($players_result->num_rows > 0) {
                                while($row = $players_result->fetch_assoc()) {
                                    echo "<option value='".$row['player_id']."'>".htmlspecialchars($row['player_name'])."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="score">Input Match Score:</label>
                        <input type="number" name="score" id="score" required>
                    </div>
                    
                    <button type="submit" name="assign_score" class="btn">Submit Score</button>
                </form>

                <?php
                echo "</div>";

            } else {
                // LOGIN FORM
                echo "<h2>Authorized Personnel Only</h2>";
                echo $message;
            ?>
                <form action="admin.php" method="POST" style="max-width: 400px; margin-top: 20px;">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    
                    <button type="submit" name="login" class="btn">Login to Dashboard</button>
                </form>
            <?php
            }
            ?>
            
        </section>
    </div>
</body>
</html>