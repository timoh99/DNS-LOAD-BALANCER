<?php
require 'auth.php';
require 'db.php';
requireAuth();

// Handle health checks
if(isset($_POST['check_health'])) {
    $servers = $db->query("SELECT * FROM servers")->fetchAll(PDO::FETCH_ASSOC);
    foreach($servers as $server) {
        // Simulate health check
        $status = rand(0, 1) ? 'healthy' : 'unhealthy';
        $response_time = rand(1, 100);
        
        $stmt = $db->prepare("INSERT INTO health_checks (server_id, status, response_time) VALUES (?, ?, ?)");
        $stmt->execute([$server['id'], $status, $response_time]);
        
        // Update log
        $db->prepare("INSERT INTO logs (type, message, server_id, status) VALUES (?, ?, ?, ?)")
           ->execute(['health_check', "Health check for {$server['name']}", $server['id'], $status]);
    }
}


// Get stats
$total_servers = $db->query("SELECT COUNT(*) FROM servers")->fetchColumn();
$healthy_servers = $db->query("SELECT COUNT(*) FROM health_checks WHERE status = 'healthy' AND checked_at > NOW() - INTERVAL 5 MINUTE")->fetchColumn();
$avg_response = $db->query("SELECT AVG(response_time) FROM health_checks")->fetchColumn();
$logs = $db->query("SELECT l.*, s.name as server_name FROM logs l LEFT JOIN servers s ON l.server_id = s.id ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>DNS Balancer - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-balance-scale"></i>
                <span>DNS Balancer</span>
            </div>
            <nav class="menu">
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="servers.php"><i class="fas fa-server"></i> Servers</a></li>
                   <li><a href="logs.php"><i class="fas fa-clipboard-list"></i> Logs</a></li>
                   <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Dashboard</h1>
                <form method="post">
                    <button type="submit" name="check_health" class="btn btn-primary">
                        <i class="fas fa-heartbeat"></i> Check Health
                    </button>
                </form>
            </header>
            
            <div class="content">
                <div class="stats-cards">
                    <div class="card">
                        <div class="card-icon bg-blue">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Servers</h3>
                            <p><?= $total_servers ?></p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-icon bg-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-info">
                            <h3>Healthy</h3>
                            <p><?= $healthy_servers ?></p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-icon bg-orange">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-info">
                            <h3>Avg Response</h3>
                            <p><?= round($avg_response) ?> ms</p>
                        </div>
                    </div>
                </div>
                
                <div class="recent-activity">
                    <h2>Recent Activity</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Server</th>
                                <th>Status</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($logs as $log): ?>
                            <tr>
                                <td><?= $log['created_at'] ?></td>
                                <td><?= $log['server_name'] ?? 'System' ?></td>
                                <td><span class="status-badge status-<?= strtolower($log['status']) ?>"><?= $log['status'] ?></span></td>
                                <td><?= substr($log['message'], 0, 50) ?>...</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>