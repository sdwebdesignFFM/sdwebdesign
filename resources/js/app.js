import './bootstrap';

// Scroll animations with IntersectionObserver
function initMotionObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.motion:not(.in-view)').forEach(el => observer.observe(el));
}

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', initMotionObserver);

// Re-initialize after Livewire navigation (for redirects)
document.addEventListener('livewire:navigated', initMotionObserver);
