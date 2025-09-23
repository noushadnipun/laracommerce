<link rel="stylesheet" href="{{asset('public/admin/plugins/toastr/toastr.min.css')}}">

<script src="{{asset('public/admin/plugins/toastr/toastr.min.js')}}"></script>

<!-- Elegant Notification System -->
<style>
    .elegant-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        min-width: 300px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .elegant-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .elegant-notification.success {
        border-left: 4px solid #28a745;
        background: linear-gradient(135deg, #ffffff, #f8fff9);
    }
    
    .elegant-notification.error {
        border-left: 4px solid #dc3545;
        background: linear-gradient(135deg, #ffffff, #fff8f8);
    }
    
    .elegant-notification.warning {
        border-left: 4px solid #ffc107;
        background: linear-gradient(135deg, #ffffff, #fffef8);
    }
    
    .elegant-notification.info {
        border-left: 4px solid #17a2b8;
        background: linear-gradient(135deg, #ffffff, #f8fcff);
    }
    
    .notification-header {
        display: flex;
        align-items: center;
        padding: 15px 20px 10px;
        gap: 12px;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }
    
    .notification-icon.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .notification-icon.error {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .notification-icon.warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
    }
    
    .notification-icon.info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 4px 0;
        line-height: 1.3;
    }
    
    .notification-message {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        line-height: 1.4;
    }
    
    .notification-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        border: none;
        background: rgba(0,0,0,0.1);
        border-radius: 50%;
        color: #6c757d;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .notification-close:hover {
        background: rgba(0,0,0,0.2);
        color: #2c3e50;
        transform: scale(1.1);
    }
    
    .notification-progress {
        height: 3px;
        background: rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .notification-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        width: 100%;
        transform: translateX(-100%);
        transition: transform 0.1s linear;
    }
    
    .notification-progress.error .notification-progress-bar {
        background: linear-gradient(90deg, #dc3545, #c82333);
    }
    
    .notification-progress.warning .notification-progress-bar {
        background: linear-gradient(90deg, #ffc107, #e0a800);
    }
    
    .notification-progress.info .notification-progress-bar {
        background: linear-gradient(90deg, #17a2b8, #138496);
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .elegant-notification {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
            min-width: auto;
        }
    }
    
    /* Animation for multiple notifications */
    .elegant-notification:nth-child(2) { top: 90px; }
    .elegant-notification:nth-child(3) { top: 160px; }
    .elegant-notification:nth-child(4) { top: 230px; }
    .elegant-notification:nth-child(5) { top: 300px; }
</style>

<!-- Notification Container -->
<div id="elegant-notification-container"></div>

<script>
    // Elegant Notification System
    window.ElegantNotification = {
        show: function(message, type = 'info', duration = 4000) {
            const container = document.getElementById('elegant-notification-container');
            const notification = document.createElement('div');
            const notificationId = 'notification-' + Date.now();
            
            // Set notification type
            notification.className = `elegant-notification ${type}`;
            notification.id = notificationId;
            
            // Icons for different types
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };
            
            // Create notification HTML
            notification.innerHTML = `
                <button class="notification-close" onclick="ElegantNotification.hide('${notificationId}')">×</button>
                <div class="notification-header">
                    <div class="notification-icon ${type}">
                        ${icons[type] || icons.info}
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${this.getTitle(type)}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                </div>
                <div class="notification-progress ${type}">
                    <div class="notification-progress-bar" id="progress-${notificationId}"></div>
                </div>
            `;
            
            // Add to container
            container.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Start progress bar
            const progressBar = document.getElementById(`progress-${notificationId}`);
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 1;
                progressBar.style.transform = `translateX(-${100 - progress}%)`;
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                    this.hide(notificationId);
                }
            }, duration / 100);
            
            // Auto hide after duration
            setTimeout(() => {
                this.hide(notificationId);
            }, duration);
            
            return notificationId;
        },
        
        hide: function(notificationId) {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 400);
            }
        },
        
        getTitle: function(type) {
            const titles = {
                success: 'Success!',
                error: 'Error!',
                warning: 'Warning!',
                info: 'Info'
            };
            return titles[type] || titles.info;
        },
        
        // Convenience methods
        success: function(message, duration) {
            return this.show(message, 'success', duration);
        },
        
        error: function(message, duration) {
            return this.show(message, 'error', duration);
        },
        
        warning: function(message, duration) {
            return this.show(message, 'warning', duration);
        },
        
        info: function(message, duration) {
            return this.show(message, 'info', duration);
        }
    };
    
    // Replace default alert function
    window.alert = function(message) {
        ElegantNotification.info(message, 3000);
    };
</script>

@if(Session::get('success') == true)
    <script>
        toastr.success("{{ Session::get('success') }}")
    </script>

@endif

@if(Session::get('delete') == true)
    <script>
        toastr.error("{{ Session::get('delete') }}")
    </script>
@endif


@if(Session::get('error') == true)
    <script>
        toastr.error("{{ Session::get('delete') }}")
    </script>
@endif

@if($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error("{{ $error }}")
        </script>
    @endforeach
@endif