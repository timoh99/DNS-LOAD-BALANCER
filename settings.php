<?php
require 'auth.php';
require 'db.php';
requireAuth();

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['update_settings'])) {
        // In a real application, you would save these to database
        $message = "Settings updated successfully";
    }
}

// Get current settings (simulated)
$settings = [
    'health_check_interval' => 5,
    'log_retention' => 30,
    'timezone' => 'UTC',
    'email_notifications' => true,
    'server_down_alerts' => true
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>DNS Balancer - Settings</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Same sidebar as other pages -->
        
        <main class="main-content">
            <header class="header">
                <h1>System Settings</h1>
            </header>
            
            <div class="content">
                <?php if(isset($message)): ?>
                    <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                
                <div class="settings-tabs">
                    <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="general">General</button>
                        <button class="tab-btn" data-tab="notifications">Notifications</button>
                    </div>
                    
                    <div class="tab-content active" id="general-tab">
                        <form method="post">
                            <div class="form-group">
                                <label for="health-check-interval">Health Check Interval (minutes)</label>
                                <input type="number" id="health-check-interval" name="health_check_interval" 
                                       min="1" max="60" value="<?= $settings['health_check_interval'] ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="log-retention">Log Retention (days)</label>
                                <input type="number" id="log-retention" name="log_retention" 
                                       min="1" max="365" value="<?= $settings['log_retention'] ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="timezone">Timezone</label>
                                <select id="timezone" name="timezone">
                                    <option value="UTC" <?= $settings['timezone'] === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                    <option value="EST" <?= $settings['timezone'] === 'EST' ? 'selected' : '' ?>>Eastern Time (EST)</option>
                                    <option value="PST" <?= $settings['timezone'] === 'PST' ? 'selected' : '' ?>>Pacific Time (PST)</option>
                                </select>
                            </div>
                            <nav class="menu">
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="servers.php"><i class="fas fa-server"></i> Servers</a></li>
                   <li><a href="logs.php"><i class="fas fa-clipboard-list"></i> Logs</a></li>
                   <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </nav>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_settings" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="tab-content" id="notifications-tab">
                        <form method="post">
                            <div class="form-group checkbox-group">
                                <input type="checkbox" id="email-notifications" name="email_notifications" 
                                       <?= $settings['email_notifications'] ? 'checked' : '' ?>>
                                <label for="email-notifications">Enable Email Notifications</label>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <input type="checkbox" id="server-down-alerts" name="server_down_alerts" 
                                       <?= $settings['server_down_alerts'] ? 'checked' : '' ?>>
                                <label for="server-down-alerts">Server Down Alerts</label>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_settings" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons and contents
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(`${this.dataset.tab}-tab`).classList.add('active');
            });
        });
    </script>
</body>
</html>