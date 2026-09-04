<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solution Details — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ SOLUTION DETAILS PAGE SPECIFIC STYLES ============ */
    
    .solution-details-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        position: relative;
        overflow: hidden;
    }
    
    .solution-details-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37,99,255,0.2) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.1); opacity: 1; }
    }
    
    .solution-details-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 32px;
        position: relative;
        z-index: 1;
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
    
    .solution-details-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin-bottom: 20px;
    }
    
    .solution-details-tag {
        display: inline-block;
        padding: 8px 16px;
        background: var(--grad);
        color: #fff;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .solution-details-description {
        color: rgba(255,255,255,0.7);
        font-size: 1.1rem;
        line-height: 1.7;
        max-width: 700px;
    }
    
    /* Hero Image */
    .solution-hero-image {
        width: 100%;
        max-width: 700px;
        margin: 40px auto 0;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    
    .solution-hero-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
    }
    
    .solution-details-body {
        padding: 80px 0;
        background: var(--white);
    }
    
    .solution-details-section {
        margin-bottom: 60px;
    }
    
    .solution-details-section h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--ink);
        letter-spacing: -0.015em;
    }
    
    .solution-details-section p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    
    /* Section Image */
    .section-image {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        margin: 30px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .section-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
    }
    
    .features-list {
        list-style: none;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .feature-item {
        background: var(--light);
        padding: 25px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .feature-item h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    
    .feature-item p {
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    
    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .benefit-item {
        text-align: center;
        padding: 30px 20px;
        border: 1px solid var(--line-light);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .benefit-item:hover {
        border-color: var(--blue);
        transform: translateY(-5px);
    }
    
    .benefit-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .benefit-item h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    
    .benefit-item p {
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .use-cases-list {
        list-style: none;
        padding: 0;
    }
    
    .use-cases-list li {
        padding: 12px 0;
        color: var(--ink-mute);
        font-size: 1rem;
        position: relative;
        padding-left: 30px;
        border-bottom: 1px solid var(--line-light);
    }
    
    .use-cases-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--cyan);
        font-weight: bold;
    }
    
    .solution-cta-section {
        background: var(--grad);
        padding: 80px 0;
        text-align: center;
    }
    
    .solution-cta-section h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
    }
    
    .solution-cta-section p {
        color: rgba(255,255,255,0.9);
        margin-bottom: 30px;
    }
    
    .btn-white {
        background: #fff;
        color: var(--blue);
        padding: 14px 28px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
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
            <li><a href="#">Coming Soon</a></li>
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
  <div id="solutionContent">
    <!-- Content will be populated by JavaScript -->
  </div>
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
        <a href="privacy.html">Privacy Policy</a>
        <a href="terms.html">Terms &amp; Conditions</a>
      </div>
    </div>
  </div>
</footer>

<script src="js/main.js"></script>
<script>
// Solution Details Data with Images
const solutionsData = {
    'intelligent-automation': {
        title: 'Intelligent Process Automation',
        tag: 'AI & Intelligence',
        icon: '🧠',
        description: 'Automate complex business processes with AI-powered solutions that learn and adapt to your workflows.',
        heroImage: 'https://images.unsplash.com/photo-1555255707-c07966088b7b?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1518432031352-d6fc5c10da5a?w=800&h=300&fit=crop',
        overview: 'Our Intelligent Process Automation solution combines machine learning, natural language processing, and computer vision to automate complex business processes that traditionally require human intervention. This solution learns from your existing workflows and continuously improves over time.',
        features: [
            {
                title: 'Document Processing',
                description: 'Automatically extract, classify, and process information from various document types including invoices, contracts, and forms.'
            },
            {
                title: 'Intelligent Workflow Automation',
                description: 'Create adaptive workflows that route tasks, approvals, and notifications based on real-time conditions and business rules.'
            },
            {
                title: 'Decision Automation',
                description: 'Implement AI-driven decision engines that can make complex decisions based on historical data and business logic.'
            },
            {
                title: 'Anomaly Detection',
                description: 'Automatically identify unusual patterns and flag potential issues before they become problems.'
            }
        ],
        benefits: [
            { icon: '⚡', title: '60% Faster', description: 'Reduce processing time by up to 60%' },
            { icon: '🎯', title: '99% Accuracy', description: 'Achieve near-perfect accuracy in data extraction' },
            { icon: '💰', title: '40% Cost Savings', description: 'Significantly reduce operational costs' },
            { icon: '📈', title: 'Scalable', description: 'Easily scale to handle growing volumes' }
        ],
        useCases: [
            'Invoice and receipt processing',
            'Contract analysis and management',
            'Customer onboarding automation',
            'Claims processing',
            'Data entry and validation'
        ]
    },
    'custom-platforms': {
        title: 'Custom Business Platforms',
        tag: 'Digital Platforms',
        icon: '🌐',
        description: 'Build tailored digital platforms that streamline operations and enhance customer experiences.',
        heroImage: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=300&fit=crop',
        overview: 'Our Custom Business Platforms solution delivers bespoke digital experiences designed around your unique business processes. We build scalable, secure platforms that integrate seamlessly with your existing systems and provide a unified experience for your team and customers.',
        features: [
            {
                title: 'Customer Portals',
                description: 'Create self-service portals that give customers 24/7 access to their accounts, orders, and support.'
            },
            {
                title: 'E-commerce Solutions',
                description: 'Build powerful online stores with seamless payment processing, inventory management, and order tracking.'
            },
            {
                title: 'Mobile Applications',
                description: 'Develop native and cross-platform mobile apps that extend your platform to any device.'
            },
            {
                title: 'Integration Services',
                description: 'Connect your platform with CRM, ERP, and other business systems for seamless data flow.'
            }
        ],
        benefits: [
            { icon: '🚀', title: 'Faster Launch', description: 'Go from concept to launch in weeks' },
            { icon: '🔒', title: 'Enterprise Security', description: 'Bank-grade security and compliance' },
            { icon: '📱', title: 'Mobile Ready', description: 'Responsive design for all devices' },
            { icon: '🔄', title: 'Easy Integration', description: 'Connect with your existing tools' }
        ],
        useCases: [
            'Customer self-service portals',
            'B2B ordering platforms',
            'Employee intranets',
            'Partner management systems',
            'Booking and reservation systems'
        ]
    },
    'process-automation': {
        title: 'Business Process Automation',
        tag: 'Automation',
        icon: '⚙️',
        description: 'Transform repetitive tasks into efficient automated workflows that save time and reduce errors.',
        heroImage: 'https://images.unsplash.com/photo-1518186285589-2f7649de83e0?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&h=300&fit=crop',
        overview: 'Our Business Process Automation solution helps organizations identify, design, and implement automated workflows that eliminate manual tasks and streamline operations. We focus on creating measurable efficiency gains while maintaining complete control and visibility.',
        features: [
            {
                title: 'Workflow Automation',
                description: 'Design and implement automated workflows for approvals, notifications, and task assignments.'
            },
            {
                title: 'Data Synchronization',
                description: 'Automatically sync data across multiple systems to ensure consistency and eliminate manual entry.'
            },
            {
                title: 'Report Generation',
                description: 'Generate and distribute reports automatically on schedule or on-demand.'
            },
            {
                title: 'System Integration',
                description: 'Connect disparate systems to create seamless automated processes.'
            }
        ],
        benefits: [
            { icon: '⏱️', title: 'Time Savings', description: 'Save hundreds of hours per month' },
            { icon: '✅', title: 'Zero Errors', description: 'Eliminate human errors in processes' },
            { icon: '📊', title: 'Full Visibility', description: 'Track and monitor all processes' },
            { icon: '🔧', title: 'Easy Maintenance', description: 'Simple to update and modify workflows' }
        ],
        useCases: [
            'HR onboarding and offboarding',
            'Finance and accounting processes',
            'Sales order processing',
            'Customer support ticketing',
            'Inventory management'
        ]
    },
    'cloud-migration': {
        title: 'Cloud Migration & Management',
        tag: 'Cloud & Infrastructure',
        icon: '☁️',
        description: 'Move to the cloud with confidence and optimize your infrastructure for performance and cost.',
        heroImage: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=300&fit=crop',
        overview: 'Our Cloud Migration & Management solution helps organizations transition from on-premises infrastructure to cloud environments seamlessly. We handle everything from initial assessment and planning to execution and ongoing optimization.',
        features: [
            {
                title: 'Cloud Migration Strategy',
                description: 'Develop a comprehensive migration plan tailored to your specific needs and timeline.'
            },
            {
                title: 'Infrastructure Optimization',
                description: 'Right-size your cloud resources to maximize performance while minimizing costs.'
            },
            {
                title: 'DevOps Implementation',
                description: 'Implement CI/CD pipelines and automation for faster, more reliable deployments.'
            },
            {
                title: 'Security & Compliance',
                description: 'Ensure your cloud environment meets industry standards and regulatory requirements.'
            }
        ],
        benefits: [
            { icon: '💵', title: 'Cost Optimization', description: 'Reduce infrastructure costs by 30-50%' },
            { icon: '⚡', title: 'High Performance', description: 'Improve application performance' },
            { icon: '🛡️', title: 'Enhanced Security', description: 'Enterprise-grade security features' },
            { icon: '📈', title: 'Scalability', description: 'Scale resources on demand' }
        ],
        useCases: [
            'Data center migration',
            'Application modernization',
            'Disaster recovery setup',
            'Hybrid cloud implementation',
            'Cloud cost optimization'
        ]
    },
    'predictive-analytics': {
        title: 'Predictive Analytics Platform',
        tag: 'AI & Intelligence',
        icon: '📊',
        description: 'Leverage machine learning to forecast trends, identify opportunities, and make data-driven decisions.',
        heroImage: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=800&h=300&fit=crop',
        overview: 'Our Predictive Analytics Platform harnesses the power of machine learning to turn your historical data into actionable insights. From sales forecasting to risk assessment, this solution helps you make informed decisions with confidence.',
        features: [
            {
                title: 'Sales Forecasting',
                description: 'Predict future sales trends with high accuracy using historical data and market indicators.'
            },
            {
                title: 'Customer Behavior Analysis',
                description: 'Understand customer patterns and preferences to improve engagement and retention.'
            },
            {
                title: 'Risk Assessment',
                description: 'Identify potential risks and opportunities before they impact your business.'
            },
            {
                title: 'Real-time Insights',
                description: 'Get up-to-the-minute analytics and alerts for critical business metrics.'
            }
        ],
        benefits: [
            { icon: '🎯', title: 'Better Decisions', description: 'Make data-driven decisions confidently' },
            { icon: '📈', title: 'Increased Revenue', description: 'Identify new revenue opportunities' },
            { icon: '⚠️', title: 'Risk Reduction', description: 'Anticipate and mitigate risks' },
            { icon: '🔍', title: 'Deep Insights', description: 'Uncover hidden patterns in data' }
        ],
        useCases: [
            'Demand forecasting',
            'Customer churn prediction',
            'Fraud detection',
            'Inventory optimization',
            'Pricing optimization'
        ]
    },
    'mobile-solutions': {
        title: 'Mobile-First Solutions',
        tag: 'Digital Platforms',
        icon: '📱',
        description: 'Create engaging mobile experiences that connect with your customers wherever they are.',
        heroImage: 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=400&fit=crop',
        sectionImage: 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?w=800&h=300&fit=crop',
        overview: 'Our Mobile-First Solutions help you reach your customers on the devices they use most. We design and develop native, cross-platform, and progressive web applications that deliver exceptional user experiences.',
        features: [
            {
                title: 'iOS & Android Apps',
                description: 'Build native applications optimized for performance and user experience on each platform.'
            },
            {
                title: 'Progressive Web Apps',
                description: 'Create web applications that work like native apps with offline capabilities and push notifications.'
            },
            {
                title: 'Cross-platform Development',
                description: 'Maximize reach with apps that work seamlessly across multiple platforms.'
            },
            {
                title: 'App Maintenance',
                description: 'Keep your apps updated, secure, and performing at their best with ongoing support.'
            }
        ],
        benefits: [
            { icon: '📱', title: 'Wide Reach', description: 'Connect with users on any device' },
            { icon: '⚡', title: 'Fast Performance', description: 'Optimized for speed and responsiveness' },
            { icon: '🎨', title: 'Great UX', description: 'Intuitive and engaging user experiences' },
            { icon: '🔄', title: 'Regular Updates', description: 'Continuous improvement and updates' }
        ],
        useCases: [
            'Customer-facing mobile apps',
            'Employee productivity tools',
            'Field service applications',
            'E-commerce mobile experiences',
            'IoT device management'
        ]
    }
};

// Get solution ID from URL
function getSolutionId() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id') || 'intelligent-automation';
}

// Render solution details
function renderSolutionDetails() {
    const solutionId = getSolutionId();
    const solution = solutionsData[solutionId] || solutionsData['intelligent-automation'];
    const container = document.getElementById('solutionContent');
    
    container.innerHTML = `
        <!-- Solution Header -->
        <section class="solution-details-header">
            <div class="solution-details-container">
                <a href="solutions.php" class="back-link">← Back to Solutions</a>
                <div class="solution-details-tag">${solution.tag}</div>
                <h1 class="solution-details-title">${solution.icon} ${solution.title}</h1>
                <p class="solution-details-description">${solution.description}</p>
                
                <!-- Hero Image -->
                <div class="solution-hero-image">
                    <img src="${solution.heroImage}" alt="${solution.title}" onerror="this.style.display='none'">
                </div>
            </div>
        </section>
        
        <!-- Solution Body -->
        <section class="solution-details-body">
            <div class="solution-details-container">
                <div class="solution-details-section">
                    <h2>Overview</h2>
                    <p>${solution.overview}</p>
                    
                    <!-- Section Image -->
                    <div class="section-image">
                        <img src="${solution.sectionImage}" alt="${solution.title} Overview" onerror="this.style.display='none'">
                    </div>
                </div>
                
                <div class="solution-details-section">
                    <h2>Key Features</h2>
                    <div class="features-list">
                        ${solution.features.map(feature => `
                            <div class="feature-item">
                                <h3>${feature.title}</h3>
                                <p>${feature.description}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="solution-details-section">
                    <h2>Benefits</h2>
                    <div class="benefits-grid">
                        ${solution.benefits.map(benefit => `
                            <div class="benefit-item">
                                <div class="benefit-icon">${benefit.icon}</div>
                                <h3>${benefit.title}</h3>
                                <p>${benefit.description}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="solution-details-section">
                    <h2>Use Cases</h2>
                    <ul class="use-cases-list">
                        ${solution.useCases.map(useCase => `
                            <li>${useCase}</li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        </section>
        
        <!-- CTA -->
        <section class="solution-cta-section">
            <div class="wrap">
                <h2>Ready to get started?</h2>
                <p>Let's discuss how this solution can benefit your business.</p>
                <a href="contact.php" class="btn-white">Get Started <span>→</span></a>
            </div>
        </section>
    `;
}

// Initialize
renderSolutionDetails();
</script>
</body>
</html>