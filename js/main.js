// ============ INITIALIZATION ============
document.addEventListener('DOMContentLoaded', () => {
    removeLoadingScreen();
    initializeNavigation();
    initializeParticles();
    initializeScrollEffects();
    initializeRevealAnimations();
    initializeCounters();
    initializeSmoothScroll();
    initializeScrollToTop();
    initializeDynamicYear();
    initializeActiveLinks();
    initializeContactForm();
});

// ============ LOADING SCREEN ============
function removeLoadingScreen() {
    const loadingScreen = document.querySelector('.loading-screen');
    if (!loadingScreen) return;
    
    if (document.readyState === 'complete') {
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
            setTimeout(() => loadingScreen.remove(), 500);
        }, 100);
    } else {
        window.addEventListener('load', () => {
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
                setTimeout(() => loadingScreen.remove(), 500);
            }, 100);
        });
        
        setTimeout(() => {
            if (loadingScreen && !loadingScreen.classList.contains('hidden')) {
                loadingScreen.classList.add('hidden');
                setTimeout(() => loadingScreen.remove(), 500);
            }
        }, 3000);
    }
}

// ============ NAVIGATION ============
function initializeNavigation() {
    const header = document.querySelector('header');
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const dropdowns = document.querySelectorAll('.dropdown');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });
    }
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                if (window.innerWidth <= 859) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropdowns.forEach(d => {
                        if (d !== dropdown) d.classList.remove('active');
                    });
                    dropdown.classList.toggle('active');
                }
            });
        }
    });
    
    window.addEventListener('resize', () => {
        if (window.innerWidth > 859) {
            closeMobileMenu();
        }
    });
}

function closeMobileMenu() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const dropdowns = document.querySelectorAll('.dropdown');
    
    if (hamburger) hamburger.classList.remove('active');
    if (navMenu) navMenu.classList.remove('active');
    dropdowns.forEach(d => d.classList.remove('active'));
}

// ============ PARTICLES ============
function initializeParticles() {
    const container = document.querySelector('.particles-container');
    if (!container) return;
    
    const particleCount = window.innerWidth <= 768 ? 15 : 25;
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.width = Math.random() * 8 + 3 + 'px';
        particle.style.height = particle.style.width;
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 10 + 's';
        particle.style.animationDuration = Math.random() * 15 + 10 + 's';
        container.appendChild(particle);
    }
}

// ============ SCROLL EFFECTS ============
function initializeScrollEffects() {
    const header = document.querySelector('header');
    if (!header) return;
    
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// ============ REVEAL ANIMATIONS ============
function initializeRevealAnimations() {
    const revealElements = document.querySelectorAll('.reveal-scroll, .reveal-left, .reveal-right, .reveal-zoom, .reveal-rotate');
    
    if (!revealElements.length) return;
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    revealElements.forEach(element => revealObserver.observe(element));
}

// ============ COUNTERS ============
function initializeCounters() {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-counter')) || 0;
                animateCounter(counter, target);
                counterObserver.unobserve(counter);
            }
        });
    }, {
        threshold: 0.5
    });
    
    counters.forEach(counter => counterObserver.observe(counter));
}

function animateCounter(element, target, duration = 1500) {
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentValue = Math.floor(target * easeOutQuart);
        
        element.textContent = currentValue + '+';
        
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = target + '+';
        }
    }
    
    requestAnimationFrame(update);
}

// ============ SMOOTH SCROLL ============
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// ============ SCROLL TO TOP ============
function initializeScrollToTop() {
    const btn = document.createElement('button');
    btn.innerHTML = '↑';
    btn.className = 'scroll-to-top';
    btn.setAttribute('aria-label', 'Scroll to top');
    document.body.appendChild(btn);
    
    window.addEventListener('scroll', () => {
        btn.style.display = window.pageYOffset > 300 ? 'block' : 'none';
    });
    
    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ============ DYNAMIC YEAR ============
function initializeDynamicYear() {
    const currentYear = new Date().getFullYear();
    document.querySelectorAll('.foot-bottom span').forEach(el => {
        el.innerHTML = el.innerHTML.replace('2026', currentYear);
    });
}

// ============ ACTIVE LINKS ============
function initializeActiveLinks() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.nav-menu a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
        }
    });
}

// ============ CONTACT FORM ============
function initializeContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const name = document.getElementById('name')?.value.trim();
        const email = document.getElementById('email')?.value.trim();
        const message = document.getElementById('message')?.value.trim();
        
        if (!name || !email || !message) {
            showFormError(form, 'Please fill in all required fields.');
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showFormError(form, 'Please enter a valid email address.');
            return;
        }
        
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;
        
        setTimeout(() => {
            alert('Thank you for your message! We will get back to you soon.');
            form.reset();
            submitButton.textContent = 'Send Message';
            submitButton.disabled = false;
        }, 1500);
    });
}

function showFormError(form, message) {
    form.style.animation = 'none';
    setTimeout(() => {
        form.style.animation = 'shake 0.5s ease';
    }, 10);
    alert(message);
}

console.log('%c🚀 WayronX Loaded Successfully!', 'color: #2563FF; font-size: 16px; font-weight: bold;');