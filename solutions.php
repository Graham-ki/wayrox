<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solutions — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ SOLUTIONS PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .solutions-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .solutions-page-header::before {
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
    
    .solutions-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .solutions-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .solutions-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .solutions-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Solutions Categories */
    .solutions-categories {
        padding: 80px 0;
        background: var(--white);
    }
    
    .solutions-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .category-tabs {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }
    
    .category-tab {
        padding: 12px 24px;
        border-radius: 30px;
        border: 2px solid var(--line-light);
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        font-family: 'Manrope', sans-serif;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: var(--ink);
    }
    
    .category-tab:hover,
    .category-tab.active {
        background: var(--grad);
        border-color: transparent;
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.2);
    }
    
    /* Solutions Grid */
    .solutions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 30px;
    }
    
    .solution-card {
        background: var(--white);
        border: 1px solid var(--line-light);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        position: relative;
        display: block;
    }
    
    .solution-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        border-color: transparent;
    }
    
    .solution-card-image {
        background: var(--grad);
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        position: relative;
        overflow: hidden;
    }
    
    .solution-card-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .solution-card:hover .solution-card-image::before {
        left: 100%;
    }
    
    .solution-card-content {
        padding: 30px;
    }
    
    .solution-card-tag {
        display: inline-block;
        padding: 5px 12px;
        background: var(--light);
        color: var(--purple);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .solution-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.01em;
        color: var(--ink);
    }
    
    .solution-card p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .solution-card-features {
        list-style: none;
        margin-bottom: 20px;
    }
    
    .solution-card-features li {
        padding: 5px 0;
        color: var(--ink-mute);
        font-size: 0.9rem;
        position: relative;
        padding-left: 25px;
    }
    
    .solution-card-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .solution-card-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--blue);
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .solution-card-link .arrow {
        transition: transform 0.3s ease;
    }
    
    .solution-card-link:hover {
        color: var(--purple);
    }
    
    .solution-card-link:hover .arrow {
        transform: translateX(5px);
    }
    
    /* Industry Solutions */
    .industry-section {
        padding: 100px 0;
        background: var(--light);
    }
    
    .industry-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .industry-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .industry-header h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .industry-header p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    .industry-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    
    .industry-card {
        background: var(--white);
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .industry-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--grad);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .industry-card:hover::before {
        transform: scaleX(1);
    }
    
    .industry-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .industry-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    
    .industry-card:hover .industry-icon {
        transform: scale(1.2);
    }
    
    .industry-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    
    .industry-card p {
        color: var(--ink-mute);
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    /* Case Studies */
    .case-studies-section {
        padding: 100px 0;
        background: var(--midnight);
    }
    
    .case-studies-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .case-studies-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .case-studies-header h2 {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
    }
    
    .case-studies-header p {
        color: rgba(255,255,255,0.6);
        font-size: 1.05rem;
    }
    
    .case-studies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .case-study-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--line-dark);
        border-radius: 16px;
        padding: 30px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .case-study-card:hover {
        background: rgba(255,255,255,0.08);
        transform: translateY(-5px);
        border-color: rgba(255,255,255,0.2);
    }
    
    .case-study-metric {
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
    }
    
    .case-study-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }
    
    .case-study-card p {
        color: rgba(255,255,255,0.6);
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    /* CTA Section */
    .solutions-cta {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .solutions-cta h2 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: -0.02em;
    }
    
    .solutions-cta p {
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
        .solutions-container,
        .industry-container,
        .case-studies-container {
            padding: 0 20px;
        }
        
        .solutions-grid {
            grid-template-columns: 1fr;
        }
        
        .industry-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .case-studies-grid {
            grid-template-columns: 1fr;
        }
        
        .category-tabs {
            flex-direction: column;
            align-items: center;
        }
    }
    
    @media (max-width: 480px) {
        .industry-grid {
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
      <li><a href="solutions.php" class="active">Solutions</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
            <li><a href="#">Coming soon</a></li>
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
  <!-- Page Header -->
  <section class="solutions-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Our Solutions</div>
      <h1 class="solutions-page-title reveal reveal-2">Solutions that solve <span class="accent">real problems</span>.</h1>
      <p class="solutions-page-subtitle reveal reveal-3">Tailored technology solutions designed to address your specific business challenges.</p>
    </div>
  </section>

  <!-- Solutions Categories -->
  <section class="solutions-categories">
    <div class="solutions-container">
      <div class="category-tabs reveal">
        <button class="category-tab active" data-category="all">All Solutions</button>
        <button class="category-tab" data-category="ai">AI & Intelligence</button>
        <button class="category-tab" data-category="digital">Software & Digital Platforms</button>
        <button class="category-tab" data-category="automation">Automation</button>
        <button class="category-tab" data-category="cloud">Cloud & Infrastructure</button>
      </div>
      
      <div class="solutions-grid">
        <!-- Solution 1 -->
        <a href="solution-details.php?id=intelligent-automation" class="solution-card reveal-zoom stagger-1" data-category="ai">
          <div class="solution-card-image">🧠</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">AI & Intelligence</span>
            <h3>Intelligent Process Automation</h3>
            <p>Automate complex business processes with AI-powered solutions that learn and adapt to your workflows.</p>
            <ul class="solution-card-features">
              <li>Document processing & extraction</li>
              <li>Intelligent workflow automation</li>
              <li>Decision automation</li>
              <li>Anomaly detection</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Solution 2 -->
        <a href="solution-details.php?id=custom-platforms" class="solution-card reveal-zoom stagger-2" data-category="digital">
          <div class="solution-card-image">🌐</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">Digital Platforms</span>
            <h3>Custom Business Platforms</h3>
            <p>Build tailored digital platforms that streamline operations and enhance customer experiences.</p>
            <ul class="solution-card-features">
              <li>Customer portals & dashboards</li>
              <li>E-commerce solutions</li>
              <li>Mobile applications</li>
              <li>Integration services</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Solution 3 -->
        <a href="solution-details.php?id=process-automation" class="solution-card reveal-zoom stagger-3" data-category="automation">
          <div class="solution-card-image">⚙️</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">Automation</span>
            <h3>Business Process Automation</h3>
            <p>Transform repetitive tasks into efficient automated workflows that save time and reduce errors.</p>
            <ul class="solution-card-features">
              <li>Workflow automation</li>
              <li>Data synchronization</li>
              <li>Report generation</li>
              <li>System integration</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Solution 4 -->
        <a href="solution-details.php?id=cloud-migration" class="solution-card reveal-zoom stagger-4" data-category="cloud">
          <div class="solution-card-image">☁️</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">Cloud & Infrastructure</span>
            <h3>Cloud Migration & Management</h3>
            <p>Move to the cloud with confidence and optimize your infrastructure for performance and cost.</p>
            <ul class="solution-card-features">
              <li>Cloud migration strategy</li>
              <li>Infrastructure optimization</li>
              <li>DevOps implementation</li>
              <li>Security & compliance</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Solution 5 -->
        <a href="solution-details.php?id=predictive-analytics" class="solution-card reveal-zoom stagger-5" data-category="ai">
          <div class="solution-card-image">📊</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">AI & Intelligence</span>
            <h3>Predictive Analytics Platform</h3>
            <p>Leverage machine learning to forecast trends, identify opportunities, and make data-driven decisions.</p>
            <ul class="solution-card-features">
              <li>Sales forecasting</li>
              <li>Customer behavior analysis</li>
              <li>Risk assessment</li>
              <li>Real-time insights</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
        
        <!-- Solution 6 -->
        <a href="solution-details.php?id=mobile-solutions" class="solution-card reveal-zoom stagger-6" data-category="digital">
          <div class="solution-card-image">📱</div>
          <div class="solution-card-content">
            <span class="solution-card-tag">Digital Platforms</span>
            <h3>Mobile-First Solutions</h3>
            <p>Create engaging mobile experiences that connect with your customers wherever they are.</p>
            <ul class="solution-card-features">
              <li>iOS & Android apps</li>
              <li>Progressive web apps</li>
              <li>Cross-platform development</li>
              <li>App maintenance</li>
            </ul>
            <span class="solution-card-link">View Details <span class="arrow">→</span></span>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Industry Solutions -->
  <section class="industry-section">
    <div class="industry-container">
      <div class="industry-header reveal">
        <div class="eyebrow on-light"><span class="mark"></span>Industries We Serve</div>
        <h2>Solutions for every industry.</h2>
        <p>We understand the unique challenges of different sectors and build solutions that fit perfectly.</p>
      </div>
      
      <div class="industry-grid">
        <div class="industry-card reveal-zoom stagger-1">
          <div class="industry-icon">🏥</div>
          <h3>Healthcare</h3>
          <p>Patient management, telemedicine, and healthcare automation.</p>
        </div>
        <div class="industry-card reveal-zoom stagger-2">
          <div class="industry-icon">🏦</div>
          <h3>Finance</h3>
          <p>Fintech solutions, risk analysis, and compliance automation.</p>
        </div>
        <div class="industry-card reveal-zoom stagger-3">
          <div class="industry-icon">🏭</div>
          <h3>Manufacturing</h3>
          <p>Supply chain optimization and production automation.</p>
        </div>
        <div class="industry-card reveal-zoom stagger-4">
          <div class="industry-icon">🛒</div>
          <h3>Retail</h3>
          <p>E-commerce platforms and customer experience solutions.</p>
        </div>
        <div class="industry-card reveal-zoom stagger-5">
          <div class="industry-icon">📚</div>
          <h3>Education</h3>
          <p>Learning management systems and educational technology.</p>
        </div>
        <div class="industry-card reveal-zoom stagger-6">
          <div class="industry-icon">🚚</div>
          <h3>Logistics</h3>
          <p>Route optimization and fleet management solutions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Case Studies -->
  <section class="case-studies-section">
    <div class="case-studies-container">
      <div class="case-studies-header reveal">
        <div class="eyebrow"><span class="mark"></span>Success Stories</div>
        <h2>Real results, real impact.</h2>
        <p>See how our solutions have transformed businesses.</p>
      </div>
      
      <div class="case-studies-grid">
        <div class="case-study-card reveal-left stagger-1">
          <div class="case-study-metric">40%</div>
          <h3>Cost Reduction</h3>
          <p>A logistics company reduced operational costs by 40% through process automation.</p>
        </div>
        <div class="case-study-card reveal-left stagger-2">
          <div class="case-study-metric">3x</div>
          <h3>Faster Processing</h3>
          <p>A financial services firm tripled their document processing speed with AI.</p>
        </div>
        <div class="case-study-card reveal-left stagger-3">
          <div class="case-study-metric">95%</div>
          <h3>Accuracy Rate</h3>
          <p>A healthcare provider achieved 95% accuracy in patient data management.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="solutions-cta">
    <div class="wrap">
      <h2 class="reveal">Find the right solution for your business.</h2>
      <p class="reveal stagger-1">Let's discuss your challenges and build a solution that works.</p>
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
<script>
    // Category Filter for Solutions
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const category = tab.getAttribute('data-category');
            const cards = document.querySelectorAll('.solution-card');
            
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>