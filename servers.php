<?php
require 'auth.php';
require 'db.php';
requireAuth();

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['delete'])) {
        $stmt = $db->prepare("DELETE FROM servers WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        
        // Log the action
        $db->prepare("INSERT INTO logs (type, message, status) VALUES (?, ?, ?)")
           ->execute(['system', "Server deleted: ID {$_POST['id']}", 'info']);
    } else {
        $stmt = $db->prepare("INSERT INTO servers (name, ip_address, port, weight) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['ip_address'],
            $_POST['port'],
            $_POST['weight']
        ]);
        
        // Log the action
        $db->prepare("INSERT INTO logs (type, message, status) VALUES (?, ?, ?)")
           ->execute(['system', "Server added: {$_POST['name']}", 'info']);
    }
}

// Get all servers
$servers = $db->query("SELECT * FROM servers")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>DNS Balancer - Servers</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Same sidebar as dashboard.php -->
        
        <main class="main-content">
            <header class="header">
                <h1>Server Management</h1>
                <button id="add-server-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Server
                </button>
            </header>
            
            <div class="content">
                <div class="server-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>Weight</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($servers as $server): ?>
                            <tr>
                                <td><?= htmlspecialchars($server['name']) ?></td>
                                <td><?= htmlspecialchars($server['ip_address']) ?></td>
                                <td><?= $server['port'] ?></td>
                                <td><?= $server['weight'] ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $server['id'] ?>">
                                        <button type="submit" name="delete" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Add Server Modal -->
                <div id="server-modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add New Server</h2>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Server Name</label>
                                    <input type="text" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>IP Address</label>
                                    <input type="text" name="ip_address" required>
                                </div>
                                <div class="form-group">
                                    <label>Port</label>
                                    <input type="number" name="port" required>
                                </div>
                                <div class="form-group">
                                    <label>Weight</label>
                                    <input type="number" name="weight" min="1" max="10" value="1">
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" id="cancel-btn">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Server</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <nav class="menu">
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="servers.php"><i class="fas fa-server"></i> Servers</a></li>
                   <li><a href="logs.php"><i class="fas fa-clipboard-list"></i> Logs</a></li>
                   <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </nav>

    <script src="scripts.js"></script>
    <script>
        // Modal handling
        const modal = document.getElementById('server-modal');
        const btn = document.getElementById('add-server-btn');
        const span = document.querySelector('.close');
        
        btn.onclick = () => modal.style.display = 'block';
        span.onclick = () => modal.style.display = 'none';
        document.getElementById('cancel-btn').onclick = () => modal.style.display = 'none';
        
        window.onclick = (event) => {
            if(event.target === modal) modal.style.display = 'none';
        }
    </script>
</body>
</html>