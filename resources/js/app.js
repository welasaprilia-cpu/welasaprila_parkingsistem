// Parking System App JS - Fixed Dashboard Interactivity
import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    console.log('Parking System loaded successfully - Dashboard interactivity fixed');
    
    // Fix dashboard buttons and interactive elements
    const interactiveElements = document.querySelectorAll('.group, .cursor-pointer, .parking-spot, button, a[href], .card a');
    interactiveElements.forEach(el => {
        el.style.pointerEvents = 'auto';
        el.style.cursor = 'pointer';
        el.classList.add('interactive-element');
    });

    // Quick action cards click feedback
    document.querySelectorAll('.grid a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Parking spots click feedback
    document.querySelectorAll('.parking-grid .group').forEach(spot => {
        spot.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            console.log('Parking spot clicked');
        });
    });

    // All buttons click effect
    document.querySelectorAll('button, .btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Status colors
    document.querySelectorAll('.status-available, .status-paid').forEach(el => {
        el.style.color = '#10b981';
    });
    
    document.querySelectorAll('.status-occupied').forEach(el => {
        el.style.color = '#ef4444';
    });
    
    console.log('Dashboard interactivity enhancements loaded');
});


