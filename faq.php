<?php
session_start();
$pageTitle = 'FAQ';
$currentPage = 'faq';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ FAQ PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .faq-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .faq-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(34,211,238,0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .faq-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .faq-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .faq-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .faq-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Search Bar */
    .faq-search-section {
        padding: 40px 0;
        background: var(--white);
        border-bottom: 1px solid var(--line-light);
    }
    
    .faq-search-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .faq-search-box {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--light);
        padding: 16px 24px;
        border-radius: 12px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .faq-search-box:focus-within {
        border-color: var(--blue);
        box-shadow: 0 0 0 4px rgba(37,99,255,0.1);
        background: var(--white);
    }
    
    .faq-search-box .search-icon {
        font-size: 1.3rem;
        color: var(--ink-mute);
    }
    
    .faq-search-box input {
        flex: 1;
        border: none;
        background: transparent;
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        color: var(--ink);
        outline: none;
    }
    
    .faq-search-box input::placeholder {
        color: var(--ink-mute);
    }
    
    /* FAQ Categories */
    .faq-categories {
        padding: 30px 0;
        border-bottom: 1px solid var(--line-light);
        position: sticky;
        top: 70px;
        z-index: 100;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(10px);
    }
    
    .faq-categories-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .faq-category-btn {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid var(--line-light);
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        font-family: 'Manrope', sans-serif;
        font-size: 0.9rem;
        color: var(--ink-mute);
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .faq-category-btn:hover,
    .faq-category-btn.active {
        background: var(--grad);
        border-color: transparent;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.2);
    }
    
    /* FAQ Main Content */
    .faq-main {
        padding: 80px 0;
        background: var(--white);
    }
    
    .faq-main-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .faq-category-section {
        margin-bottom: 60px;
    }
    
    .faq-category-section:last-child {
        margin-bottom: 0;
    }
    
    .faq-category-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.015em;
    }
    
    .faq-category-title .icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .faq-item {
        background: var(--white);
        border: 1px solid var(--line-light);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        border-color: rgba(37,99,255,0.3);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .faq-item.active {
        border-color: var(--blue);
        box-shadow: 0 10px 25px rgba(37,99,255,0.1);
    }
    
    .faq-question {
        padding: 22px 25px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--ink);
        transition: all 0.3s ease;
        user-select: none;
    }
    
    .faq-question:hover {
        color: var(--blue);
    }
    
    .faq-item.active .faq-question {
        color: var(--blue);
    }
    
    .faq-toggle-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--ink);
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    
    .faq-item.active .faq-toggle-icon {
        background: var(--grad);
        color: #fff;
        transform: rotate(45deg);
    }
    
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.4s ease;
        padding: 0 25px;
    }
    
    .faq-item.active .faq-answer {
        max-height: 1500px;
        padding: 0 25px 22px;
    }
    
    .faq-answer p {
        color: var(--ink-mute);
        font-size: 0.98rem;
        line-height: 1.7;
        margin-bottom: 12px;
    }
    
    .faq-answer p:last-child {
        margin-bottom: 0;
    }
    
    .faq-answer ul {
        list-style: none;
        margin: 12px 0;
    }
    
    .faq-answer ul li {
        padding: 6px 0 6px 24px;
        color: var(--ink-mute);
        font-size: 0.95rem;
        position: relative;
        line-height: 1.6;
    }
    
    .faq-answer ul li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .faq-answer ul ul {
        margin: 6px 0;
        padding-left: 20px;
    }
    
    .faq-answer ul ul li {
        font-size: 0.9rem;
        padding-left: 20px;
    }
    
    .faq-answer ul ul li::before {
        content: '•';
        color: var(--blue);
    }
    
    .faq-answer strong {
        color: var(--ink);
        font-weight: 700;
    }
    
    .faq-answer a {
        color: var(--blue);
        font-weight: 600;
        text-decoration: underline;
    }
    
    .faq-answer a:hover {
        color: var(--purple);
    }
    
    /* No Results */
    .faq-no-results {
        text-align: center;
        padding: 60px 20px;
        display: none;
    }
    
    .faq-no-results.active {
        display: block;
    }
    
    .faq-no-results .icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    
    .faq-no-results h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    
    .faq-no-results p {
        color: var(--ink-mute);
    }
    
    /* Contact CTA */
    .faq-contact-cta {
        padding: 80px 0;
        background: var(--light);
        text-align: center;
    }
    
    .faq-contact-cta-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .faq-contact-cta h2 {
        font-size: clamp(1.85rem, 3.4vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .faq-contact-cta p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.65;
        margin-bottom: 30px;
    }
    
    .faq-contact-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-primary {
        background: var(--grad);
        color: #fff;
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.98rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .btn-secondary {
        background: transparent;
        color: var(--blue);
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.98rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 2px solid var(--blue);
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: var(--blue);
        color: #fff;
        transform: translateY(-2px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .faq-search-container,
        .faq-categories-container,
        .faq-main-container,
        .faq-contact-cta-container {
            padding: 0 20px;
        }
        
        .faq-question {
            padding: 18px 20px;
            font-size: 1rem;
        }
        
        .faq-item.active .faq-answer {
            padding: 0 20px 18px;
        }
        
        .faq-categories {
            top: 62px;
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
      <li><a href="portfolio.php" >Portfolio</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" class="active">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
          <li><a href="faq.php" class="active">FAQ</a></li>
        </ul>
      </li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Page Header -->
  <section class="faq-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Help Center</div>
      <h1 class="faq-page-title reveal reveal-2">Frequently Asked <span class="accent">Questions</span>.</h1>
      <p class="faq-page-subtitle reveal reveal-3">Find answers to common questions about our services, products, and how we work.</p>
    </div>
  </section>

  <!-- Search -->
  <section class="faq-search-section">
    <div class="faq-search-container">
      <div class="faq-search-box">
        <span class="search-icon">🔍</span>
        <input type="text" id="faqSearch" placeholder="Search for answers...">
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section class="faq-categories">
    <div class="faq-categories-container">
      <button class="faq-category-btn active" data-category="all">All Questions</button>
      <button class="faq-category-btn" data-category="general">General</button>
      <button class="faq-category-btn" data-category="services">Services</button>
      <button class="faq-category-btn" data-category="products">Products</button>
      <button class="faq-category-btn" data-category="pricing">Pricing</button>
      <button class="faq-category-btn" data-category="technical">Technical</button>
      <button class="faq-category-btn" data-category="support">Support</button>
    </div>
  </section>

  <!-- FAQ Content -->
  <section class="faq-main">
    <div class="faq-main-container">
      
      <!-- General -->
      <div class="faq-category-section" data-category="general">
        <h2 class="faq-category-title">
          <span class="icon">💡</span>
          General Questions
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              What does WayronX do?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>WayronX is a technology company that builds intelligent digital solutions for businesses. We specialize in three core areas:</p>
              <ul>
                <li><strong>AI & Intelligent Solutions</strong> — Machine learning, predictive analytics, and automation powered by AI</li>
                <li><strong>Digital Platforms</strong> — Custom web and mobile applications, portals, and marketplaces</li>
                <li><strong>Business Automation</strong> — Workflow automation, system integration, and process optimization</li>
              </ul>
              <p>We also build and sell enterprise products like POS Pro, Manufacturing Management System, and Savings & Credit Management System. Our goal is simple: help businesses solve real problems, simplify operations, and grow faster with technology that actually works.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Where is WayronX located?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Our headquarters are in <strong>Gulu, Uganda</strong>, in the heart of East Africa. However, we work with clients globally — from startups in Nairobi to enterprises in London and New York.</p>
              <p>Our team operates remotely and collaborates across time zones, so distance is never a barrier to great work. We use modern collaboration tools to stay connected with clients wherever they are.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              How long has WayronX been in business?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>WayronX was founded in <strong>2026</strong> but the team has been building technology solutions for now over <strong>5 years</strong>.</p>
              <p>In that time, we've:</p>
              <ul>
                <li>Delivered <strong>10+ projects</strong> for businesses of all sizes</li>
                <li>Served clients in <strong>6+ countries</strong> across Africa, Europe, and North America</li>
                <li>Grown our team to <strong>10+ specialists</strong> in engineering, design, and consulting</li>
                <li>Built and launched <strong>3 flagship enterprise products</strong></li>
              </ul>
              <p>Our five years of experience means we've seen technology trends come and go — and we know what actually delivers value.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              What industries do you serve?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We work across a wide range of industries, but we have deep expertise in:</p>
              <ul>
                <li><strong>Retail & E-commerce</strong> — POS systems, marketplaces, inventory, and customer loyalty</li>
                <li><strong>Manufacturing & Production</strong> — Production planning, quality control, and supply chain</li>
                <li><strong>Financial Services</strong> — SACCOs, microfinance institutions, credit unions, and community banks</li>
                <li><strong>Healthcare</strong> — Patient management, telemedicine, and health records</li>
                <li><strong>Logistics & Transportation</strong> — Fleet management, route optimization, and delivery tracking</li>
                <li><strong>Education</strong> — Learning management systems and educational technology</li>
                <li><strong>Government & Public Sector</strong> — Digital services, citizen portals, and process automation</li>
              </ul>
              <p>If your industry isn't listed, reach out — we love tackling new challenges.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Who are your typical clients?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Our clients range from ambitious startups to established enterprises. They typically fall into three groups:</p>
              <ul>
                <li><strong>Growing SMEs</strong> looking to digitize operations and scale efficiently</li>
                <li><strong>Established companies</strong> needing to modernize legacy systems or automate processes</li>
                <li><strong>Financial institutions</strong> (SACCOs, MFIs, banks) seeking reliable management platforms</li>
              </ul>
              <p>What they all share is a belief that technology should solve real business problems — not just look good on paper.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Services -->
      <div class="faq-category-section" data-category="services">
        <h2 class="faq-category-title">
          <span class="icon">🛠️</span>
          Services
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              What services does WayronX offer?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We offer a comprehensive range of technology services:</p>
              <ul>
                <li><strong>AI & Intelligent Solutions</strong> — Predictive analytics, NLP, computer vision, and chatbots</li>
                <li><strong>Digital Platform Development</strong> — Custom web apps, mobile apps, and marketplaces</li>
                <li><strong>Business Process Automation</strong> — Workflow automation, system integration, and reporting</li>
                <li><strong>Cloud Solutions</strong> — Migration, optimization, DevOps, and cloud security</li>
                <li><strong>Custom Software Development</strong> — Tailored solutions for unique business needs</li>
                <li><strong>Mobile App Development</strong> — Native iOS, Android, and cross-platform apps</li>
                <li><strong>Data Analytics</strong> — BI dashboards, data visualization, and reporting</li>
                <li><strong>Cybersecurity</strong> — Audits, penetration testing, and compliance</li>
                <li><strong>Technology Consulting</strong> — Strategy, architecture, and digital transformation</li>
              </ul>
              <p>Whether you need a small feature or a complete platform, we can help.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              How do I start a project with WayronX?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Getting started is simple and low-risk. Here's how it works:</p>
              <ul>
                <li><strong>Step 1: Reach out</strong> — Contact us through our <a href="contact.php">contact form</a>, email, or phone</li>
                <li><strong>Step 2: Free consultation</strong> — We schedule a call to understand your goals, challenges, and timeline</li>
                <li><strong>Step 3: Proposal</strong> — Within 3-5 days, we send a detailed proposal with scope, timeline, and pricing</li>
                <li><strong>Step 4: Kickoff</strong> — Once approved, we set up a kickoff meeting and start work</li>
                <li><strong>Step 5: Build & iterate</strong> — We work in sprints with regular check-ins and demos</li>
              </ul>
              <p>The initial consultation is completely free — no strings attached.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you work with startups?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Absolutely — startups are some of our favorite clients. We understand the unique pressures of early-stage companies: tight budgets, aggressive timelines, and constant pivoting.</p>
              <p>Here's how we help startups:</p>
              <ul>
                <li><strong>MVP development</strong> — Fast, focused builds to test your idea with real users</li>
                <li><strong>Flexible pricing</strong> — Startup-friendly rates and phased payments</li>
                <li><strong>Technical guidance</strong> — Help choosing the right stack and architecture</li>
                <li><strong>Scalability planning</strong> — Build for today but design for tomorrow</li>
                <li><strong>Investor-ready code</strong> — Clean, documented, and ready for due diligence</li>
              </ul>
              <p>We've helped dozens of startups go from idea to funded product. Tell us what you're building.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Can you work with our existing systems?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — and we actually prefer it. Most of our clients have existing systems they've invested in, and ripping them out isn't practical or cost-effective.</p>
              <p>We have extensive experience integrating with:</p>
              <ul>
                <li><strong>Databases</strong> — MySQL, PostgreSQL, MongoDB, SQL Server, Oracle</li>
                <li><strong>ERP & CRM systems</strong> — SAP, Salesforce, HubSpot, Zoho, custom ERPs</li>
                <li><strong>Payment gateways</strong> — Stripe, PayPal, Flutterwave, MTN MoMo, Airtel Money</li>
                <li><strong>Accounting software</strong> — QuickBooks, Xero, Sage, Tally</li>
                <li><strong>APIs & webhooks</strong> — Any system with a modern API</li>
                <li><strong>Legacy systems</strong> — Even older systems — we can build bridges</li>
              </ul>
              <p>Our approach is always: understand what you have, connect what makes sense, and build only what's missing.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you offer ongoing support after launch?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — and we strongly recommend it. Launching is just the beginning; software needs care and attention to keep performing well.</p>
              <p>Our support offerings include:</p>
              <ul>
                <li><strong>Bug fixes</strong> — Free for 30 days after launch, then via support plan</li>
                <li><strong>Security updates</strong> — Regular patches to keep your system safe</li>
                <li><strong>Performance monitoring</strong> — 24/7 uptime monitoring and alerts</li>
                <li><strong>Feature enhancements</strong> — Ongoing improvements as your business evolves</li>
                <li><strong>Content updates</strong> — For content-driven platforms</li>
                <li><strong>Backups & disaster recovery</strong> — Automated, tested, and reliable</li>
              </ul>
              <p>Support plans range from basic email support to full 24/7 dedicated support — details on our <a href="pricing.php">pricing page</a>.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Products -->
      <div class="faq-category-section" data-category="products">
        <h2 class="faq-category-title">
          <span class="icon">📦</span>
          Products
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              What products does WayronX offer?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We currently offer three flagship enterprise products, each designed for a specific industry need:</p>
              <ul>
                <li><strong>POS Pro</strong> — A complete point-of-sale and marketplace platform for retailers, restaurants, supermarkets, and multi-store chains. Handles sales, inventory, customers, and analytics.</li>
                <li><strong>Manufacturing Management System</strong> — An end-to-end production management platform. Tracks production planning, quality control, materials, and costs — perfect for factories and manufacturers.</li>
                <li><strong>Savings & Credit Management System</strong> — A purpose-built platform for SACCOs, microfinance institutions, and credit unions. Manages members, savings, loans, and regulatory reporting.</li>
              </ul>
              <p>Each product can be customized to match your specific workflows, and we offer free trials on all of them.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Can I try before I buy?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — we offer a <strong>14-day free trial</strong> on every product. No credit card required, no obligations.</p>
              <p>During the trial you get:</p>
              <ul>
                <li>Full access to all features</li>
                <li>Sample data to test workflows</li>
                <li>Guided onboarding session with our team</li>
                <li>Ability to import your own data</li>
                <li>Direct support from our team</li>
              </ul>
              <p>We also offer <strong>live demos</strong> where you can see the product in action and ask questions. <a href="contact.php">Request a demo</a> — it takes just 30 minutes.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Can products be customized to fit my business?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — customization is one of our strengths. Every business has unique workflows, terminology, and reporting needs. We don't force you into a one-size-fits-all box.</p>
              <p>Customizations available include:</p>
              <ul>
                <li><strong>Branding</strong> — Your logo, colors, and domain</li>
                <li><strong>Workflows</strong> — Custom approval chains and process flows</li>
                <li><strong>Fields & forms</strong> — Additional data fields specific to your industry</li>
                <li><strong>Reports</strong> — Custom reports matching your formats</li>
                <li><strong>Integrations</strong> — Connect to your existing tools</li>
                <li><strong>Modules</strong> — Add new features or entire modules</li>
              </ul>
              <p>Customization is included in Enterprise plans or available as a separate service for other tiers.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do your products support multiple users and roles?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — all our products come with robust user management built-in. You can:</p>
              <ul>
                <li><strong>Create unlimited users</strong> (some plans have limits)</li>
                <li><strong>Assign roles</strong> — Admin, Manager, Cashier, Viewer, etc.</li>
                <li><strong>Set permissions</strong> — Control what each role can see and do</li>
                <li><strong>Track activity</strong> — Full audit trail of who did what and when</li>
                <li><strong>Multi-location access</strong> — Manage multiple branches from one account</li>
                <li><strong>Single Sign-On (SSO)</strong> — Available for enterprise clients</li>
              </ul>
              <p>Role-based access is essential for security and compliance — we take it seriously.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Pricing -->
      <div class="faq-category-section" data-category="pricing">
        <h2 class="faq-category-title">
          <span class="icon">💰</span>
          Pricing & Billing
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              How much do your services cost?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Pricing depends on the scope and complexity of your project. We offer three engagement models:</p>
              <ul>
                <li><strong>Fixed Price</strong> — Best for well-defined projects with clear scope. You pay one agreed amount.</li>
                <li><strong>Time & Materials</strong> — Best for evolving requirements. You pay for hours worked at an agreed rate.</li>
                <li><strong>Monthly Retainer</strong> — Best for ongoing work. You get a set number of hours each month.</li>
              </ul>
              <p>For our products, pricing starts at:</p>
              <ul>
                <li>POS Pro — from <strong>$29/month</strong></li>
                <li>Manufacturing System — from <strong>$199/month</strong></li>
                <li>Savings & Credit System — from <strong>$149/month</strong></li>
              </ul>
              <p>Visit our <a href="pricing.php">pricing page</a> for full details, or <a href="contact.php">contact us</a> for a custom quote.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you offer payment plans?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — we understand that large projects need flexible payment structures. For fixed-price projects, we typically use:</p>
              <ul>
                <li><strong>30% upfront</strong> — To kick off the project</li>
                <li><strong>40% at midpoint</strong> — Once key milestones are approved</li>
                <li><strong>30% on completion</strong> — After final delivery and testing</li>
              </ul>
              <p>For products (POS Pro, Manufacturing, Savings & Credit), we offer:</p>
              <ul>
                <li><strong>Monthly billing</strong> — Pay as you go</li>
                <li><strong>Annual billing</strong> — Save 20% by paying upfront</li>
                <li><strong>Quarterly billing</strong> — A middle-ground option for some plans</li>
              </ul>
              <p>We're happy to discuss custom arrangements for larger engagements.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Are there any hidden fees?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p><strong>No.</strong> We believe in total transparency, and we put everything in writing before you commit.</p>
              <p>Our policy:</p>
              <ul>
                <li><strong>All costs outlined upfront</strong> in your proposal</li>
                <li><strong>No surprise charges</strong> for scope that was already agreed</li>
                <li><strong>Change requests</strong> are always discussed and approved before work continues</li>
                <li><strong>No setup fees</strong> for our standard products</li>
                <li><strong>No hidden "platform" fees</strong> — the price you see is the price you pay</li>
              </ul>
              <p>If anything changes, you'll know about it in advance — never after the fact.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              What payment methods do you accept?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We accept a variety of payment methods to make it easy for clients around the world:</p>
              <ul>
                <li><strong>Bank transfers</strong> — Local and international</li>
                <li><strong>Mobile money</strong> — MTN MoMo, Airtel Money (for East African clients)</li>
                <li><strong>Credit & debit cards</strong> — Visa, Mastercard, American Express</li>
                <li><strong>PayPal</strong> — For international clients</li>
                <li><strong>Stripe</strong> — For recurring subscription payments</li>
                <li><strong>Cheques</strong> — For local clients with established relationships</li>
              </ul>
              <p>For enterprise clients, we also support purchase orders and NET-30/60 terms.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you offer refunds?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — we offer refunds under the following conditions:</p>
              <ul>
                <li><strong>Products:</strong> Full refund within 30 days if you're not satisfied, no questions asked</li>
                <li><strong>Services:</strong> We'll refund any work not yet started; completed work is non-refundable</li>
                <li><strong>Retainers:</strong> Unused hours roll over for 30 days; refunds available on unused balances</li>
              </ul>
              <p>We'd rather earn your trust through quality work than lock you into a contract. If something isn't working, let's talk about it.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Technical -->
      <div class="faq-category-section" data-category="technical">
        <h2 class="faq-category-title">
          <span class="icon">⚙️</span>
          Technical Questions
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              What technologies do you use?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We use modern, proven technologies chosen for reliability, performance, and long-term support. Our core stack includes:</p>
              <ul>
                <li><strong>Frontend:</strong> React, Vue.js, Next.js, HTML5, CSS3, JavaScript/TypeScript</li>
                <li><strong>Backend:</strong> PHP (Laravel), Node.js, Python (Django/Flask), .NET</li>
                <li><strong>Databases:</strong> MySQL, PostgreSQL, MongoDB, Redis</li>
                <li><strong>Cloud:</strong> AWS, Microsoft Azure, Google Cloud, DigitalOcean</li>
                <li><strong>Mobile:</strong> React Native, Flutter, native iOS (Swift), native Android (Kotlin)</li>
                <li><strong>DevOps:</strong> Docker, Kubernetes, CI/CD pipelines (GitHub Actions, GitLab CI)</li>
                <li><strong>AI/ML:</strong> TensorFlow, PyTorch, OpenAI APIs, custom models</li>
              </ul>
              <p>We always recommend the right tool for the job — not the newest hype. If you have an existing stack, we work with it.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Is my data secure?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — security is a top priority in everything we build. We implement multiple layers of protection:</p>
              <ul>
                <li><strong>Encryption at rest and in transit</strong> — AES-256 and TLS 1.3</li>
                <li><strong>Regular security audits</strong> — Quarterly penetration testing</li>
                <li><strong>Role-based access control</strong> — Users only see what they should</li>
                <li><strong>Audit trails</strong> — Every action is logged and traceable</li>
                <li><strong>Regular backups</strong> — Automated daily backups with offsite storage</li>
                <li><strong>Compliance-ready</strong> — GDPR, ISO 27001, and local regulations</li>
                <li><strong>Secure development</strong> — OWASP Top 10 best practices followed</li>
              </ul>
              <p>For financial institutions (Savings & Credit), we go beyond standard security with additional controls required by regulators.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do your products work offline?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — we know internet isn't always reliable, especially in emerging markets. Our key products support <strong>offline mode</strong>:</p>
              <ul>
                <li><strong>POS Pro</strong> — Continue making sales, printing receipts, and managing inventory even without internet. Data syncs automatically when back online.</li>
                <li><strong>Savings & Credit System</strong> — Process deposits, withdrawals, and loan repayments offline. Syncs when connected.</li>
                <li><strong>Manufacturing System</strong> — Production floor continues running offline; syncing occurs at the next opportunity.</li>
              </ul>
              <p>We use a local-first architecture with conflict resolution to ensure your data stays consistent, even across multiple devices operating offline.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Can I integrate your products with other software?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Absolutely — we believe in an open ecosystem, not lock-in. All our products offer:</p>
              <ul>
                <li><strong>REST APIs</strong> — Complete API access to all data and functions</li>
                <li><strong>Webhooks</strong> — Real-time event notifications</li>
                <li><strong>Pre-built integrations</strong> — With popular tools:
                  <ul>
                    <li>QuickBooks, Xero, Sage (accounting)</li>
                    <li>Stripe, PayPal, Flutterwave (payments)</li>
                    <li>Mailchimp, SendGrid (email)</li>
                    <li>Twilio (SMS)</li>
                    <li>Google Analytics, Mixpanel (analytics)</li>
                  </ul>
                </li>
                <li><strong>Custom integrations</strong> — We can build connectors to any system with an API</li>
              </ul>
              <p>If you need to connect our product to a system we don't currently support, let's talk — we love a good integration challenge.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              How do you handle backups and disaster recovery?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We treat data protection as non-negotiable. Our approach:</p>
              <ul>
                <li><strong>Automated daily backups</strong> — Every night, your data is backed up</li>
                <li><strong>Offsite storage</strong> — Backups stored in geographically separate locations</li>
                <li><strong>Point-in-time recovery</strong> — Restore to any moment in the last 30 days</li>
                <li><strong>Tested restoration</strong> — We regularly test that our backups actually work</li>
                <li><strong>Disaster recovery plan</strong> — Documented, practiced, and reliable</li>
                <li><strong>99.9% uptime SLA</strong> — For enterprise clients</li>
              </ul>
              <p>For on-premise deployments, we set up your backups with equal rigor. You'll never lose your data.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Support -->
      <div class="faq-category-section" data-category="support">
        <h2 class="faq-category-title">
          <span class="icon">💬</span>
          Support & Maintenance
        </h2>
        
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question">
              What kind of support do you provide?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>We offer multiple support tiers, so you can choose the level that matches your needs:</p>
              <ul>
                <li><strong>Email Support</strong> — Response within 24 hours (business days)</li>
                <li><strong>Priority Support</strong> — Response within 4 hours</li>
                <li><strong>24/7 Support</strong> — Phone, live chat, and email anytime</li>
                <li><strong>Dedicated Support</strong> — Assigned account manager + 24/7 access</li>
              </ul>
              <p>All support plans include:</p>
              <ul>
                <li>Access to our knowledge base and documentation</li>
                <li>Video tutorials and guides</li>
                <li>Bug fixes and patches</li>
                <li>Security updates</li>
              </ul>
              <p>Support is available in English; other languages available for enterprise clients.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you provide training for your products?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes — we want your team to be confident using our products from day one. All products include:</p>
              <ul>
                <li><strong>Written documentation</strong> — User guides, admin manuals, and FAQs</li>
                <li><strong>Video tutorials</strong> — Step-by-step walkthroughs</li>
                <li><strong>Onboarding session</strong> — 1-2 hour live session with our team</li>
                <li><strong>Sample data</strong> — Practice environment to explore safely</li>
                <li><strong>Post-launch check-in</strong> — We follow up after 2 weeks</li>
              </ul>
              <p>For Enterprise clients we offer:</p>
              <ul>
                <li>In-person training (on-site or at our office)</li>
                <li>Train-the-trainer programs</li>
                <li>Custom training materials</li>
                <li>Refresher sessions as needed</li>
              </ul>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              What happens after the project is delivered?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Delivery isn't the end — it's the beginning of our long-term relationship. After delivery:</p>
              <ul>
                <li><strong>30-day warranty</strong> — Free bug fixes for any issues in that period</li>
                <li><strong>Handover</strong> — Full documentation, code, and credentials transferred to you</li>
                <li><strong>Support plan</strong> — Optional ongoing support if you want it (recommended)</li>
                <li><strong>Performance monitoring</strong> — We help you keep things running smoothly</li>
                <li><strong>Updates</strong> — Security patches and improvements as needed</li>
                <li><strong>Feature roadmap</strong> — We discuss future enhancements</li>
              </ul>
              <p>Most clients continue with us for years — we're not a "build and disappear" shop.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              How do I report a bug or issue?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>You have multiple ways to reach us — pick what's easiest for you:</p>
              <ul>
                <li><strong>Email:</strong> <a href="mailto:wayronx01@gmail.com">wayronx01@gmail.com</a></li>
                <li><strong>Phone:</strong> <a href="tel:+256795885548">+256 795885548</a></li>
                <li><strong>In-app ticket system</strong> — Available in all our products</li>
                <li><strong>WhatsApp</strong> — For urgent issues</li>
                <li><strong>Your account manager</strong> — Direct line for Enterprise clients</li>
              </ul>
              <p>When reporting an issue, please include:</p>
              <ul>
                <li>What you were trying to do</li>
                <li>What happened instead</li>
                <li>Steps to reproduce it (if you can)</li>
                <li>Screenshots or screen recordings (if relevant)</li>
                <li>Your user account / location</li>
              </ul>
              <p>The more detail you give, the faster we can fix it.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              What are your support hours?
              <span class="faq-toggle-icon">+</span>
            </div>
            <div class="faq-answer">
              <p>Support hours depend on your plan:</p>
              <ul>
                <li><strong>Email Support:</strong> Monday–Friday, 9:00 AM – 6:00 PM EAT</li>
                <li><strong>Priority Support:</strong> Monday–Saturday, 8:00 AM – 8:00 PM EAT</li>
                <li><strong>24/7 Support:</strong> Always available — nights, weekends, holidays</li>
                <li><strong>Enterprise:</strong> Your account manager is available during your business hours</li>
              </ul>
              <p>For <strong>critical issues</strong> (system down, data loss risk), even basic plans get immediate escalation. We don't let urgent problems wait for business hours.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- No Results -->
      <div class="faq-no-results" id="noResults">
        <div class="icon">🔍</div>
        <h3>No results found</h3>
        <p>Try a different search term or <a href="contact.php" style="color: var(--blue); font-weight: 600;">contact us directly</a>.</p>
      </div>
      
    </div>
  </section>

  <!-- Contact CTA -->
  <section class="faq-contact-cta">
    <div class="faq-contact-cta-container">
      <h2 class="reveal">Still have questions?</h2>
      <p class="reveal stagger-1">Our team is happy to help. Reach out and we'll get back to you within 24 hours.</p>
      <div class="faq-contact-actions reveal stagger-2">
        <a href="contact.php" class="btn-primary">Contact Us <span>→</span></a>
        <a href="pricing.php" class="btn-secondary">View Pricing</a>
      </div>
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
    // ============ FAQ ACCORDION (Self-Contained) ============
    (function() {
        'use strict';
        
        // Wait for DOM to be fully loaded
        function initFAQ() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            if (faqQuestions.length === 0) {
                console.warn('No FAQ questions found');
                return;
            }
            
            console.log('FAQ initialized with', faqQuestions.length, 'questions');
            
            faqQuestions.forEach(function(question) {
                question.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const item = this.closest('.faq-item');
                    if (!item) return;
                    
                    const isActive = item.classList.contains('active');
                    
                    // Close all other items in the same category
                    const parentList = item.parentElement;
                    if (parentList) {
                        parentList.querySelectorAll('.faq-item').forEach(function(otherItem) {
                            if (otherItem !== item) {
                                otherItem.classList.remove('active');
                            }
                        });
                    }
                    
                    // Toggle current item
                    if (isActive) {
                        item.classList.remove('active');
                    } else {
                        item.classList.add('active');
                    }
                });
            });
        }
        
        // Category Filter
        function initCategories() {
            const categoryBtns = document.querySelectorAll('.faq-category-btn');
            
            categoryBtns.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const category = this.getAttribute('data-category');
                    
                    // Update active button
                    categoryBtns.forEach(function(b) { b.classList.remove('active'); });
                    this.classList.add('active');
                    
                    // Show/hide sections
                    document.querySelectorAll('.faq-category-section').forEach(function(section) {
                        if (category === 'all' || section.getAttribute('data-category') === category) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            });
        }
        
        // Search
        function initSearch() {
            const searchInput = document.getElementById('faqSearch');
            const noResults = document.getElementById('noResults');
            
            if (!searchInput) return;
            
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                const sections = document.querySelectorAll('.faq-category-section');
                
                if (searchTerm === '') {
                    sections.forEach(function(section) {
                        section.style.display = 'block';
                        section.querySelectorAll('.faq-item').forEach(function(item) {
                            item.style.display = 'block';
                        });
                    });
                    if (noResults) noResults.classList.remove('active');
                    return;
                }
                
                let totalResults = 0;
                
                sections.forEach(function(section) {
                    let sectionHasResults = false;
                    const items = section.querySelectorAll('.faq-item');
                    
                    items.forEach(function(item) {
                        const questionEl = item.querySelector('.faq-question');
                        const answerEl = item.querySelector('.faq-answer');
                        
                        const question = questionEl ? questionEl.textContent.toLowerCase() : '';
                        const answer = answerEl ? answerEl.textContent.toLowerCase() : '';
                        
                        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                            item.style.display = 'block';
                            sectionHasResults = true;
                            totalResults++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    section.style.display = sectionHasResults ? 'block' : 'none';
                });
                
                if (noResults) {
                    if (totalResults === 0) {
                        noResults.classList.add('active');
                    } else {
                        noResults.classList.remove('active');
                    }
                }
            });
        }
        
        // Initialize everything
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initFAQ();
                initCategories();
                initSearch();
            });
        } else {
            initFAQ();
            initCategories();
            initSearch();
        }
    })();
</script>
</body>
</html>