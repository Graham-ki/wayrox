<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WayronX — Technology that moves business forward</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Loading Screen -->
<div class="loading-screen">
    <div class="loader"></div>
</div>

<!-- Particles Container -->
<div class="particles-container"></div>

<!-- Header -->
<header class="navbar" id="navbar">
  <nav class="nav-container">
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
    
    <a href="index.php" class="logo">
      <svg class="mark" viewBox="0 0 24 24" fill="none"><path d="M4 4L20 20M20 4L4 20" stroke="url(#navX)" stroke-width="2.6" stroke-linecap="round"/><defs><linearGradient id="navX" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2563FF"/><stop offset="1" stop-color="#7C3AED"/></linearGradient></defs></svg>
      WayronX
    </a>
    
    <ul class="nav-menu" id="navMenu">
      <li><a href="index.php" class="active">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="portfolio.php">Solutions</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
        <li><a href="#">Coming Soon</a></li>
        <li><a href="shop/index.php">Shop</a></li>
          <!--<li><a href="careers.html">Careers</a></li>
          <li><a href="team.html">Our Team</a></li>
          <li><a href="faq.html">FAQ</a></li>
          <li><a href="pricing.html">Pricing</a></li>
          <li><a href="contact.html">Contact</a></li>-->
        </ul>
      </li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>

  <!-- HERO -->
  <section class="hero dark">
    <div class="wrap hero-inner">
      <div class="hero-copy">
        <div class="eyebrow reveal reveal-1"><span class="mark"></span>AI · Digital · Automation</div>
        <h1 class="hero-h reveal reveal-2">We build technology that moves business <span class="accent">forward</span>.</h1>
        <p class="hero-lede reveal reveal-3">WayronX creates intelligent digital solutions that help businesses simplify operations, automate processes and turn ideas into scalable technology.</p>
        <div class="hero-actions reveal reveal-4">
          <a href="contact.php" class="btn btn-primary">Let's Talk <span class="x-arrow">→</span></a>
          <a href="services.php" class="btn btn-ghost-dark">Explore Services</a>
        </div>
        <div class="hero-stats reveal reveal-4">
          <div class="stat">
            <h3 class="counter" data-counter="15">0</h3>
            <p>Projects Delivered</p>
          </div>
          <div class="stat">
            <h3 class="counter" data-counter="10">0</h3>
            <p>Happy Clients</p>
          </div>
          <div class="stat">
            <h3 class="counter" data-counter="10">0</h3>
            <p>Years Experience</p>
          </div>
        </div>
      </div>

      <div class="hero-visual reveal reveal-3">
        <svg viewBox="0 0 520 480">
          <defs>
            <radialGradient id="glow" cx="50%" cy="46%" r="52%">
              <stop offset="0%" stop-color="#7C3AED" stop-opacity="0.32"/>
              <stop offset="100%" stop-color="#7C3AED" stop-opacity="0"/>
            </radialGradient>
            <linearGradient id="xGrad" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#2563FF"/>
              <stop offset="1" stop-color="#7C3AED"/>
            </linearGradient>
            <linearGradient id="xGradSoft" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#2563FF" stop-opacity="0.9"/>
              <stop offset="1" stop-color="#22D3EE" stop-opacity="0.9"/>
            </linearGradient>
            <pattern id="grid" width="34" height="34" patternUnits="userSpaceOnUse">
              <path d="M34 0H0V34" fill="none" stroke="rgba(255,255,255,0.045)" stroke-width="1"/>
            </pattern>
          </defs>

          <circle cx="260" cy="230" r="220" fill="url(#glow)"/>
          <rect x="60" y="60" width="400" height="340" fill="url(#grid)"/>

          <g class="core-float">
            <ellipse cx="260" cy="230" rx="168" ry="168" fill="none" stroke="url(#xGradSoft)" stroke-width="0.8" opacity="0.22"/>
            <g transform="translate(260,230)">
              <path d="M-78 -78 L78 78 M78 -78 L-78 78" stroke="url(#xGrad)" stroke-width="15" stroke-linecap="round"/>
              <path d="M-78 -78 L78 78 M78 -78 L-78 78" stroke="rgba(5,8,22,0.35)" stroke-width="15" stroke-linecap="round" stroke-dasharray="1 46" opacity="0.5"/>
            </g>
            <line x1="260" y1="230" x2="132" y2="118" stroke="rgba(255,255,255,0.14)" stroke-width="1"/>
            <line x1="260" y1="230" x2="388" y2="118" stroke="rgba(255,255,255,0.14)" stroke-width="1"/>
            <line x1="260" y1="230" x2="260" y2="382" stroke="rgba(255,255,255,0.14)" stroke-width="1"/>
            <circle cx="132" cy="118" r="4" fill="#22D3EE"/>
            <circle cx="388" cy="118" r="4" fill="#2563FF"/>
            <circle cx="260" cy="382" r="4" fill="#7C3AED"/>
            <text x="132" y="98" text-anchor="middle" fill="rgba(255,255,255,0.5)" font-family="Manrope, sans-serif" font-size="12.5" font-weight="600">AI</text>
            <text x="388" y="98" text-anchor="middle" fill="rgba(255,255,255,0.5)" font-family="Manrope, sans-serif" font-size="12.5" font-weight="600">Automation</text>
            <text x="260" y="408" text-anchor="middle" fill="rgba(255,255,255,0.5)" font-family="Manrope, sans-serif" font-size="12.5" font-weight="600">Digital Solutions</text>
          </g>
        </svg>
      </div>
    </div>

    <div class="hero-foot">Technology · Intelligence · Impact</div>
  </section>

  <!-- WHAT WE DO -->
  <section id="services" class="section-pad">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow on-light"><span class="mark"></span>What We Do</div>
        <h2>Technology designed around your business.</h2>
      </div>

      <div class="services">
        <div class="service reveal-zoom stagger-1">
          <div class="num">01</div>
          <h3>AI &amp; Intelligent Solutions</h3>
          <p>We develop intelligent solutions that help businesses analyze information, automate decisions and work smarter.</p>
        </div>
        <div class="service reveal-zoom stagger-2">
          <div class="num">02</div>
          <h3>Digital Solutions</h3>
          <p>We design and build digital platforms and software tailored to the way your business actually works.</p>
        </div>
        <div class="service reveal-zoom stagger-3">
          <div class="num">03</div>
          <h3>Business Automation</h3>
          <p>We transform repetitive processes into smarter workflows that save time and improve efficiency.</p>
        </div>
      </div>

      <div class="services-more reveal">
        <h4>Need something different?</h4>
        <p>We also work with organizations to identify technology opportunities and develop solutions built around their specific needs.</p>
        <a href="contact.php">Talk to us <span class="x-arrow">→</span></a>
      </div>
    </div>
  </section>

  <!-- HOW WE WORK -->
  <section class="section-pad dark" style="padding:104px 0;">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="mark"></span>How We Work</div>
        <h2 style="color:#fff;">From idea to impact.</h2>
      </div>
      <div class="process-grid">
        <div class="process-step reveal-left stagger-1">
          <span class="n">01</span>
          <div class="t">Discover</div>
          <p class="d">Understand the challenge, the goals and the people involved.</p>
        </div>
        <div class="process-step reveal-left stagger-2">
          <span class="n">02</span>
          <div class="t">Design</div>
          <p class="d">Shape the right solution around the problem, not the other way around.</p>
        </div>
        <div class="process-step reveal-left stagger-3">
          <span class="n">03</span>
          <div class="t">Build</div>
          <p class="d">Develop, test and refine the technology until it's ready.</p>
        </div>
        <div class="process-step reveal-left stagger-4">
          <span class="n">04</span>
          <div class="t">Transform</div>
          <p class="d">Launch, improve and scale the solution as the business grows.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="section-pad">
    <div class="wrap">
      <div class="about-grid">
        <div class="reveal-left">
          <div class="eyebrow on-light"><span class="mark"></span>Who We Are</div>
          <h2>Technology should make things better.</h2>
        </div>
        <div class="about-copy reveal-right">
          <p>WayronX is a technology company focused on creating intelligent digital solutions that help businesses solve problems, simplify operations and embrace new possibilities.</p>
          <p>We work closely with the teams behind each project, so what we build fits how the business actually runs — not the other way around.</p>
          <a href="about.php">More about WayronX <span class="x-arrow">→</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY WAYRONX -->
  <section id="why" class="section-pad" style="background:var(--light);">
    <div class="wrap">
      <div class="eyebrow on-light reveal" style="display:flex;justify-content:center;"><span class="mark"></span>Why WayronX</div>
      <div class="why-statement reveal">
        <h2>We don't build technology for technology's sake.</h2>
      </div>
      <p class="why-sub reveal stagger-1">We start with the problem. We understand the people behind it. And we build solutions that create meaningful results.</p>
      <div class="why-grid">
        <div class="why-item reveal-zoom stagger-1">
          <span class="n">01</span>
          <h4>Purposeful</h4>
          <p>Built around real problems.</p>
        </div>
        <div class="why-item reveal-zoom stagger-2">
          <span class="n">02</span>
          <h4>Intelligent</h4>
          <p>Using AI where it creates value.</p>
        </div>
        <div class="why-item reveal-zoom stagger-3">
          <span class="n">03</span>
          <h4>Scalable</h4>
          <p>Designed to grow with your business.</p>
        </div>
        <div class="why-item reveal-zoom stagger-4">
          <span class="n">04</span>
          <h4>Reliable</h4>
          <p>Built with security and quality in mind.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="section-pad dark final-cta">
    <div class="wrap">
      <h2 class="reveal">What's next starts with an idea.</h2>
      <p class="reveal stagger-1">Have a challenge, an idea or a process that could work better? Let's explore what's possible.</p>
      <a href="contact.php" class="btn btn-primary reveal stagger-2">Let's Talk <span class="x-arrow">→</span></a>
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
            <li><a href="solutions.php">Solutions</a></li>
            <li><a href="careers.php">Careers</a></li>
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