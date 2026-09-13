<?php
session_start();
$pageTitle = 'Portfolio';
$currentPage = 'portfolio';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portfolio — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ PORTFOLIO PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .portfolio-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .portfolio-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(124,58,237,0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .portfolio-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .portfolio-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .portfolio-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .portfolio-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Featured Product */
    .featured-product {
        padding: 80px 0;
        background: var(--white);
    }
    
    .featured-product-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .featured-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: var(--grad);
        color: #fff;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 25px;
    }
    
    .featured-product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    
    .featured-product-content h2 {
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-bottom: 20px;
        color: var(--ink);
    }
    
    .featured-product-content h2 .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .featured-product-content > p {
        color: var(--ink-mute);
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 25px;
    }
    
    .product-features-list {
        list-style: none;
        margin-bottom: 30px;
    }
    
    .product-features-list li {
        padding: 8px 0;
        color: var(--ink-mute);
        font-size: 1rem;
        position: relative;
        padding-left: 28px;
    }
    
    .product-features-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .product-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .product-actions .btn-primary,
    .product-actions .btn-secondary {
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-family: 'Manrope', sans-serif;
        cursor: pointer;
    }
    
    .featured-product-visual {
        position: relative;
    }
    
    .product-image-wrapper {
        background: var(--grad);
        border-radius: 20px;
        padding: 40px;
        min-height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(37,99,255,0.2);
    }
    
    .product-image-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    
    .product-image-wrapper img {
        width: 100%;
        max-width: 400px;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }
    
    .product-image-fallback {
        font-size: 8rem;
        position: relative;
        z-index: 1;
    }
    
    /* Products Grid */
    .products-section {
        padding: 100px 0;
        background: var(--light);
    }
    
    .products-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .section-head-portfolio {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .section-head-portfolio h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .section-head-portfolio p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 30px;
    }
    
    .product-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    .product-card-image {
        background: var(--grad);
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        font-size: 5rem;
    }
    
    .product-card-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .product-card:hover .product-card-image::before {
        left: 100%;
    }
    
    .product-card-content {
        padding: 30px;
    }
    
    .product-card-tag {
        display: inline-block;
        padding: 5px 12px;
        background: var(--light);
        color: var(--purple);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .product-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.01em;
        color: var(--ink);
    }
    
    .product-card p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .product-card-features {
        list-style: none;
        margin-bottom: 20px;
    }
    
    .product-card-features li {
        padding: 5px 0;
        color: var(--ink-mute);
        font-size: 0.9rem;
        position: relative;
        padding-left: 25px;
    }
    
    .product-card-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .product-card-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--blue);
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .product-card-link .arrow {
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-card-link .arrow {
        transform: translateX(5px);
    }
    
    /* Stats Section */
    .portfolio-stats {
        background: var(--midnight);
        padding: 80px 0;
    }
    
    .stats-grid-portfolio {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
        text-align: center;
    }
    
    .stat-item-portfolio h3 {
        font-size: 3rem;
        font-weight: 800;
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
    }
    
    .stat-item-portfolio p {
        color: rgba(255,255,255,0.6);
        font-size: 1rem;
    }
    
    /* CTA Section */
    .portfolio-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .portfolio-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .portfolio-cta p {
        color: rgba(255,255,255,0.9);
        font-size: 1.05rem;
        margin-bottom: 30px;
    }
    
    .btn-white {
        background: #fff;
        color: var(--blue);
        padding: 14px 28px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.98rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    /* Responsive */
    @media (max-width: 920px) {
        .featured-product-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .featured-product-visual {
            order: -1;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .featured-product-container,
        .products-container {
            padding: 0 20px;
        }
        
        .stats-grid-portfolio {
            grid-template-columns: repeat(2, 1fr);
            padding: 0 20px;
        }
    }
</style>
</head>
<body>

<div class="loading-screen"><div class="loader"></div></div>
<div class="particles-container"></div>

<header class="navbar" id="navbar">
  <nav>
    <div class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </div>
    
    <a href="index.php" class="logo">
      <svg class="mark" viewBox="0 0 24 24" fill="none"><path d="M4 4L20 20M20 4L4 20" stroke="url(#navX)" stroke-width="2.6" stroke-linecap="round"/><defs><linearGradient id="navX" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2563FF"/><stop offset="1" stop-color="#7C3AED"/></linearGradient></defs></svg>
      WayronX
    </a>
    
    <ul class="nav-menu" id="navMenu">
      <li><a href="index.php">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="solutions.php">Solutions</a></li>
      <li><a href="portfolio.php" class="active">Portfolio</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Coming Soon</a></li>
        </ul>
      </li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Page Header -->
  <section class="portfolio-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Our Products</div>
      <h1 class="portfolio-page-title reveal reveal-2">Technology that powers <span class="accent">real businesses</span>.</h1>
      <p class="portfolio-page-subtitle reveal reveal-3">Explore our suite of enterprise-grade platforms designed to transform how businesses operate.</p>
    </div>
  </section>

  <!-- Featured Product: POS Pro -->
  <section class="featured-product">
    <div class="featured-product-container">
      <div class="featured-product-grid">
        <div class="featured-product-content reveal-left">
          <span class="featured-label">⭐ Featured Product</span>
          <h2>POS Pro — <span class="accent">Point of Sale & Marketplace</span></h2>
          <p>A complete point-of-sale and marketplace platform built for modern retail businesses. Manage sales, inventory, customers, and multiple locations from one powerful dashboard.</p>
          
          <ul class="product-features-list">
            <li>Multi-location sales management</li>
            <li>Real-time inventory tracking</li>
            <li>Integrated marketplace platform</li>
            <li>Advanced reporting & analytics</li>
            <li>Customer loyalty programs</li>
            <li>Mobile & desktop apps</li>
          </ul>
          
          <div class="product-actions">
            <a href="product-pos-pro.php" class="btn-primary">View Product <span>→</span></a>
            <a href="contact.php" class="btn-secondary">Request Demo</a>
          </div>
        </div>
        
        <div class="featured-product-visual reveal-right">
          <div class="product-image-wrapper">
            <div class="product-image-fallback">💳</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- All Products -->
  <section class="products-section">
    <div class="products-container">
      <div class="section-head-portfolio reveal">
        <div class="eyebrow on-light"><span class="mark"></span>All Products</div>
        <h2>Enterprise platforms for every business.</h2>
        <p>Each product is built with the same commitment to quality, reliability, and performance.</p>
      </div>
      
      <div class="products-grid">
        <!-- POS Pro -->
        <a href="product-pos-pro.php" class="product-card reveal-zoom stagger-1">
          <div class="product-card-image">💳</div>
          <div class="product-card-content">
            <span class="product-card-tag">Retail & Commerce</span>
            <h3>POS Pro</h3>
            <p>A complete point-of-sale and marketplace platform for modern retail businesses of all sizes.</p>
            <ul class="product-card-features">
              <li>Multi-store management</li>
              <li>Inventory tracking</li>
              <li>Sales analytics</li>
              <li>Customer management</li>
            </ul>
            <span class="product-card-link">View Product <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Manufacturing Management System -->
        <a href="product-manufacturing.php" class="product-card reveal-zoom stagger-2">
          <div class="product-card-image">🏭</div>
          <div class="product-card-content">
            <span class="product-card-tag">Manufacturing</span>
            <h3>Manufacturing Management System</h3>
            <p>End-to-end production management system that optimizes your entire manufacturing workflow.</p>
            <ul class="product-card-features">
              <li>Production planning</li>
              <li>Quality control</li>
              <li>Supply chain management</li>
              <li>Cost tracking</li>
            </ul>
            <span class="product-card-link">View Product <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Savings & Credit Management -->
        <a href="product-savings-credit.php" class="product-card reveal-zoom stagger-3">
          <div class="product-card-image">🏦</div>
          <div class="product-card-content">
            <span class="product-card-tag">Financial Services</span>
            <h3>Savings & Credit Management System</h3>
            <p>A comprehensive solution for SACCOs, microfinance institutions, and credit unions to manage savings and loans.</p>
            <ul class="product-card-features">
              <li>Member management</li>
              <li>Loan processing</li>
              <li>Savings tracking</li>
              <li>Financial reporting</li>
            </ul>
            <span class="product-card-link">View Product <span class="arrow">→</span></span>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <section class="portfolio-stats">
    <div class="stats-grid-portfolio">
      <div class="stat-item-portfolio reveal-zoom stagger-1">
        <h3 class="counter" data-counter="500">0</h3>
        <p>Businesses Served</p>
      </div>
      <div class="stat-item-portfolio reveal-zoom stagger-2">
        <h3 class="counter" data-counter="15">0</h3>
        <p>Countries</p>
      </div>
      <div class="stat-item-portfolio reveal-zoom stagger-3">
        <h3 class="counter" data-counter="99">0</h3>
        <p>% Uptime</p>
      </div>
      <div class="stat-item-portfolio reveal-zoom stagger-4">
        <h3 class="counter" data-counter="24">0</h3>
        <p>/7 Support</p>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="portfolio-cta">
    <div class="wrap">
      <h2 class="reveal">Ready to transform your business?</h2>
      <p class="reveal stagger-1">Let's find the right product for your needs.</p>
      <a href="contact.php" class="btn-white reveal stagger-2">Get Started <span>→</span></a>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <a href="index.php" class="logo">
          <svg class="mark" viewBox="0 0 24 24" fill="none"><path d="M4 4L20 20M20 4L4 20" stroke="url(#footX)" stroke-width="2.6" stroke-linecap="round"/><defs><linearGradient id="footX" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2563FF"/><stop offset="1" stop-color="#7C3AED"/></linearGradient></defs></svg>
          WayronX
        </a>
        <p>Building intelligent digital solutions for a smarter future.</p>
      </div>
      <div class="foot-cols">
        <div class="foot-col">
          <h4>Company</h4>
          <ul>
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Our Approach</a></li>
            <li><a href="careers.php">Careers</a></li>
          </ul>
        </div>
        <div class="foot-col">
          <h4>Products</h4>
          <ul>
            <li><a href="product-pos-pro.php">POS Pro</a></li>
            <li><a href="product-manufacturing.php">Manufacturing System</a></li>
            <li><a href="product-savings-credit.php">Savings & Credit</a></li>
          </ul>
        </div>
        <div class="foot-col">
          <h4>Connect</h4>
          <ul>
            <li><a href="#">LinkedIn</a></li>
            <li><a href="#">Instagram</a></li>
            <li><a href="mailto:wayronx01@gmail.com">Email</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© 2026 WayronX. All rights reserved.</span>
      <div class="foot-legal">
        <a href="privacy.php">Privacy Policy</a>
        <a href="terms.php">Terms &amp; Conditions</a>
      </div>
    </div>
  </div>
</footer>

<script src="js/main.js"></script>
</body>
</html>