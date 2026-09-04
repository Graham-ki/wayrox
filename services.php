<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Services — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ SERVICES PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .services-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .services-page-header::before {
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
    
    .services-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .services-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .services-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .services-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Services Grid */
    .services-main-section {
        padding: 80px 0;
        background: var(--light);
    }
    
    .services-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    /* Service Card */
    .service-card {
        background: var(--white);
        border-radius: 16px;
        padding: 40px 35px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--line-light);
    }
    
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--grad);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    
    .service-card:hover::before {
        transform: scaleX(1);
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        border-color: transparent;
    }
    
    .service-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .service-card:hover .service-card-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .service-card-number {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--purple);
        margin-bottom: 15px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    
    .service-card h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        letter-spacing: -0.01em;
        color: var(--ink);
    }
    
    .service-card-description {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.65;
        margin-bottom: 25px;
    }
    
    .service-card-features {
        list-style: none;
        margin-bottom: 25px;
    }
    
    .service-card-features li {
        padding: 6px 0;
        color: var(--ink-mute);
        font-size: 0.9rem;
        position: relative;
        padding-left: 28px;
        transition: all 0.3s ease;
    }
    
    .service-card-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .service-card-features li:hover {
        color: var(--ink);
        transform: translateX(5px);
    }
    
    .service-card-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--blue);
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .service-card-link .arrow {
        transition: transform 0.3s ease;
    }
    
    .service-card-link:hover {
        color: var(--purple);
    }
    
    .service-card-link:hover .arrow {
        transform: translateX(5px);
    }
    
    /* Process Section */
    .process-section {
        background: var(--midnight);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }
    
    .process-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 1px;
        background: var(--grad);
    }
    
    .process-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .process-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .process-header h2 {
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
    }
    
    .process-header p {
        color: rgba(255,255,255,0.6);
        font-size: 1.05rem;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    .process-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        position: relative;
    }
    
    .process-step {
        text-align: center;
        position: relative;
        padding: 30px 20px;
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .process-step:hover {
        background: rgba(255,255,255,0.05);
        transform: translateY(-5px);
    }
    
    .process-step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 auto 20px;
        transition: all 0.3s ease;
    }
    
    .process-step:hover .process-step-number {
        transform: scale(1.1) rotate(360deg);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .process-step h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }
    
    .process-step p {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.55);
        line-height: 1.55;
    }
    
    /* CTA Section */
    .services-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .services-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .services-cta p {
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
    }
    
    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .services-container {
            grid-template-columns: 1fr;
            padding: 0 20px;
        }
        
        .process-container {
            padding: 0 20px;
        }
        
        .process-steps {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .service-card {
            padding: 30px 25px;
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
      <li><a href="services.php" class="active">Services</a></li>
      <li><a href="solutions.php">Solutions</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
        <li><a href="#">Coming Soon</a></li>
          <!--<li><a href="careers.php">Careers</a></li>
          <li><a href="team.php">Our Team</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="pricing.php">Pricing</a></li>
          <li><a href="contact.php">Contact</a></li>-->
        </ul>
      </li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Page Header -->
  <section class="services-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Our Services</div>
      <h1 class="services-page-title reveal reveal-2">Technology designed around <span class="accent">your business</span>.</h1>
      <p class="services-page-subtitle reveal reveal-3">Comprehensive solutions that help you work smarter, faster, and more efficiently.</p>
    </div>
  </section>

  <!-- Services Grid -->
  <section class="services-main-section">
    <div class="services-container">
      <!-- Service 1 -->
      <div class="service-card reveal-zoom stagger-1">
        <div class="service-card-icon">🤖</div>
        <div class="service-card-number">01 / AI Solutions</div>
        <h2>AI &amp; Intelligent Solutions</h2>
        <p class="service-card-description">We develop intelligent solutions that help businesses analyze information, automate decisions and work smarter.</p>
        <ul class="service-card-features">
          <li>Predictive Analytics</li>
          <li>Natural Language Processing</li>
          <li>Computer Vision</li>
          <li>Chatbots & Virtual Assistants</li>
          <li>Machine Learning Models</li>
        </ul>
        <a href="solutions.php" class="service-card-link">Learn More <span class="arrow">→</span></a>
      </div>
      
      <!-- Service 2 -->
      <div class="service-card reveal-zoom stagger-2">
        <div class="service-card-icon">💻</div>
        <div class="service-card-number">02 / Software & Digital Solutions</div>
        <h2>Software & Digital Solutions</h2>
        <p class="service-card-description">We design and build digital platforms and software tailored to the way your business actually works.</p>
        <ul class="service-card-features">
          <li>Custom Web Applications</li>
          <li>Mobile App Development</li>
          <li>E-commerce Platforms</li>
          <li>CMS Development</li>
          <li>API Integration</li>
        </ul>
        <a href="solutions.php" class="service-card-link">Learn More <span class="arrow">→</span></a>
      </div>
      
      <!-- Service 3 -->
      <div class="service-card reveal-zoom stagger-3">
        <div class="service-card-icon">⚡</div>
        <div class="service-card-number">03 / Automation</div>
        <h2>Business Automation</h2>
        <p class="service-card-description">We transform repetitive processes into smarter workflows that save time and improve efficiency.</p>
        <ul class="service-card-features">
          <li>Process Automation</li>
          <li>Workflow Optimization</li>
          <li>System Integration</li>
          <li>Data Pipeline Development</li>
          <li>Reporting Automation</li>
        </ul>
        <a href="solutions.php" class="service-card-link">Learn More <span class="arrow">→</span></a>
      </div>
      
      <!-- Service 4 -->
      <div class="service-card reveal-zoom stagger-4">
        <div class="service-card-icon">☁️</div>
        <div class="service-card-number">04 / Cloud Solutions</div>
        <h2>Cloud Solutions</h2>
        <p class="service-card-description">Scalable, secure, and cost-effective cloud infrastructure and services for your business.</p>
        <ul class="service-card-features">
          <li>Cloud Migration</li>
          <li>AWS & Azure Services</li>
          <li>DevOps Implementation</li>
          <li>Container Orchestration</li>
          <li>Cloud Security</li>
        </ul>
        <a href="solutions.php" class="service-card-link">Learn More <span class="arrow">→</span></a>
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section class="process-section">
    <div class="process-container">
      <div class="process-header reveal">
        <div class="eyebrow"><span class="mark"></span>How We Work</div>
        <h2>From idea to impact.</h2>
        <p>Our proven process ensures we deliver solutions that create real business value.</p>
      </div>
      
      <div class="process-steps">
        <div class="process-step reveal-left stagger-1">
          <div class="process-step-number">01</div>
          <h3>Discover</h3>
          <p>Understand the challenge, the goals and the people involved.</p>
        </div>
        <div class="process-step reveal-left stagger-2">
          <div class="process-step-number">02</div>
          <h3>Design</h3>
          <p>Shape the right solution around the problem, not the other way around.</p>
        </div>
        <div class="process-step reveal-left stagger-3">
          <div class="process-step-number">03</div>
          <h3>Build</h3>
          <p>Develop, test and refine the technology until it's ready.</p>
        </div>
        <div class="process-step reveal-left stagger-4">
          <div class="process-step-number">04</div>
          <h3>Transform</h3>
          <p>Launch, improve and scale the solution as the business grows.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="services-cta">
    <div class="wrap">
      <h2 class="reveal">Ready to transform your business?</h2>
      <p class="reveal stagger-1">Let's discuss how we can help you achieve your technology goals.</p>
      <a href="contact.html" class="btn-white reveal stagger-2">Let's Talk <span class="arrow">→</span></a>
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
            <li><a href="about.html">About</a></li>
            <li><a href="services.html">Our Approach</a></li>
            <li><a href="careers.html">Careers</a></li>
          </ul>
        </div>
        <div class="foot-col">
          <h4>Services</h4>
          <ul>
            <li><a href="services.html">AI &amp; Intelligent Solutions</a></li>
            <li><a href="services.html">Digital Solutions</a></li>
            <li><a href="services.html">Automation</a></li>
          </ul>
        </div>
        <div class="foot-col">
          <h4>Connect</h4>
          <ul>
            <li><a href="#">LinkedIn</a></li>
            <li><a href="#">Instagram</a></li>
            <li><a href="mailto:hello@wayronx.com">Email</a></li>
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