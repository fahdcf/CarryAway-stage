/**
 * Homepage JavaScript
 * Handles all interactions and animations for the homepage
 */

class HomepageApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.setupHeaderScroll();
        this.setupTabSwitcher();
        this.setupScrollAnimations();
        this.setupCounterAnimations();
        this.setupSmoothScrolling();
        console.log('Homepage initialized successfully');
    }

    // Mobile menu functionality
    setupMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
                
                // Update icon
                const icon = mobileMenuToggle.querySelector('i');
                if (mobileMenu.classList.contains('active')) {
                    icon.setAttribute('data-lucide', 'x');
                } else {
                    icon.setAttribute('data-lucide', 'menu');
                }
                
                // Refresh icons
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileMenuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.setAttribute('data-lucide', 'menu');
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }
            });
        }
    }

    // Header scroll effect
    setupHeaderScroll() {
        const header = document.getElementById('header');
        if (!header) return;

        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            lastScrollY = currentScrollY;
        });
    }

    // Tab switcher functionality
    setupTabSwitcher() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const actionText = document.getElementById('action-text');
        const primaryAction = document.getElementById('primary-action');

        if (tabButtons.length === 0) return;

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');

                // Update action text and icon based on selected tab
                const tab = button.getAttribute('data-tab');
                const icon = primaryAction.querySelector('i');
                
                if (tab === 'sender') {
                    actionText.textContent = 'Publier une demande';
                    icon.setAttribute('data-lucide', 'package');
                } else if (tab === 'traveler') {
                    actionText.textContent = 'Proposer un transport';
                    icon.setAttribute('data-lucide', 'truck');
                }

                // Refresh icons
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });
        });
    }

    // Scroll animations using Intersection Observer
    setupScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all elements with animation classes
        const animatedElements = document.querySelectorAll(
            '.homepage-fade-in, .homepage-slide-in-left, .homepage-slide-in-right, .fade-in, .slide-in-left, .slide-in-right'
        );

        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }

    // Counter animations
    setupCounterAnimations() {
        const counters = document.querySelectorAll('.stat-number[data-target]');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };

            updateCounter();
        };

        // Use Intersection Observer to trigger counter animations
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    // Smooth scrolling for anchor links
    setupSmoothScrolling() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = document.getElementById('header')?.offsetHeight || 0;
                    const targetPosition = target.offsetTop - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu && mobileMenu.classList.contains('active')) {
                        mobileMenu.classList.remove('active');
                        const toggle = document.getElementById('mobile-menu-toggle');
                        const icon = toggle?.querySelector('i');
                        if (icon) {
                            icon.setAttribute('data-lucide', 'menu');
                            if (window.lucide) {
                                window.lucide.createIcons();
                            }
                        }
                    }
                }
            });
        });
    }

    // Utility method for showing notifications
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
}

// Initialize the homepage app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.homepageApp = new HomepageApp();
});

// Handle page visibility changes
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        // Refresh icons when page becomes visible
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
});

// Handle window resize
window.addEventListener('resize', () => {
    // Close mobile menu on resize to desktop
    if (window.innerWidth >= 1024) {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            const toggle = document.getElementById('mobile-menu-toggle');
            const icon = toggle?.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', 'menu');
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }
        }
    }
});

// Export for potential use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = HomepageApp;
}
