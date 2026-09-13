<?php
session_start();
$pageTitle = 'Product Details';
$currentPage = 'portfolio';

// Get product slug from URL
$productSlug = $_GET['product'] ?? '';

// Products data array
$products = [
    'pos-pro' => [
        'slug' => 'pos-pro',
        'name' => 'POS Pro',
        'tagline' => 'Point of Sale & Marketplace',
        'category' => 'Retail & Commerce',
        'category_icon' => '💳',
        'hero_icon' => '💳',
        'hero_description' => 'The complete point-of-sale and marketplace platform trusted by retailers, restaurants, supermarkets, and multi-store chains. Manage sales, inventory, customers, and grow your business — all from one powerful platform.',
        'overview_title' => 'Everything your retail business needs.',
        'overview_description' => 'POS Pro combines point-of-sale, inventory management, customer relationship management, and marketplace capabilities into one seamless platform.',
        'features' => [
            ['icon' => '🛒', 'title' => 'Smart Point of Sale', 'description' => 'Process sales quickly with barcode scanning, multiple payment methods, receipt printing, and offline mode support.'],
            ['icon' => '📦', 'title' => 'Inventory Management', 'description' => 'Track stock levels in real-time across multiple locations. Get automatic alerts for low stock and expiry dates.'],
            ['icon' => '👥', 'title' => 'Customer Management', 'description' => 'Build customer profiles, track purchase history, and reward loyalty with integrated points and discounts.'],
            ['icon' => '📊', 'title' => 'Sales Analytics', 'description' => 'Understand your business with detailed reports on sales, products, customers, and employee performance.'],
            ['icon' => '🏪', 'title' => 'Multi-Store Support', 'description' => 'Manage multiple locations from a single dashboard. Sync inventory, pricing, and promotions across all stores.'],
            ['icon' => '🌐', 'title' => 'Marketplace Integration', 'description' => 'Sell online through our integrated marketplace. Sync products, orders, and inventory automatically.'],
        ],
        'benefits_title' => 'Built for the way you do business.',
        'benefits_description' => 'Whether you run a single shop or a chain of stores, POS Pro is designed to grow with your business and adapt to your unique workflows.',
        'benefits' => [
            ['title' => 'Lightning Fast Checkout', 'description' => 'Process transactions in seconds, even during peak hours.'],
            ['title' => 'Works Offline', 'description' => 'Keep selling even when your internet is down — syncs automatically when back online.'],
            ['title' => 'Bank-Level Security', 'description' => 'Your data is encrypted and protected with enterprise-grade security.'],
            ['title' => '24/7 Support', 'description' => 'Our team is always available to help you succeed.'],
        ],
        'visual_title' => 'Ready to get started?',
        'visual_description' => 'Join thousands of businesses already using POS Pro to grow their revenue.',
        'visual_emoji' => '🚀',
        'pricing' => [
            [
                'name' => 'Starter',
                'price' => '$29',
                'period' => 'per month',
                'featured' => false,
                'features' => ['1 Store Location', 'Unlimited Products', 'Basic Reports', 'Email Support', 'Mobile App']
            ],
            [
                'name' => 'Business',
                'price' => '$79',
                'period' => 'per month',
                'featured' => true,
                'features' => ['Up to 5 Stores', 'Unlimited Products', 'Advanced Analytics', 'Marketplace Integration', 'Priority Support', 'Custom Reports']
            ],
            [
                'name' => 'Enterprise',
                'price' => 'Custom',
                'period' => 'contact us',
                'featured' => false,
                'features' => ['Unlimited Stores', 'Custom Features', 'Dedicated Account Manager', 'API Access', '24/7 Phone Support', 'On-premise Option']
            ]
        ],
        'cta_title' => 'Start growing your business today.',
        'cta_description' => 'Join thousands of retailers already using POS Pro.',
    ],
    
    'manufacturing-system' => [
        'slug' => 'manufacturing-system',
        'name' => 'Manufacturing Management System',
        'tagline' => 'Production Management Platform',
        'category' => 'Manufacturing',
        'category_icon' => '🏭',
        'hero_icon' => '🏭',
        'hero_description' => 'End-to-end production management system that optimizes your entire manufacturing workflow — from raw materials to finished goods. Track production, manage quality, control costs, and scale your operations with confidence.',
        'overview_title' => 'Take control of your production floor.',
        'overview_description' => 'Our Manufacturing Management System gives you complete visibility and control over every aspect of your production process.',
        'features' => [
            ['icon' => '📋', 'title' => 'Production Planning', 'description' => 'Plan production schedules, allocate resources, and optimize capacity with intelligent scheduling tools.'],
            ['icon' => '📦', 'title' => 'Inventory & Materials', 'description' => 'Track raw materials, work-in-progress, and finished goods. Automatic reorder alerts prevent stockouts.'],
            ['icon' => '✅', 'title' => 'Quality Control', 'description' => 'Implement quality checkpoints at every stage. Track defects and continuously improve production quality.'],
            ['icon' => '💰', 'title' => 'Cost Tracking', 'description' => 'Monitor production costs in real-time. Understand labor, materials, and overhead costs per unit.'],
            ['icon' => '🚚', 'title' => 'Supply Chain', 'description' => 'Manage suppliers, purchase orders, and deliveries. Integrate with your existing ERP systems.'],
            ['icon' => '📊', 'title' => 'Analytics & Reports', 'description' => 'Get detailed insights into production efficiency, OEE, downtime, and productivity metrics.'],
        ],
        'benefits_title' => 'Manufacture smarter, not harder.',
        'benefits_description' => 'Our system helps manufacturers reduce waste, increase efficiency, and deliver higher quality products to their customers.',
        'benefits' => [
            ['title' => 'Reduce Downtime', 'description' => 'Predictive maintenance and real-time monitoring keep your production running.'],
            ['title' => 'Increase Efficiency', 'description' => 'Optimize production schedules and eliminate bottlenecks.'],
            ['title' => 'Improve Quality', 'description' => 'Consistent quality control at every production stage.'],
            ['title' => 'Lower Costs', 'description' => 'Reduce waste, optimize inventory, and control production costs.'],
        ],
        'visual_title' => 'Industry 4.0 Ready',
        'visual_description' => 'Embrace smart manufacturing with IoT integration and real-time analytics.',
        'visual_emoji' => '⚙️',
        'pricing' => [
            [
                'name' => 'Basic',
                'price' => '$199',
                'period' => 'per month',
                'featured' => false,
                'features' => ['Single Production Line', 'Up to 50 Users', 'Basic Reports', 'Email Support', 'Mobile App']
            ],
            [
                'name' => 'Professional',
                'price' => '$499',
                'period' => 'per month',
                'featured' => true,
                'features' => ['Multiple Production Lines', 'Unlimited Users', 'Advanced Analytics', 'IoT Integration', 'Priority Support', 'Custom Reports']
            ],
            [
                'name' => 'Enterprise',
                'price' => 'Custom',
                'period' => 'contact us',
                'featured' => false,
                'features' => ['Unlimited Facilities', 'Custom Development', 'Dedicated Account Manager', 'API Access', '24/7 Phone Support', 'On-premise Option']
            ]
        ],
        'cta_title' => 'Ready to transform your manufacturing?',
        'cta_description' => 'Let\'s discuss how our system can optimize your production.',
    ],
    
    'savings-credit' => [
        'slug' => 'savings-credit',
        'name' => 'Savings & Credit Management System',
        'tagline' => 'Financial Institution Platform',
        'category' => 'Financial Services',
        'category_icon' => '🏦',
        'hero_icon' => '🏦',
        'hero_description' => 'A comprehensive solution for SACCOs, microfinance institutions, credit unions, and community banks. Manage members, savings, loans, and financial reporting with ease and confidence.',
        'overview_title' => 'Modern financial management for growing institutions.',
        'overview_description' => 'Streamline your operations, reduce errors, and serve your members better with our comprehensive platform.',
        'features' => [
            ['icon' => '👥', 'title' => 'Member Management', 'description' => 'Complete member profiles, KYC documentation, and communication tools to keep you connected.'],
            ['icon' => '💰', 'title' => 'Savings Management', 'description' => 'Handle multiple account types, interest calculations, deposits, withdrawals, and statements.'],
            ['icon' => '📝', 'title' => 'Loan Management', 'description' => 'Full loan lifecycle from application to approval, disbursement, and repayment tracking.'],
            ['icon' => '📊', 'title' => 'Financial Reports', 'description' => 'Generate balance sheets, income statements, aging reports, and regulatory reports.'],
            ['icon' => '💳', 'title' => 'Mobile Banking', 'description' => 'Members can check balances, transfer funds, and apply for loans from their phones.'],
            ['icon' => '🔒', 'title' => 'Security & Compliance', 'description' => 'Bank-level security, audit trails, and regulatory compliance built-in.'],
        ],
        'benefits_title' => 'Built for financial institutions.',
        'benefits_description' => 'Our system is designed specifically for the unique needs of SACCOs, MFIs, and credit unions in emerging markets.',
        'benefits' => [
            ['title' => 'Regulatory Compliance', 'description' => 'Meet all regulatory requirements with built-in compliance tools.'],
            ['title' => 'Multi-Branch Support', 'description' => 'Manage multiple branches from one central system.'],
            ['title' => 'SMS Notifications', 'description' => 'Automatic SMS alerts for transactions and loan reminders.'],
            ['title' => 'Offline Mode', 'description' => 'Keep operating during internet outages with offline mode.'],
        ],
        'visual_title' => 'Empowering financial inclusion.',
        'visual_description' => 'Help your community grow through accessible financial services.',
        'visual_emoji' => '🌍',
        'pricing' => [
            [
                'name' => 'Small',
                'price' => '$149',
                'period' => 'per month',
                'featured' => false,
                'features' => ['Up to 500 Members', 'Basic Reports', 'SMS Notifications', 'Email Support', 'Mobile App']
            ],
            [
                'name' => 'Growing',
                'price' => '$349',
                'period' => 'per month',
                'featured' => true,
                'features' => ['Up to 5,000 Members', 'Advanced Reports', 'Multi-Branch', 'Mobile Banking', 'Priority Support', 'API Access']
            ],
            [
                'name' => 'Enterprise',
                'price' => 'Custom',
                'period' => 'contact us',
                'featured' => false,
                'features' => ['Unlimited Members', 'Custom Development', 'Dedicated Account Manager', 'Regulatory Compliance', '24/7 Phone Support', 'On-premise Option']
            ]
        ],
        'cta_title' => 'Ready to modernize your institution?',
        'cta_description' => 'Let\'s discuss how we can help you serve your members better.',
    ]
];

