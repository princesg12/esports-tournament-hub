<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->getConnection();

// Get the requested game ID from the URL (default to 1 if not set)
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 1;

// Fetch the game name for the header
$game_query = $conn->query("SELECT game_name FROM tournament_games WHERE game_id = $game_id");
$game_name = ($game_query->num_rows > 0) ? $game_query->fetch_assoc()['game_name'] : "Unknown Game";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboards - Esports Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .rank-1 { color: #ffd700; font-weight: bold; text-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
        .rank-2 { color: #c0c0c0; font-weight: bold; }
        .rank-3 { color: #cd7f32; font-weight: bold; }
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
                <li><a href="index.php">Dashboard Home</a></li>
                <br>
                <!-- Dynamic Leaderboard Links -->
                <li><a href="leaderboard.php?game_id=1" <?php if($game_id == 1) echo "class='highlight'"; ?>>Trackmania</a></li>
                <li><a href="leaderboard.php?game_id=2" <?php if($game_id == 2) echo "class='highlight'"; ?>>Mecha BREAK</a></li>
                <li><a href="leaderboard.php?game_id=3" <?php if($game_id == 3) echo "class='highlight'"; ?>>Blood Strike</a></li>
                <br>
                <li><a href="register.php">Register Player</a></li>
                <li><a href="feedback.php">Player Support</a></li>
                <li><a href="admin.php">Admin Login</a></li>
            </ul>
        </nav>

        <!-- 80% Content Area -->
        <section class="content">
            <h2>Live Leaderboard: <span style="color: var(--neon-red);"><?php echo htmlspecialchars($game_name); ?></span></h2>
            <p style="margin-bottom: 20px;">Top players ranked by total tournament points.</p>
            
            <table style="width: 100%; border-collapse: collapse; background-color: var(--panel-bg);">
                <tr style="background: rgba(255, 42, 95, 0.2); text-align: left;">
                    <th style="padding: 15px; border-bottom: 2px solid var(--primary-red);">Rank</th>
                    <th style="padding: 15px; border-bottom: 2px solid var(--primary-red);">Player Name</th>
                    <th style="padding: 15px; border-bottom: 2px solid var(--primary-red);">Total Score</th>
                </tr>
                
                <?php
                // Query to get total scores for players in the selected game
                $sql = "SELECT rp.player_name, SUM(ms.score) as total_score 
                        FROM registered_players rp 
                        JOIN match_scores ms ON rp.player_id = ms.player_id 
                        WHERE rp.game_id = ? 
                        GROUP BY rp.player_id 
                        ORDER BY total_score DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $game_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $rank = 1;
                    while($row = $result->fetch_assoc()) {
                        // Apply special styling for top 3 ranks
                        $rankClass = "";
                        if ($rank == 1) $rankClass = "rank-1";
                        elseif ($rank == 2) $rankClass = "rank-2";
                        elseif ($rank == 3) $rankClass = "rank-3";

                        echo "<tr style='border-bottom: 1px solid var(--border-color);'>";
                        echo "<td style='padding: 15px;' class='$rankClass'>#$rank</td>";
                        echo "<td style='padding: 15px; font-weight: 600;'>" . htmlspecialchars($row['player_name']) . "</td>";
                        echo "<td style='padding: 15px; color: var(--neon-green); font-weight: bold;'>" . number_format($row['total_score']) . " PTS</td>";
                        echo "</tr>";
                        $rank++;
                    }
                } else {
                    echo "<tr><td colspan='3' style='padding: 20px; text-align: center; color: #a0a0a0;'>No scores recorded for this bracket yet.</td></tr>";
                }
                $stmt->close();
                ?>
            </table>
        </section>
    </div>
</body>
</html>