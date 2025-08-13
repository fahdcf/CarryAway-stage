/**
 * CarryWay Modern JavaScript
 * Handles all interactions for the modern homepage design
 */

class CarryWayApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupScrollAnimations();
        this.setupHeaderScroll();
        this.setupTabSwitcher();
        this.setupCounterAnimations();
        this.setupMobileMenu();
    }

    setupEventListeners() {
        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.initializeComponents();
            });
        } else {
            this.initializeComponents();
        }
    }

    initializeComponents() {
        // Initialize all components after DOM is ready
        this.setupScrollAnimations();
        this.setupHeaderScroll();
        this.setupTabSwitcher();
        this.setupCounterAnimations();
        this.setupMobileMenu();
    }

    setupMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenuToggle.innerHTML = '<i data-lucide="x" class="icon"></i>';
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuToggle.innerHTML = '<i data-lucide="menu" class="icon"></i>';
                }
                
                // Re-initialize Lucide icons
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });

            // Close mobile menu when clicking on links
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    mobileMenuToggle.innerHTML = '<i data-lucide="menu" class="icon"></i>';
                    
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            });
        }
    }

    setupHeaderScroll() {
        const header = document.getElementById('header');
        
        if (header) {
            let lastScrollY = window.scrollY;
            
            const updateHeader = () => {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > 50) {
                    header.classList.remove('header-default');
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                    header.classList.add('header-default');
                }
                
                lastScrollY = currentScrollY;
            };

            window.addEventListener('scroll', updateHeader, { passive: true });
        }
    }

    setupTabSwitcher() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const primaryAction = document.getElementById('primary-action');
        const actionText = document.getElementById('action-text');
        
        if (tabButtons.length > 0 && primaryAction && actionText) {
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    // Update action button text based on selected tab
                    const tabType = button.getAttribute('data-tab');
                    const icon = primaryAction.querySelector('i');
                    
                    if (tabType === 'sender') {
                        actionText.textContent = 'Publier une demande';
                        if (icon) {
                            icon.setAttribute('data-lucide', 'package');
                        }
                    } else if (tabType === 'traveler') {
                        actionText.textContent = 'Proposer un transport';
                        if (icon) {
                            icon.setAttribute('data-lucide', 'truck');
                        }
                    }
                    
                    // Re-initialize Lucide icons
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            });
        }
    }

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
        const animatedElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in');
        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }

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
                }
            });
        });
    }

    // Add loading states to buttons
    setupButtonLoading() {
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Don't add loading state to navigation buttons
                if (button.closest('.nav-actions') || button.closest('.mobile-menu-actions')) {
                    return;
                }
                
                // Add loading state
                button.classList.add('loading');
                button.style.pointerEvents = 'none';
                
                // Create spinner
                const originalContent = button.innerHTML;
                button.innerHTML = '<span class="spinner mr-2"></span>Chargement...';
                
                // Remove loading state after 2 seconds (simulate API call)
                setTimeout(() => {
                    button.classList.remove('loading');
                    button.style.pointerEvents = '';
                    button.innerHTML = originalContent;
                    
                    // Re-initialize Lucide icons
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }, 2000);
            });
        });
    }

    // Handle form submissions (AJAX-only, opt-in)
    setupFormHandling() {
        // Only intercept forms explicitly marked for AJAX
        const forms = document.querySelectorAll('form[data-ajax="true"]');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                // Intercept only these forms
                e.preventDefault();

                // Add your form handling logic here
                console.log('AJAX form submitted:', form);

                // Show success message or handle errors
                this.showNotification('Formulaire envoyé avec succès!', 'success');
            });
        });
    }

    // Show notifications
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 24px',
            borderRadius: '8px',
            color: 'white',
            fontWeight: '500',
            zIndex: '1000',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease'
        });
        
        // Set background color based on type
        const colors = {
            success: '#22c55e',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };
        
        notification.style.backgroundColor = colors[type] || colors.info;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Initialize all features
    initializeAll() {
        // Keep minimal for performance on dashboard pages
        // this.setupSmoothScrolling();
        // this.setupButtonLoading();
        this.setupFormHandling();
    }
}

// Initialize the app when the script loads
const app = new CarryWayApp();

// Initialize additional features when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    app.initializeAll();
});

// Handle window resize
window.addEventListener('resize', () => {
    // Handle any resize-specific logic here
}, { passive: true });

// Export for potential external use
window.CarryWayApp = CarryWayApp;