// Check if product exists
if (!isset($products[$productSlug])) {
    header('Location: portfolio.php');
    exit;
}

$product = $products[$productSlug];
$pageTitle = $product['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name']); ?> | WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ PRODUCT DETAILS PAGE STYLES ============ */
    
    /* Product Hero */
    .product-hero {
        background: var(--midnight);
        padding: 140px 0 100px;
        position: relative;
        overflow: hidden;
    }
    
    .product-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(circle, rgba(37,99,255,0.2) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.1); opacity: 1; }
    }
    
    .product-hero-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.6);
        font-weight: 500;
        margin-bottom: 30px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .back-link:hover {
        color: #fff;
        transform: translateX(-5px);
    }
    
    .product-tag {
        display: inline-block;
        padding: 8px 16px;
        background: var(--grad);
        color: #fff;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .product-hero-content h1 {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin-bottom: 20px;
    }
    
    .product-hero-content h1 .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .product-hero-content > p {
        color: rgba(255,255,255,0.7);
        font-size: 1.15rem;
        line-height: 1.7;
        margin-bottom: 30px;
    }
    
    .product-hero-actions {
        display: flex;
        gap: 15px;
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
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
    }
    
    .btn-outline {
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
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-outline:hover {
        border-color: #fff;
        transform: translateY(-2px);
    }
    
    .product-hero-visual {
        background: var(--grad);
        border-radius: 20px;
        padding: 40px;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        box-shadow: 0 20px 60px rgba(37,99,255,0.3);
        position: relative;
        overflow: hidden;
    }
    
    .product-hero-visual::before {
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
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    /* Product Sections */
    .product-section {
        padding: 100px 0;
    }
    
    .product-section.alt {
        background: var(--light);
    }
    
    .product-section-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .product-section-head {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 60px;
    }
    
    .product-section-head h2 {
        font-size: clamp(1.85rem, 3.4vw, 2.6rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        line-height: 1.22;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .product-section-head p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.6;
    }
    
    /* Features Grid */
    .features-grid-product {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }
    
    .feature-card-product {
        background: var(--white);
        border: 1px solid var(--line-light);
        border-radius: 16px;
        padding: 35px 30px;
        transition: all 0.3s ease;
    }
    
    .feature-card-product:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    
    .feature-icon-product {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .feature-card-product:hover .feature-icon-product {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .feature-card-product h3 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--ink);
    }
    
    .feature-card-product p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    /* Benefits Section */
    .benefits-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    
    .benefits-content h2 {
        font-size: clamp(1.85rem, 3.4vw, 2.4rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        line-height: 1.25;
        margin-bottom: 20px;
        color: var(--ink);
    }
    
    .benefits-content > p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 30px;
    }
    
    .benefits-list {
        list-style: none;
    }
    
    .benefit-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .benefit-item:hover {
        background: var(--light);
        transform: translateX(5px);
    }
    
    .benefit-check {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: bold;
    }
    
    .benefit-text h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--ink);
    }
    
    .benefit-text p {
        color: var(--ink-mute);
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }
    
    .benefits-visual {
        background: var(--grad);
        border-radius: 20px;
        padding: 60px 40px;
        min-height: 450px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    
    .benefits-visual::before {
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
    
    .benefits-visual h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }
    
    .benefits-visual p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.9);
        position: relative;
        z-index: 1;
    }
    
    .visual-emoji {
        font-size: 5rem;
        margin-top: 30px;
        position: relative;
        z-index: 1;
    }
    
    /* Pricing */
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }
    
    .pricing-card {
        background: var(--white);
        border: 2px solid var(--line-light);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .pricing-card.featured {
        border-color: var(--blue);
        box-shadow: 0 20px 40px rgba(37,99,255,0.15);
    }
    
    .pricing-card.featured::before {
        content: 'POPULAR';
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--grad);
        color: #fff;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    
    .pricing-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    
    .pricing-price {
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 5px;
    }
    
    .pricing-period {
        color: var(--ink-mute);
        font-size: 0.9rem;
        margin-bottom: 25px;
    }
    
    .pricing-features {
        list-style: none;
        text-align: left;
        margin-bottom: 30px;
    }
    
    .pricing-features li {
        padding: 10px 0;
        color: var(--ink-mute);
        font-size: 0.9rem;
        padding-left: 28px;
        position: relative;
    }
    
    .pricing-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .btn-primary-full {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: var(--grad);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary-full:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    /* CTA */
    .product-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .product-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .product-cta p {
        color: rgba(255,255,255,0.9);
        font-size: 1.05rem;
        margin-bottom: 30px;
    }
    
    /* Responsive */
    @media (max-width: 920px) {
        .product-hero-container,
        .benefits-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .product-hero-visual {
            order: -1;
            min-height: 300px;
            font-size: 6rem;
        }
    }
    
    @media (max-width: 768px) {
        .product-hero-container,
        .product-section-container {
            padding: 0 20px;
        }
        
        .pricing-grid {
            grid-template-columns: 1fr;
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
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Product Hero -->
  <section class="product-hero">
    <div class="product-hero-container">
      <div class="product-hero-content reveal-left">
        <a href="portfolio.php" class="back-link">← Back to Portfolio</a>
        <span class="product-tag"><?php echo $product['category_icon'] . ' ' . htmlspecialchars($product['category']); ?></span>
        <h1><?php echo htmlspecialchars($product['name']); ?> <span class="accent">— <?php echo htmlspecialchars($product['tagline']); ?></span></h1>
        <p><?php echo htmlspecialchars($product['hero_description']); ?></p>
        
        <div class="product-hero-actions">
          <a href="contact.php" class="btn-white">Request Demo <span>→</span></a>
          <a href="#features" class="btn-outline">Explore Features</a>
        </div>
      </div>
      
      <div class="product-hero-visual reveal-right">
        <?php echo $product['hero_icon']; ?>
      </div>
    </div>
  </section>

  <!-- Overview & Features -->
  <section class="product-section">
    <div class="product-section-container">
      <div class="product-section-head reveal">
        <h2><?php echo htmlspecialchars($product['overview_title']); ?></h2>
        <p><?php echo htmlspecialchars($product['overview_description']); ?></p>
      </div>
      
      <div class="features-grid-product" id="features">
        <?php foreach ($product['features'] as $index => $feature): ?>
          <div class="feature-card-product reveal-zoom stagger-<?php echo ($index % 6) + 1; ?>">
            <div class="feature-icon-product"><?php echo $feature['icon']; ?></div>
            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
            <p><?php echo htmlspecialchars($feature['description']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Benefits -->
  <section class="product-section alt">
    <div class="product-section-container">
      <div class="benefits-container">
        <div class="benefits-content reveal-left">
          <h2><?php echo htmlspecialchars($product['benefits_title']); ?></h2>
          <p><?php echo htmlspecialchars($product['benefits_description']); ?></p>
          
          <ul class="benefits-list">
            <?php foreach ($product['benefits'] as $benefit): ?>
              <li class="benefit-item">
                <span class="benefit-check">✓</span>
                <div class="benefit-text">
                  <h4><?php echo htmlspecialchars($benefit['title']); ?></h4>
                  <p><?php echo htmlspecialchars($benefit['description']); ?></p>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        
        <div class="benefits-visual reveal-right">
          <h3><?php echo htmlspecialchars($product['visual_title']); ?></h3>
          <p><?php echo htmlspecialchars($product['visual_description']); ?></p>
          <div class="visual-emoji"><?php echo $product['visual_emoji']; ?></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing -->
  <?php if (!empty($product['pricing'])): ?>
  <section class="product-section">
    <div class="product-section-container">
      <div class="product-section-head reveal">
        <h2>Simple, transparent pricing.</h2>
        <p>Choose the plan that fits your business. All plans include a 14-day free trial.</p>
      </div>
      
      <div class="pricing-grid">
        <?php foreach ($product['pricing'] as $index => $plan): ?>
          <div class="pricing-card <?php echo $plan['featured'] ? 'featured' : ''; ?> reveal-zoom stagger-<?php echo $index + 1; ?>">
            <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
            <div class="pricing-price"><?php echo htmlspecialchars($plan['price']); ?></div>
            <div class="pricing-period"><?php echo htmlspecialchars($plan['period']); ?></div>
            <ul class="pricing-features">
              <?php foreach ($plan['features'] as $feature): ?>
                <li><?php echo htmlspecialchars($feature); ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="contact.php" class="btn-primary-full">
              <?php echo $plan['price'] === 'Custom' ? 'Contact Sales' : 'Get Started'; ?>
              <span>→</span>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- CTA -->
  <section class="product-cta">
    <div class="wrap">
      <h2 class="reveal"><?php echo htmlspecialchars($product['cta_title']); ?></h2>
      <p class="reveal stagger-1"><?php echo htmlspecialchars($product['cta_description']); ?></p>
      <a href="contact.php" class="btn-white reveal stagger-2">Request Free Demo <span>→</span></a>
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
</body>
</html>