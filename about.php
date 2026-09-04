<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ ABOUT PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .about-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .about-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37,99,255,0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.1); opacity: 1; }
    }
    
    .about-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .about-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .about-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .about-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Story Section */
    .story-section {
        padding: 100px 0;
        background: var(--white);
    }
    
    .story-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    
    .story-content h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        line-height: 1.25;
        margin-bottom: 20px;
        color: var(--ink);
    }
    
    .story-content p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    
    .story-highlight {
        border-left: 3px solid var(--blue);
        padding-left: 20px;
        margin: 30px 0;
        font-style: italic;
        color: var(--ink);
        font-size: 1.1rem;
    }
    
    .story-visual {
        position: relative;
    }
    
    .story-visual-box {
        background: var(--grad);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .story-visual-box::before {
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
    
    .story-visual-box h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    
    .story-visual-box p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.9);
        position: relative;
        z-index: 1;
    }
    
    /* Stats Section */
    .stats-section {
        background: var(--light);
        padding: 80px 0;
    }
    
    .stats-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        text-align: center;
    }
    
    .stat-card {
        background: var(--white);
        border-radius: 16px;
        padding: 40px 20px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: var(--grad);
        transition: width 0.3s ease;
    }
    
    .stat-card:hover::before {
        width: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
    }
    
    .stat-label {
        color: var(--ink-mute);
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    /* Values Section */
    .values-section {
        padding: 100px 0;
        background: var(--white);
    }
    
    .values-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .values-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .values-header h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
    }
    
    .values-header p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    
    .value-card {
        background: var(--light);
        border-radius: 16px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .value-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--grad);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 0;
    }
    
    .value-card:hover::after {
        opacity: 0.05;
    }
    
    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        background: var(--white);
    }
    
    .value-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }
    
    .value-card:hover .value-icon {
        transform: scale(1.2) rotate(10deg);
    }
    
    .value-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
        letter-spacing: -0.01em;
    }
    
    .value-card p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }
    
    /* Team Section */
    .team-section {
        padding: 100px 0;
        background: var(--midnight);
    }
    
    .team-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .team-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .team-header h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
    }
    
    .team-header p {
        color: rgba(255,255,255,0.6);
        font-size: 1.05rem;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
    }
    
    .team-member {
        text-align: center;
        padding: 30px 20px;
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .team-member::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.03);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .team-member:hover::before {
        opacity: 1;
    }
    
    .team-member:hover {
        transform: translateY(-5px);
    }
    
    .member-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--grad);
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #fff;
        font-weight: 700;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .team-member:hover .member-avatar {
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .team-member h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        position: relative;
        z-index: 1;
    }
    
    .team-member p {
        color: rgba(255,255,255,0.5);
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
    }
    
    /* CTA Section */
    .about-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .about-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .about-cta p {
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
        .story-container {
            grid-template-columns: 1fr;
            padding: 0 20px;
        }
        
        .stats-container,
        .values-container,
        .team-container {
            padding: 0 20px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .values-grid {
            grid-template-columns: 1fr;
        }
        
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .team-grid {
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
      <li><a href="about.php" class="active">About</a></li>
      <li><a href="services.php">Services</a></li>
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
  <section class="about-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Who We Are</div>
      <h1 class="about-page-title reveal reveal-2">Technology should make things <span class="accent">better</span>.</h1>
      <p class="about-page-subtitle reveal reveal-3">We're on a mission to help businesses thrive in the digital age through intelligent, purposeful technology.</p>
    </div>
  </section>

  <!-- Story Section -->
  <section class="story-section">
    <div class="story-container">
      <div class="story-content reveal-left">
        <div class="eyebrow on-light"><span class="mark"></span>Our Story</div>
        <h2>Built on purpose, driven by impact.</h2>
        <p>WayronX was founded with a simple belief: technology should solve real problems. We started as a small team of engineers and designers who wanted to build solutions that actually make a difference for businesses.</p>
        <p>Today, we've grown into a full-service technology company, helping organizations across industries leverage AI, automation, and digital solutions to transform how they work.</p>
        <div class="story-highlight">
          "Our approach remains the same — understand the problem, listen to the people, and build technology that creates meaningful results."
        </div>
        <p>We work closely with the teams behind each project, so what we build fits how the business actually runs — not the other way around.</p>
      </div>
      
      <div class="story-visual reveal-right">
        <div class="story-visual-box">
          <h3>10+ Years</h3>
          <p>of building technology that matters</p>
          <div style="margin-top: 30px; font-size: 3rem;">🚀</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <div class="stats-container">
      <div class="stats-grid">
        <div class="stat-card reveal-zoom stagger-1">
          <div class="stat-number counter" data-counter="15">0</div>
          <div class="stat-label">Projects Delivered</div>
        </div>
        <div class="stat-card reveal-zoom stagger-2">
          <div class="stat-number counter" data-counter="10">0</div>
          <div class="stat-label">Happy Clients</div>
        </div>
        <div class="stat-card reveal-zoom stagger-3">
          <div class="stat-number counter" data-counter="10">0</div>
          <div class="stat-label">Years Experience</div>
        </div>
        <div class="stat-card reveal-zoom stagger-4">
          <div class="stat-number counter" data-counter="5">0</div>
          <div class="stat-label">Team Members</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Values Section -->
  <section class="values-section">
    <div class="values-container">
      <div class="values-header reveal">
        <div class="eyebrow on-light"><span class="mark"></span>Our Values</div>
        <h2>What guides our work.</h2>
        <p>The principles that shape every solution we build and every partnership we form.</p>
      </div>
      
      <div class="values-grid">
        <div class="value-card reveal-zoom stagger-1">
          <div class="value-icon">🎯</div>
          <h3>Purposeful</h3>
          <p>We build technology around real problems, not the other way around. Every solution has a clear purpose and measurable impact.</p>
        </div>
        <div class="value-card reveal-zoom stagger-2">
          <div class="value-icon">💡</div>
          <h3>Intelligent</h3>
          <p>We use AI and smart solutions where they create genuine value, making technology work smarter for your business.</p>
        </div>
        <div class="value-card reveal-zoom stagger-3">
          <div class="value-icon">📈</div>
          <h3>Scalable</h3>
          <p>Our solutions are designed to grow with your business, adapting to new challenges and opportunities.</p>
        </div>
        <div class="value-card reveal-zoom stagger-4">
          <div class="value-icon">🤝</div>
          <h3>Collaborative</h3>
          <p>We work closely with your team to ensure success, building partnerships that last beyond project delivery.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="team-section">
    <div class="team-container">
      <div class="team-header reveal">
        <div class="eyebrow"><span class="mark"></span>Leadership</div>
        <h2>Meet the team.</h2>
        <p>The people behind WayronX who make it all happen.</p>
      </div>
      
      <div class="team-grid">
        <div class="team-member reveal-zoom stagger-1">
          <div class="member-avatar">SW</div>
          <h3>Sharon Wayungrwot</h3>
          <p>Founder & CEO</p>
        </div>
        <!-- <div class="team-member reveal-zoom stagger-2">
          <div class="member-avatar">S</div>
          <h3>Sarah Chen</h3>
          <p>Chief Technology Officer</p>
        </div>
        <div class="team-member reveal-zoom stagger-3">
          <div class="member-avatar">M</div>
          <h3>Michael Torres</h3>
          <p>Head of Engineering</p>
        </div>
        <div class="team-member reveal-zoom stagger-4">
          <div class="member-avatar">E</div>
          <h3>Emily Watson</h3>
          <p>Creative Director</p>
        </div>-->
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="about-cta">
    <div class="wrap">
      <h2 class="reveal">Let's build something meaningful together.</h2>
      <p class="reveal stagger-1">Ready to transform your business with intelligent technology?</p>
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
            <li><a href="#">Careers</a></li>
          </ul>
        </div>
        <div class="foot-col">
          <h4>Services</h4>
          <ul>
            <li><a href="services.php">AI &amp; Intelligent Solutions</a></li>
            <li><a href="services.php">Digital Solutions</a></li>
            <li><a href="services.php">Automation</a></li>
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