import './bootstrap';

// Konfirmasi hapus
document.addEventListener('DOMContentLoaded', () => {

    // Auto hide alert setelah 4 detik
    const alerts = document.querySelectorAll('[data-auto-hide]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

});