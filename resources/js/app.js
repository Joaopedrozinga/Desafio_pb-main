import 'sweetalert2/dist/sweetalert2.css';
import SweetAlert2 from 'sweetalert2';

// Configure global SweetAlert2 defaults
window.Swal = SweetAlert2;
SweetAlert2.defaults({
    theme: 'auto',
    iconColor: 'var(--color-accent)',
    confirmButtonColor: 'var(--color-accent)',
    background: 'var(--color-zinc-50)',
    color: 'var(--color-zinc-900)',
    showClass: {
        popup: 'animate__animated animate__fadeIn animate__zoomIn',
        backdrop: 'animate__animated animate__fadeIn'
    },
    hideClass: {
        popup: 'animate__animated animate__zoomOut animate__fadeOut',
        backdrop: 'animate__animated animate__fadeOut'
    }
});

// Helper for Livewire toast events
window.addEventListener('toast', (event) => {
    const { text, variant = 'success' } = event.detail || {};
    const iconMap = {
        success: 'success',
        danger: 'error',
        warning: 'warning',
        info: 'info'
    };
    SweetAlert2.fire({
        icon: iconMap[variant] || 'info',
        text: text,
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
});