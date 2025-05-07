<?php
require 'auth.php';
require 'db.php';
requireAuth();

// Filtering
$type = $_GET['type'] ?? 'all';
$status = $_GET['status'] ?? 'all';

$query = "SELECT l.*, s.name as server_name FROM logs l LEFT JOIN servers s ON l.server_id = s.id";
$params = [];

if($type !== 'all') {
    $query .= " WHERE l.type = ?";
    $params[] = $type;
}

if($status !== 'all') {
    $query .= (strpos($query, 'WHERE') === false ? " WHERE" : " AND");
    $query .= " l.status = ?";
    $params[] = $status;
}

$query .= " ORDER BY l.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute($params);
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>DNS Balancer - Logs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Same sidebar -->
        
        <main class="main-content">
            <header class="header">
                <h1>System Logs</h1>
            </header>
            
            <div class="content">
                <div class="log-filters">
                    <form method="get">
                        <select name="type">
                            <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All Types</option>
                            <option value="health_check" <?= $type === 'health_check' ? 'selected' : '' ?>>Health Checks</option>
                            <option value="system" <?= $type === 'system' ? 'selected' : '' ?>>System</option>
                        </select>
                        
                        <select name="status">
                            <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All Statuses</option>
                            <option value="healthy" <?= $status === 'healthy' ? 'selected' : '' ?>>Healthy</option>
                            <option value="unhealthy" <?= $status === 'unhealthy' ? 'selected' : '' ?>>Unhealthy</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>
                </div>
                
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Server</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($logs as $log): ?>
                        <tr>
                            <td><?= $log['created_at'] ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $log['type'])) ?></td>
                            <td><?= $log['server_name'] ?? 'System' ?></td>
                            <td><span class="status-badge status-<?= strtolower($log['status']) ?>"><?= $log['status'] ?></span></td>
                            <td><?= htmlspecialchars($log['message']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
<nav class="menu">
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="servers.php"><i class="fas fa-server"></i> Servers</a></li>
                   <li><a href="logs.php"><i class="fas fa-clipboard-list"></i> Logs</a></li>
                   <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </nav>
</html>