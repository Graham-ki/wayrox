<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - TechNova</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader"></div>
    </div>

    <!-- Particles Container -->
    <div class="particles-container"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.html" class="logo">
                <svg width="30" height="30" viewBox="0 0 30 30">
                    <rect width="30" height="30" rx="6" fill="#6366f1"/>
                    <text x="15" y="21" text-anchor="middle" fill="white" font-weight="bold" font-size="14">T</text>
                </svg>
                <span>TechNova</span>
            </a>
            <ul class="nav-menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="portfolio.html">Portfolio</a></li>
                <li><a href="blog.html" class="active">Blog</a></li>
                <li><a href="contact.html" class="btn-primary">Get Started</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header animated-gradient">
        <div class="container">
            <h1 class="reveal-zoom">Our Blog</h1>
            <p class="reveal stagger-1">Insights, news, and thought leadership from our team</p>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="blog-section">
        <div class="container">
            <div class="blog-grid">
                <article class="blog-card reveal-zoom stagger-1">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">March 15, 2024</span>
                            <span class="blog-category">Technology</span>
                        </div>
                        <h2>The Future of AI in Business</h2>
                        <p>Explore how artificial intelligence is transforming industries and what it means for your business.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
                
                <article class="blog-card reveal-zoom stagger-2">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">March 10, 2024</span>
                            <span class="blog-category">Development</span>
                        </div>
                        <h2>10 Web Development Trends to Watch</h2>
                        <p>Stay ahead of the curve with these emerging web development trends and technologies.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
                
                <article class="blog-card reveal-zoom stagger-3">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">March 5, 2024</span>
                            <span class="blog-category">Cloud</span>
                        </div>
                        <h2>Cloud Migration Best Practices</h2>
                        <p>Learn the essential steps for a successful cloud migration strategy.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
                
                <article class="blog-card reveal-zoom stagger-4">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">February 28, 2024</span>
                            <span class="blog-category">Security</span>
                        </div>
                        <h2>Cybersecurity Essentials for 2024</h2>
                        <p>Protect your business with these critical cybersecurity measures and best practices.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
                
                <article class="blog-card reveal-zoom stagger-5">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">February 20, 2024</span>
                            <span class="blog-category">Mobile</span>
                        </div>
                        <h2>Mobile-First Design Principles</h2>
                        <p>Why mobile-first design is crucial for modern web development and user experience.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
                
                <article class="blog-card reveal-zoom stagger-6">
                    <div class="blog-image">
                        <div class="placeholder">Blog Image</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">February 15, 2024</span>
                            <span class="blog-category">Data</span>
                        </div>
                        <h2>Data-Driven Decision Making</h2>
                        <p>How to leverage data analytics to make better business decisions.</p>
                        <a href="#" class="btn-link">Read More →</a>
                    </div>
                </article>
            </div>
            
            <!-- Pagination -->
            <div class="pagination reveal stagger-1">
                <button class="page-btn active interactive-hover">1</button>
                <button class="page-btn interactive-hover">2</button>
                <button class="page-btn interactive-hover">3</button>
                <button class="page-btn interactive-hover">Next →</button>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section animated-gradient">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item reveal-zoom stagger-1">
                    <h3 class="counter" data-counter="200">0</h3>
                    <p>Blog Posts</p>
                </div>
                <div class="stat-item reveal-zoom stagger-2">
                    <h3 class="counter" data-counter="50">0</h3>
                    <p>Expert Authors</p>
                </div>
                <div class="stat-item reveal-zoom stagger-3">
                    <h3 class="counter" data-counter="1000">0</h3>
                    <p>Monthly Readers</p>
                </div>
                <div class="stat-item reveal-zoom stagger-4">
                    <h3 class="counter" data-counter="15">0</h3>
                    <p>Categories</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-section reveal-left stagger-1">
                <h3>TechNova</h3>
                <p>Innovative technology solutions for modern businesses.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook" class="interactive-hover">📘</a>
                    <a href="#" aria-label="Twitter" class="interactive-hover">🐦</a>
                    <a href="#" aria-label="LinkedIn" class="interactive-hover">💼</a>
                    <a href="#" aria-label="GitHub" class="interactive-hover">🐙</a>
                </div>
            </div>
            <div class="footer-section reveal-left stagger-2">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="about.html" class="interactive-hover">About Us</a></li>
                    <li><a href="services.html" class="interactive-hover">Services</a></li>
                    <li><a href="portfolio.html" class="interactive-hover">Portfolio</a></li>
                    <li><a href="blog.html" class="interactive-hover">Blog</a></li>
                </ul>
            </div>
            <div class="footer-section reveal-left stagger-3">
                <h4>Services</h4>
                <ul>
                    <li><a href="services.html" class="interactive-hover">Web Development</a></li>
                    <li><a href="services.html" class="interactive-hover">Mobile Apps</a></li>
                    <li><a href="services.html" class="interactive-hover">Cloud Solutions</a></li>
                    <li><a href="services.html" class="interactive-hover">AI & ML</a></li>
                </ul>
            </div>
            <div class="footer-section reveal-left stagger-4">
                <h4>Contact</h4>
                <ul>
                    <li>📍 123 Tech Street, Silicon Valley</li>
                    <li>📞 +1 (555) 123-4567</li>
                    <li>✉️ info@technova.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 TechNova. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>