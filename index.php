<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Esports Tournament Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Esports Tournament Hub</h1>
    </header>
    
    <div class="main-container">
        <!-- 20% Left Navigation Menu -->
        <nav class="sidebar">
            <ul>
                <li><a href="leaderboard.php?game_id=1">Trackmania</a></li>
                <li><a href="leaderboard.php?game_id=2">Mecha BREAK</a></li>
                <li><a href="leaderboard.php?game_id=3">Blood Strike</a></li>
                <br>
                <li><a href="register.php" class="highlight">Register Player</a></li>
                <li><a href="admin.php">Admin Login</a></li>
            </ul>
        </nav>

        <!-- 80% Right Content Area -->
        <section class="content">
            <h2>Welcome to the Arena</h2>
            <p>Select a game from the left to view live leaderboards, or register your details to join the next tournament bracket.</p>
            
            <div id="dynamic-display">
                <h3 style="margin-top: 30px; border-bottom: 2px solid #333; padding-bottom: 10px;">Live Registration Roster</h3>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background-color: #222;">
                    <tr style="background-color: #d32f2f; text-align: left;">
                        <th style="padding: 12px; border: 1px solid #444;">Player Name</th>
                        <th style="padding: 12px; border: 1px solid #444;">Game Bracket</th>
                        <th style="padding: 12px; border: 1px solid #444;">Timestamp</th>
                    </tr>
                    
                    <?php
                    // Establish database connection
                    require_once 'Database.php';
                    $db = new Database();
                    $conn = $db->getConnection();

                    // Query to join players with their respective game names
                    $sql = "SELECT rp.player_name, rp.registration_date, tg.game_name 
                            FROM registered_players rp 
                            JOIN tournament_games tg ON rp.game_id = tg.game_id 
                            ORDER BY rp.registration_date DESC LIMIT 10";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Loop through results and output table rows
                        while($row = $result->fetch_assoc()) {
                            echo "<tr style='border-bottom: 1px solid #444;'>";
                            echo "<td style='padding: 12px;'>" . htmlspecialchars($row['player_name']) . "</td>";
                            echo "<td style='padding: 12px; color: #4CAF50; font-weight: bold;'>" . $row['game_name'] . "</td>";
                            echo "<td style='padding: 12px; font-size: 0.9em; color: #aaa;'>" . $row['registration_date'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' style='padding: 15px; text-align: center;'>No players have entered the arena yet.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </section>
    </div>
</body>
</html>