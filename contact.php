<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us — WayronX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    /* ============ CONTACT PAGE SPECIFIC STYLES ============ */
    
    /* Page Header */
    .contact-page-header {
        background: var(--midnight);
        padding: 140px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .contact-page-header::before {
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
    
    .contact-page-header .wrap {
        position: relative;
        z-index: 1;
    }
    
    .contact-page-title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 20px 0;
    }
    
    .contact-page-title .accent {
        background: var(--grad);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .contact-page-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        line-height: 1.65;
        max-width: 48ch;
        margin: 0 auto;
    }
    
    /* Contact Section */
    .contact-section {
        padding: 80px 0;
        background: var(--white);
    }
    
    .contact-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 50px;
        align-items: start;
    }
    
    /* Contact Info */
    .contact-info {
        padding-right: 20px;
    }
    
    .contact-info h2 {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.015em;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .contact-info > p {
        color: var(--ink-mute);
        font-size: 1.05rem;
        line-height: 1.65;
        margin-bottom: 30px;
    }
    
    .contact-details {
        margin-top: 30px;
    }
    
    .contact-item {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        padding: 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .contact-item:hover {
        background: var(--light);
        transform: translateX(5px);
    }
    
    .contact-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    
    .contact-item:hover .contact-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .contact-item-content h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--ink);
    }
    
    .contact-item-content p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        margin-bottom: 0;
        line-height: 1.5;
    }
    
    .contact-item-content a {
        color: var(--blue);
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .contact-item-content a:hover {
        color: var(--purple);
    }
    
    /* Social Links */
    .contact-social {
        margin-top: 30px;
    }
    
    .contact-social h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .social-buttons {
        display: flex;
        gap: 12px;
    }
    
    .social-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .social-btn:hover {
        background: var(--grad);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    /* Contact Form */
    .contact-form-wrapper {
        background: var(--light);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    
    .contact-form-wrapper h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
        letter-spacing: -0.01em;
    }
    
    .contact-form-wrapper > p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        margin-bottom: 30px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--ink);
    }
    
    .form-group label span {
        color: var(--purple);
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--line-light);
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--ink);
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,255,0.1);
    }
    
    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: rgba(90,97,128,0.5);
    }
    
    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235A6180' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        cursor: pointer;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .form-submit {
        width: 100%;
        padding: 16px;
        background: var(--grad);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .form-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    
    .form-submit:active {
        transform: translateY(0);
    }
    
    .form-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .form-note {
        margin-top: 15px;
        text-align: center;
        font-size: 0.85rem;
        color: var(--ink-mute);
    }
    
    /* Success Message */
    .form-success {
        display: none;
        text-align: center;
        padding: 40px 20px;
    }
    
    .form-success.active {
        display: block;
    }
    
    .success-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    
    .form-success h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 10px;
    }
    
    .form-success p {
        color: var(--ink-mute);
        margin-bottom: 20px;
    }
    
    /* Error Message */
    .form-error {
        display: none;
        padding: 12px;
        background: rgba(239,68,68,0.1);
        color: var(--danger);
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .form-error.active {
        display: block;
    }
    
    /* Map Section */
    .map-section {
        padding: 0 0 80px;
    }
    
    .map-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    .map-placeholder {
        background: var(--light);
        border-radius: 20px;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--line-light);
    }
    
    .map-placeholder::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(37,99,255,0.05), rgba(124,58,237,0.05));
    }
    
    .map-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    .map-placeholder h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
        position: relative;
        z-index: 1;
    }
    
    .map-placeholder p {
        color: var(--ink-mute);
        position: relative;
        z-index: 1;
    }
    
    /* FAQ Quick Links */
    .quick-links {
        margin-top: 40px;
        padding: 20px;
        background: var(--light);
        border-radius: 12px;
    }
    
    .quick-links h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }
    
    .quick-links ul {
        list-style: none;
        padding: 0;
    }
    
    .quick-links ul li {
        margin-bottom: 10px;
    }
    
    .quick-links ul li a {
        color: var(--blue);
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .quick-links ul li a:hover {
        color: var(--purple);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .contact-container {
            grid-template-columns: 1fr;
            padding: 0 20px;
            gap: 40px;
        }
        
        .contact-info {
            padding-right: 0;
        }
        
        .contact-form-wrapper {
            padding: 30px 20px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .map-container {
            padding: 0 20px;
        }
        
        .map-placeholder {
            min-height: 300px;
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
      <li><a href="contact.php" class="active">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">More <span class="dropdown-arrow">▼</span></a>
        <ul class="dropdown-menu">
            <li><a href="#">Coming Soon</a></li>
            <li><a href="shop/index.php">Shop</a></li>
        </ul>
      </li>
    </ul>
    
    <a href="contact.php" class="btn btn-primary nav-cta">Let's Talk</a>
  </nav>
</header>

<main>
  <!-- Page Header -->
  <section class="contact-page-header">
    <div class="wrap">
      <div class="eyebrow reveal reveal-1"><span class="mark"></span>Get In Touch</div>
      <h1 class="contact-page-title reveal reveal-2">Let's start a <span class="accent">conversation</span>.</h1>
      <p class="contact-page-subtitle reveal reveal-3">Have a challenge, an idea or a process that could work better? We'd love to hear from you.</p>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="contact-container">
      <!-- Contact Info -->
      <div class="contact-info reveal-left">
        <h2>We're here to help.</h2>
        <p>Whether you have a question about our services, need a quote, or want to discuss a project, our team is ready to assist you.</p>
        
        <div class="contact-details">
          <div class="contact-item">
            <div class="contact-icon-wrapper">📍</div>
            <div class="contact-item-content">
              <h4>Visit Us</h4>
              <p>Kampala<br>Uganda, East Africa</p>
            </div>
          </div>
          
          <div class="contact-item">
            <div class="contact-icon-wrapper">📞</div>
            <div class="contact-item-content">
              <h4>Call Us</h4>
              <p><a href="tel:+256795885548">+256 795885548</a></p>
            </div>
          </div>
          
          <div class="contact-item">
            <div class="contact-icon-wrapper">✉️</div>
            <div class="contact-item-content">
              <h4>Email Us</h4>
              <p><a href="mailto:wayronx01@gmail.com">wayronx01@gmail.com</a></p>
            </div>
          </div>
          
          <div class="contact-item">
            <div class="contact-icon-wrapper">🕐</div>
            <div class="contact-item-content">
              <h4>Business Hours</h4>
              <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday - Sunday: Closed</p>
            </div>
          </div>
        </div>
        
        <div class="contact-social">
          <h4>Follow Us</h4>
          <div class="social-buttons">
            <a href="#" class="social-btn" aria-label="LinkedIn">💼</a>
            <a href="#" class="social-btn" aria-label="Twitter">🐦</a>
            <a href="#" class="social-btn" aria-label="Instagram">📸</a>
            <a href="#" class="social-btn" aria-label="GitHub">🐙</a>
          </div>
        </div>
        
        <div class="quick-links">
          <h4>Quick Answers</h4>
          <ul>
            <li><a href="faq.php">Frequently Asked Questions →</a></li>
            <li><a href="pricing.php">View Pricing →</a></li>
            <li><a href="solutions.php">Explore Solutions →</a></li>
          </ul>
        </div>
      </div>
      
      <!-- Contact Form -->
      <div class="contact-form-wrapper reveal-right">
        <div id="formContainer">
          <h3>Send us a message</h3>
          <p>Fill out the form below and we'll get back to you within 24 hours.</p>
          
          <div class="form-error" id="formError"></div>
          
          <form id="contactForm">
            <div class="form-row">
              <div class="form-group">
                <label for="name">Full Name <span>*</span></label>
                <input type="text" id="name" name="name" placeholder="John Doe" required>
              </div>
              
              <div class="form-group">
                <label for="email">Email Address <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="+256 (000-000-000)"required>
              </div>
              
              <div class="form-group">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" placeholder="Company Name" required>
              </div>
            </div>
            
            <div class="form-group">
              <label for="service">Service Needed <span>*</span></label>
              <select id="service" name="service" required>
                <option value="">Select a service</option>
                <option value="AI & Intelligent Solutions">AI & Intelligent Solutions</option>
                <option value="Digital Solutions">Digital Solutions</option>
                <option value="Business Automation">Business Automation</option>
                <option value="Cloud Solutions">Cloud Solutions</option>
                <option value="Technology Consulting">Technology Consulting</option>
                <option value="Other">Other</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="message">Project Details <span>*</span></label>
              <textarea id="message" name="service_description" placeholder="Tell us about your project, challenges, or goals..." required></textarea>
            </div>
            
            <button type="submit" class="form-submit" id="submitBtn">
              Send Message <span>→</span>
            </button>
            
            <p class="form-note">By submitting, you agree to our privacy policy.</p>
          </form>
        </div>
        
        <!-- Success Message -->
        <div class="form-success" id="formSuccess">
          <div class="success-icon">✅</div>
          <h3>Message Sent Successfully!</h3>
          <p>Thank you for contacting us. We'll get back to you within 24 hours.</p>
          <button class="form-submit" onclick="resetForm()">Send Another Message</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <div class="map-container">
      <div class="map-placeholder reveal-zoom">
        <div class="map-icon">🗺️</div>
        <h3>Find Us Here</h3>
        <p>Kampala, Uganda</p>
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
<script>
    // Contact Form Handler with Database Integration
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: document.getElementById('name').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            company: document.getElementById('company').value.trim(),
            service: document.getElementById('service').value,
            service_description: document.getElementById('message').value.trim()
        };
        
        // Hide previous error
        hideError();
        
        // Validation
        if (!formData.name || !formData.email || !formData.service || !formData.service_description) {
            showError('Please fill in all required fields.');
            return;
        }
        
        if (!isValidEmail(formData.email)) {
            showError('Please enter a valid email address.');
            return;
        }
        
        // Update button state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
        
        try {
            // Send data to PHP backend
            const response = await fetch('api/save_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                document.getElementById('formContainer').style.display = 'none';
                document.getElementById('formSuccess').classList.add('active');
            } else {
                showError(result.message || 'Failed to send message. Please try again.');
                submitBtn.textContent = 'Send Message →';
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Network error. Please check your connection and try again.');
            submitBtn.textContent = 'Send Message →';
            submitBtn.disabled = false;
        }
    });
    
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('formError');
        errorDiv.textContent = message;
        errorDiv.classList.add('active');
        
        // Shake animation
        const form = document.getElementById('contactForm');
        form.style.animation = 'none';
        setTimeout(() => {
            form.style.animation = 'shake 0.5s ease';
        }, 10);
    }
    
    function hideError() {
        const errorDiv = document.getElementById('formError');
        errorDiv.textContent = '';
        errorDiv.classList.remove('active');
    }
    
    function resetForm() {
        document.getElementById('contactForm').reset();
        document.getElementById('formSuccess').classList.remove('active');
        document.getElementById('formContainer').style.display = 'block';
    }
    
    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>