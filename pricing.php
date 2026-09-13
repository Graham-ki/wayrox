<?php
session_start();
$pageTitle = 'Pricing';
$currentPage = 'pricing';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pricing — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ PRICING PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .pricing-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .pricing-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37,99,255,0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .pricing-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .pricing-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .pricing-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .pricing-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Billing Toggle */
    .billing-toggle-section {
        padding: 40px 0;
        background: var(--white);
        text-align: center;
    }
    
    .billing-toggle {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: var(--light);
        padding: 8px;
        border-radius: 50px;
        position: relative;
    }
    
    .billing-toggle button {
        padding: 10px 24px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        font-family: 'Manrope', sans-serif;
        font-size: 0.95rem;
        color: var(--ink-mute);
        border-radius: 50px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .billing-toggle button.active {
        background: var(--grad);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,255,0.3);
    }
    
    .billing-save-badge {
        display: inline-block;
        margin-left: 10px;
        padding: 3px 10px;
        background: rgba(16,185,129,0.15);
        color: var(--success);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    /* Pricing Section */
    .pricing-main {
        padding: 60px 0 100px;
        background: var(--white);
    }
    
    .pricing-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .pricing-section-head {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 50px;
    }
    
    .pricing-section-head h2 {
        font-size: clamp(2rem, 3.5vw, 2.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .pricing-section-head p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.65;
    }
    
    /* Service Pricing Cards */
    .pricing-grid-main {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }
    
    .pricing-card-main {
        background: var(--white);
        border: 2px solid var(--line-light);
        border-radius: 20px;
        padding: 40px 30px;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .pricing-card-main:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: rgba(37,99,255,0.3);
    }
    
    .pricing-card-main.featured {
        border-color: var(--blue);
        box-shadow: 0 20px 40px rgba(37,99,255,0.15);
        transform: scale(1.02);
    }
    
    .pricing-card-main.featured:hover {
        transform: scale(1.02) translateY(-8px);
    }
    
    .pricing-card-main.featured::before {
        content: 'MOST POPULAR';
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--grad);
        color: #fff;
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    
    .pricing-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .pricing-card-main h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--ink);
        letter-spacing: -0.01em;
    }
    
    .pricing-card-desc {
        color: var(--ink-mute);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 25px;
        min-height: 40px;
    }
    
    .pricing-price-main {
        display: flex;
        align-items: baseline;
        gap: 5px;
        margin-bottom: 5px;
    }
    
    .pricing-price-main .currency {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--blue);
    }
    
    .pricing-price-main .amount {
        font-size: 3rem;
        font-weight: 800;
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        line-height: 1;
    }
    
    .pricing-price-main .period {
        color: var(--ink-mute);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .pricing-billing-note {
        color: var(--ink-mute);
        font-size: 0.8rem;
        margin-bottom: 25px;
    }
    
    .pricing-divider {
        height: 1px;
        background: var(--line-light);
        margin-bottom: 25px;
    }
    
    .pricing-features-list {
        list-style: none;
        margin-bottom: 30px;
        flex-grow: 1;
    }
    
    .pricing-features-list li {
        padding: 8px 0 8px 28px;
        color: var(--ink-mute);
        font-size: 0.9rem;
        position: relative;
        line-height: 1.5;
    }
    
    .pricing-features-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
        font-size: 1rem;
    }
    
    .pricing-features-list li.disabled {
        color: rgba(90,97,128,0.4);
    }
    
    .pricing-features-list li.disabled::before {
        content: '✕';
        color: rgba(90,97,128,0.4);
    }
    
    .pricing-card-btn {
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        font-family: 'Manrope', sans-serif;
        border: none;
    }
    
    .pricing-card-btn.primary {
        background: var(--grad);
        color: #fff;
    }
    
    .pricing-card-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .pricing-card-btn.outline {
        background: transparent;
        color: var(--blue);
        border: 2px solid var(--blue);
    }
    
    .pricing-card-btn.outline:hover {
        background: var(--blue);
        color: #fff;
        transform: translateY(-2px);
    }
    
    /* Product Pricing Section */
    .product-pricing-section {
        padding: 80px 0;
        background: var(--light);
    }
    
    .product-pricing-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .product-pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }
    
    .product-pricing-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .product-pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    .product-pricing-header {
        background: var(--grad);
        padding: 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    
    .product-pricing-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .product-pricing-header .icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }
    
    .product-pricing-header h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .product-pricing-header p {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.9);
        position: relative;
        z-index: 1;
    }
    
    .product-pricing-body {
        padding: 30px;
    }
    
    .product-price-display {
        display: flex;
        align-items: baseline;
        gap: 5px;
        margin-bottom: 5px;
    }
    
    .product-price-display .amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--ink);
    }
    
    .product-price-display .period {
        color: var(--ink-mute);
        font-size: 0.9rem;
    }
    
    .product-price-starting {
        color: var(--ink-mute);
        font-size: 0.85rem;
        margin-bottom: 20px;
    }
    
    .product-pricing-features {
        list-style: none;
        margin-bottom: 25px;
    }
    
    .product-pricing-features li {
        padding: 8px 0 8px 24px;
        color: var(--ink-mute);
        font-size: 0.9rem;
        position: relative;
    }
    
    .product-pricing-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .product-pricing-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: var(--grad);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .product-pricing-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    /* Enterprise CTA */
    .enterprise-section {
        padding: 80px 0;
        background: var(--midnight);
    }
    
    .enterprise-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 32px;
        text-align: center;
    }
    
    .enterprise-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 25px;
        box-shadow: 0 20px 40px rgba(37,99,255,0.3);
    }
    
    .enterprise-section h2 {
        font-size: clamp(2rem, 3.5vw, 2.6rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .enterprise-section p {
        color: rgba(255,255,255,0.7);
        font-size: 1.05rem;
        line-height: 1.65;
        max-width: 50ch;
        margin: 0 auto 35px;
    }
    
    .enterprise-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .enterprise-feature {
        padding: 20px 15px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .enterprise-feature:hover {
        background: rgba(255,255,255,0.08);
        transform: translateY(-3px);
    }
    
    .enterprise-feature .icon {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .enterprise-feature h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
    }
    
    .enterprise-feature p {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
        margin: 0;
    }
    
    .enterprise-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-white {
        background: #fff;
        color: var(--blue);
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
    }
    
    .btn-outline-white {
        background: transparent;
        color: #fff;
        padding: 14px 28px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-outline-white:hover {
        border-color: #fff;
        transform: translateY(-2px);
    }
    
    /* FAQ Section */
    .pricing-faq-section {
        padding: 80px 0;
        background: var(--white);
    }
    
    .pricing-faq-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .pricing-faq-container h2 {
        font-size: clamp(1.85rem, 3.4vw, 2.4rem);
        font-weight: 800;
        text-align: center;
        margin-bottom: 50px;
        letter-spacing: -0.015em;
        color: var(--ink);
    }
    
    .pricing-faq-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .pricing-faq-item {
        background: var(--light);
        border-radius: 12px;
        padding: 25px;
        transition: all 0.3s ease;
    }
    
    .pricing-faq-item:hover {
        background: rgba(37,99,255,0.03);
    }
    
    .pricing-faq-item h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .pricing-faq-item h4::before {
        content: '?';
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .pricing-faq-item p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.65;
        margin: 0;
        padding-left: 34px;
    }
    
    /* CTA */
    .pricing-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .pricing-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .pricing-cta p {
        color: rgba(255,255,255,0.9);
        font-size: 1.05rem;
        margin-bottom: 30px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .pricing-container,
        .product-pricing-container,
        .enterprise-container,
        .pricing-faq-container {
            padding: 0 20px;
        }
        
        .pricing-grid-main,
        .product-pricing-grid {
            grid-template-columns: 1fr;
        }
        
        .pricing-card-main.featured {
            transform: none;
        }
        
        .pricing-card-main.featured:hover {
            transform: translateY(-8px);
        }
        
        .billing-toggle {
            flex-direction: column;
            gap: 5px;
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
      <li><a href="portfolio.php">Portfolio</a></li>
      <li><a href="pricing.php" class="active">Pricing</a></li>
      <li><a href="faq.php">FAQ</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Page Header -->
  <section class="pricing-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Pricing</div>
      <h1 class="pricing-page-title reveal reveal-2">Simple, transparent <span class="accent">pricing</span>.</h1>
      <p class="pricing-page-subtitle reveal reveal-3">Choose the plan that fits your business. No hidden fees, no surprises.</p>
    </div>
  </section>

  <!-- Billing Toggle -->
  <section class="billing-toggle-section">
    <div class="billing-toggle">
      <button class="active" id="monthlyBtn">Monthly</button>
      <button id="annualBtn">Annual <span class="billing-save-badge">Save 20%</span></button>
    </div>
  </section>

  <!-- Main Pricing -->
  <section class="pricing-main">
    <div class="pricing-container">
      <div class="pricing-section-head reveal">
        <h2>Service Plans</h2>
        <p>Perfect for businesses looking for development, consulting, and technology solutions.</p>
      </div>
      
      <div class="pricing-grid-main">
        <!-- Starter -->
        <div class="pricing-card-main reveal-zoom stagger-1">
          <div class="pricing-card-icon">🚀</div>
          <h3>Starter</h3>
          <p class="pricing-card-desc">Perfect for small projects and startups getting started.</p>
          <div class="pricing-price-main">
            <span class="currency">$</span>
            <span class="amount" data-monthly="499" data-annual="399">499</span>
            <span class="period">/month</span>
          </div>
          <p class="pricing-billing-note" data-monthly="Billed monthly" data-annual="Billed annually">Billed monthly</p>
          <div class="pricing-divider"></div>
          <ul class="pricing-features-list">
            <li>Up to 40 hours/month</li>
            <li>1 dedicated developer</li>
            <li>Basic support (email)</li>
            <li>Weekly progress reports</li>
            <li>Source code ownership</li>
            <li class="disabled">Priority support</li>
            <li class="disabled">Dedicated account manager</li>
          </ul>
          <a href="contact.php" class="pricing-card-btn outline">Get Started <span>→</span></a>
        </div>
        
        <!-- Professional -->
        <div class="pricing-card-main featured reveal-zoom stagger-2">
          <div class="pricing-card-icon">💼</div>
          <h3>Professional</h3>
          <p class="pricing-card-desc">Best for growing businesses with regular development needs.</p>
          <div class="pricing-price-main">
            <span class="currency">$</span>
            <span class="amount" data-monthly="1499" data-annual="1199">1499</span>
            <span class="period">/month</span>
          </div>
          <p class="pricing-billing-note" data-monthly="Billed monthly" data-annual="Billed annually">Billed monthly</p>
          <div class="pricing-divider"></div>
          <ul class="pricing-features-list">
            <li>Up to 120 hours/month</li>
            <li>2-3 dedicated developers</li>
            <li>Priority support</li>
            <li>Weekly progress reports</li>
            <li>Source code ownership</li>
            <li>Basic DevOps support</li>
            <li class="disabled">Dedicated account manager</li>
          </ul>
          <a href="contact.php" class="pricing-card-btn primary">Get Started <span>→</span></a>
        </div>
        
        <!-- Enterprise -->
        <div class="pricing-card-main reveal-zoom stagger-3">
          <div class="pricing-card-icon">🏢</div>
          <h3>Enterprise</h3>
          <p class="pricing-card-desc">For large organizations with complex, ongoing requirements.</p>
          <div class="pricing-price-main">
            <span class="amount">Custom</span>
          </div>
          <p class="pricing-billing-note">Tailored to your needs</p>
          <div class="pricing-divider"></div>
          <ul class="pricing-features-list">
            <li>Unlimited hours</li>
            <li>Full team allocation</li>
            <li>24/7 priority support</li>
            <li>Daily progress reports</li>
            <li>Source code ownership</li>
            <li>Full DevOps support</li>
            <li>Dedicated account manager</li>
          </ul>
          <a href="contact.php" class="pricing-card-btn outline">Contact Sales <span>→</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Pricing -->
  <section class="product-pricing-section">
    <div class="product-pricing-container">
      <div class="pricing-section-head reveal">
        <h2>Product Pricing</h2>
        <p>Ready-to-deploy platforms for specific business needs. All include free trials.</p>
      </div>
      
      <div class="product-pricing-grid">
        <!-- POS Pro -->
        <div class="product-pricing-card reveal-zoom stagger-1">
          <div class="product-pricing-header">
            <div class="icon">💳</div>
            <h3>POS Pro</h3>
            <p>Point of Sale & Marketplace</p>
          </div>
          <div class="product-pricing-body">
            <div class="product-price-display">
              <span class="amount">$29</span>
              <span class="period">/month</span>
            </div>
            <p class="product-price-starting">Starting price</p>
            <ul class="product-pricing-features">
              <li>1 store location</li>
              <li>Unlimited products</li>
              <li>Inventory management</li>
              <li>Basic reports</li>
              <li>Mobile app included</li>
              <li>14-day free trial</li>
            </ul>
            <a href="product-details.php?product=pos-pro" class="product-pricing-btn">
              View Details <span>→</span>
            </a>
          </div>
        </div>
        
        <!-- Manufacturing System -->
        <div class="product-pricing-card reveal-zoom stagger-2">
          <div class="product-pricing-header">
            <div class="icon">🏭</div>
            <h3>Manufacturing System</h3>
            <p>Production Management</p>
          </div>
          <div class="product-pricing-body">
            <div class="product-price-display">
              <span class="amount">$199</span>
              <span class="period">/month</span>
            </div>
            <p class="product-price-starting">Starting price</p>
            <ul class="product-pricing-features">
              <li>Single production line</li>
              <li>Up to 50 users</li>
              <li>Production planning</li>
              <li>Quality control</li>
              <li>Basic reports</li>
              <li>14-day free trial</li>
            </ul>
            <a href="product-details.php?product=manufacturing-system" class="product-pricing-btn">
              View Details <span>→</span>
            </a>
          </div>
        </div>
        
        <!-- Savings & Credit -->
        <div class="product-pricing-card reveal-zoom stagger-3">
          <div class="product-pricing-header">
            <div class="icon">🏦</div>
            <h3>Savings & Credit</h3>
            <p>Financial Management</p>
          </div>
          <div class="product-pricing-body">
            <div class="product-price-display">
              <span class="amount">$149</span>
              <span class="period">/month</span>
            </div>
            <p class="product-price-starting">Starting price</p>
            <ul class="product-pricing-features">
              <li>Up to 500 members</li>
              <li>Savings management</li>
              <li>Loan processing</li>
              <li>Basic reports</li>
              <li>SMS notifications</li>
              <li>14-day free trial</li>
            </ul>
            <a href="product-details.php?product=savings-credit" class="product-pricing-btn">
              View Details <span>→</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enterprise CTA -->
  <section class="enterprise-section">
    <div class="enterprise-container">
      <div class="enterprise-icon">🏢</div>
      <h2 class="reveal">Need a custom solution?</h2>
      <p class="reveal stagger-1">We build custom enterprise solutions tailored to your specific requirements. Let's discuss your needs.</p>
      
      <div class="enterprise-features reveal stagger-2">
        <div class="enterprise-feature">
          <div class="icon">🎯</div>
          <h4>Custom Development</h4>
          <p>Built for your exact needs</p>
        </div>
        <div class="enterprise-feature">
          <div class="icon">👥</div>
          <h4>Dedicated Team</h4>
          <p>Your own development team</p>
        </div>
        <div class="enterprise-feature">
          <div class="icon">🛡️</div>
          <h4>SLA Guarantee</h4>
          <p>99.9% uptime guarantee</p>
        </div>
        <div class="enterprise-feature">
          <div class="icon">📞</div>
          <h4>24/7 Support</h4>
          <p>Always available for you</p>
        </div>
      </div>
      
      <div class="enterprise-actions reveal stagger-3">
        <a href="contact.php" class="btn-white">Contact Sales <span>→</span></a>
        <a href="services.php" class="btn-outline-white">View All Services</a>
      </div>
    </div>
  </section>

  <!-- Pricing FAQ -->
  <section class="pricing-faq-section">
    <div class="pricing-faq-container">
      <h2 class="reveal">Pricing Questions</h2>
      
      <div class="pricing-faq-list">
        <div class="pricing-faq-item reveal stagger-1">
          <h4>Do you offer a free trial?</h4>
          <p>Yes! All our products come with a 14-day free trial. No credit card required. You can test all features and see if it fits your needs.</p>
        </div>
        
        <div class="pricing-faq-item reveal stagger-2">
          <h4>Can I switch plans later?</h4>
          <p>Absolutely. You can upgrade or downgrade your plan at any time. Changes take effect at the start of your next billing cycle.</p>
        </div>
        
        <div class="pricing-faq-item reveal stagger-3">
          <h4>What payment methods do you accept?</h4>
          <p>We accept bank transfers, mobile money (MTN, Airtel), credit/debit cards, and PayPal for international clients.</p>
        </div>
        
        <div class="pricing-faq-item reveal stagger-4">
          <h4>Are there setup fees?</h4>
          <p>No setup fees for our standard products. Custom development projects may have an initial discovery fee, which is credited toward your project cost.</p>
        </div>
        
        <div class="pricing-faq-item reveal stagger-5">
          <h4>Do you offer discounts for annual payment?</h4>
          <p>Yes! Pay annually and save 20% on all our products and services. Contact us for more details.</p>
        </div>
        
        <div class="pricing-faq-item reveal stagger-6">
          <h4>Can I get a custom quote?</h4>
          <p>Of course. Every business is different. Contact us with your requirements and we'll provide a tailored quote that fits your needs and budget.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="pricing-cta">
    <div class="wrap">
      <h2 class="reveal">Ready to get started?</h2>
      <p class="reveal stagger-1">Let's find the perfect plan for your business.</p>
      <a href="contact.php" class="btn-white reveal stagger-2">Contact Us <span>→</span></a>
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
            <li><a href="product-details.php?product=pos-pro">POS Pro</a></li>
            <li><a href="product-details.php?product=manufacturing-system">Manufacturing System</a></li>
            <li><a href="product-details.php?product=savings-credit">Savings & Credit</a></li>
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
<script>
    // Billing Toggle
    const monthlyBtn = document.getElementById('monthlyBtn');
    const annualBtn = document.getElementById('annualBtn');
    const priceAmounts = document.querySelectorAll('.pricing-price-main .amount');
    const billingNotes = document.querySelectorAll('.pricing-billing-note');
    
    function updatePricing(period) {
        priceAmounts.forEach(amount => {
            const monthlyPrice = amount.getAttribute('data-monthly');
            const annualPrice = amount.getAttribute('data-annual');
            
            if (monthlyPrice && annualPrice) {
                amount.textContent = period === 'monthly' ? monthlyPrice : annualPrice;
            }
        });
        
        billingNotes.forEach(note => {
            const monthlyText = note.getAttribute('data-monthly');
            const annualText = note.getAttribute('data-annual');
            
            if (monthlyText && annualText) {
                note.textContent = period === 'monthly' ? monthlyText : annualText;
            }
        });
    }
    
    if (monthlyBtn && annualBtn) {
        monthlyBtn.addEventListener('click', () => {
            monthlyBtn.classList.add('active');
            annualBtn.classList.remove('active');
            updatePricing('monthly');
        });
        
        annualBtn.addEventListener('click', () => {
            annualBtn.classList.add('active');
            monthlyBtn.classList.remove('active');
            updatePricing('annual');
        });
    }
</script>
</body>
</html>