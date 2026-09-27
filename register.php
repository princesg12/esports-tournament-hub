<?php
// 1. Database Connection (PHP & MySQL)
require_once 'Database.php';
$db = new Database();
$conn = $db->getConnection();
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['player_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile_no'];
    $game_id = $_POST['game_id'];

    // Insert data into the registered_players table
    $sql = "INSERT INTO registered_players (player_name, email, mobile_no, game_id) 
            VALUES ('$name', '$email', '$mobile', '$game_id')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "<h3 style='color: #4CAF50; margin-bottom: 15px;'>Player Registered Successfully!</h3>";
    } else {
        $message = "<h3 style='color: #f44336; margin-bottom: 15px;'>Error: " . $conn->error . "</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Esports Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Form Specific CSS */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { 
            width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; 
            background: #333; color: white; 
        }
        .btn { 
            background-color: #2e7d32; color: white; padding: 10px 20px; 
            border: none; cursor: pointer; border-radius: 4px; font-size: 16px;
        }
        .btn:hover { background-color: #1b5e20; }
        #summaryBox { 
            width: 100%; margin-top: 15px; background: #222; 
            color: #4CAF50; border: 1px dashed #555; padding: 10px;
        }
    </style>
    
    <!-- 3. Client-Side Validation (JavaScript) -->
    <script>
        function validateForm(event) {
            let name = document.forms["regForm"]["player_name"].value;
            let email = document.forms["regForm"]["email"].value;
            let mobile = document.forms["regForm"]["mobile_no"].value;
            let summaryBox = document.getElementById("summaryBox");
            
            // Validation check
            if (name === "" || email === "" || mobile === "") {
                alert("All fields must be filled out!");
                event.preventDefault(); // Stop form submission
                return false;
            }
            
            // Combine and display information in the textbox before server processing
            summaryBox.value = "Processing Registration for: " + name + " | Contact: " + mobile;
            return true;
        }
    </script>
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
                <li><a href="register.php" class="highlight">Register Player</a></li>
                <li><a href="admin.php">Admin Login</a></li>
            </ul>
        </nav>

        <!-- 80% Content Area -->
        <section class="content">
            <h2>Tournament Registration</h2>
            
            <!-- Output Success/Fail Message here -->
            <?php echo $message; ?>
            
            <form name="regForm" action="register.php" method="POST" onsubmit="return validateForm(event)">
                <div class="form-group">
                    <label>Player Name:</label>
                    <input type="text" name="player_name">
                </div>
                
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email">
                </div>
                
                <div class="form-group">
                    <label>Mobile Number:</label>
                    <input type="text" name="mobile_no">
                </div>
                
                <div class="form-group">
                    <label>Select Game Bracket:</label>
                    <select name="game_id">
                        <?php
                        // Fetch games dynamically from MySQL for the dropdown
                        $games_query = "SELECT * FROM tournament_games";
                        $games_result = $conn->query($games_query);
                        
                        if ($games_result->num_rows > 0) {
                            while($row = $games_result->fetch_assoc()) {
                                echo "<option value='".$row['game_id']."'>".$row['game_name']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="btn">Confirm Registration</button>
                
                <!-- Display summary box for JS output -->
                <textarea id="summaryBox" rows="2" readonly placeholder="JavaScript validation summary will appear here..."></textarea>
            </form>
        </section>
    </div>
</body>
</html>