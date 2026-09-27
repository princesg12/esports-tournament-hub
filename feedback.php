<?php
$display_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playerName = $_POST['player_name'];
    $feedback = $_POST['feedback'];
    
    // Format the log entry with a timestamp
    $logEntry = "Date: " . date("Y-m-d H:i:s") . " | Player: $playerName | Feedback: $feedback\n";
    
    // Open feedback.txt in 'append' mode, write the data, and close the file
    $file = fopen("feedback.txt", "a");
    if ($file) {
        fwrite($file, $logEntry);
        fclose($file);
        
        // Display the information back to the user
        $display_message = "<div style='background: #2e7d32; padding: 15px; margin-bottom: 20px; border-radius: 4px;'>
                            <h4 style='margin-bottom: 5px;'>Feedback Submitted Successfully!</h4>
                            <p><strong>Name:</strong> $playerName</p>
                            <p><strong>Message:</strong> $feedback</p>
                            </div>";
    } else {
        $display_message = "<div style='background: #f44336; padding: 15px; margin-bottom: 20px;'>Error opening file!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support - Esports Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { 
            width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; 
            background: #333; color: white; 
        }
        .btn { padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; color: white; }
        .btn-submit { background-color: #2e7d32; }
        .btn-submit:hover { background-color: #1b5e20; }
        .btn-reset { background-color: #555; margin-left: 10px; }
        .btn-reset:hover { background-color: #333; }
    </style>
</head>
<body>
    <header>
        <h1>Esports Tournament Hub</h1>
    </header>
    
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul>
                <li><a href="leaderboard.php?game_id=1">Trackmania</a></li>
                <li><a href="leaderboard.php?game_id=2">Mecha BREAK</a></li>
                <li><a href="leaderboard.php?game_id=3">Blood Strike</a></li>
                <li><a href="index.php">Dashboard Home</a></li>
                <br>
                <li><a href="register.php">Register Player</a></li>
                <li><a href="feedback.php" class="highlight">Player Support</a></li>
                <li><a href="admin.php">Admin Login</a></li>
            </ul>
        </nav>

        <!-- Content Area -->
        <section class="content">
            <h2>Player Support & Feedback</h2>
            <p style="margin-bottom: 20px;">Encountered a bug or want to dispute a match score? Let us know below.</p>
            
            <?php echo $display_message; ?>
            
            <form action="feedback.php" method="POST">
                <div class="form-group">
                    <label>Player Name:</label>
                    <input type="text" name="player_name" required>
                </div>
                
                <div class="form-group">
                    <label>Describe your issue or feedback:</label>
                    <textarea name="feedback" rows="5" required></textarea>
                </div>
                
                <!-- Submit and Reset Buttons -->
                <button type="submit" class="btn btn-submit">Submit Feedback</button>
                <button type="reset" class="btn btn-reset">Reset Form</button>
            </form>
        </section>
    </div>
</body>
</html>