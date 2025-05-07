document.addEventListener('DOMContentLoaded', function() {
    // Modal handling for all pages
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
      const openButtons = document.querySelectorAll(`[data-target="#${modal.id}"]`);
      const closeButtons = modal.querySelectorAll('.close, .cancel-btn');
      
      openButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          modal.style.display = 'block';
        });
      });
      
      closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          modal.style.display = 'none';
        });
      });
      
      window.addEventListener('click', (e) => {
        if(e.target === modal) modal.style.display = 'none';
      });
    });
    
    // Filter handling for logs page
    if(document.getElementById('apply-filters')) {
      document.getElementById('apply-filters').addEventListener('click', function(e) {
        e.preventDefault();
        const type = document.getElementById('log-type').value;
        const status = document.getElementById('log-status').value;
        window.location.href = `logs.php?type=${type}&status=${status}`;
      });
    }
    
    // Health check simulation
    if(document.getElementById('check-health-btn')) {
      document.getElementById('check-health-btn').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        setTimeout(() => {
          this.innerHTML = '<i class="fas fa-heartbeat"></i> Check Health';
        }, 2000);
      });
    }
  });