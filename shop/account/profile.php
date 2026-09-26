<?php
$pageTitle = 'My Profile';
$currentAccountPage = 'profile';
require_once __DIR__ . '/includes/header.php';

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$accountUser['id']]);
$user = $stmt->fetch();
?>

<style>
    /* =========================================================
       PROFILE PAGE
       ========================================================= */

    .prof-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .prof-grid { grid-template-columns: 1fr; }
    }

    /* Profile hero card */
    .prof-hero {
        grid-column: 1 / -1;
        background: linear-gradient(135deg, #050816 0%, #0B1026 100%);
        color: #fff;
        border-radius: 18px;
        padding: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(5,8,22,0.15);
    }
    .prof-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(37,99,255,0.25), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .prof-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .prof-avatar-lg {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(37,99,255,0.4);
    }
    .prof-hero-info { flex: 1; min-width: 200px; }
    .prof-hero-info h1 {
        font-size: clamp(1.3rem, 3vw, 1.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
        color: #fff;
    }
    .prof-hero-info .role {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255,255,255,0.9);
        margin-bottom: 8px;
    }
    .prof-hero-info .meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
    }
    .prof-hero-info .meta span {
        display: flex;
        align-items: center;
        gap: 8px;
        word-break: break-all;
    }
    @media (max-width: 480px) {
        .prof-hero { padding: 20px 16px; }
        .prof-hero-inner { flex-direction: column; align-items: flex-start; }
    }

    /* Form card */
    .prof-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 24px;
        box-shadow: 0 4px 20px rgba(11,16,38,0.05);
        position: relative;
        overflow: hidden;
    }
    .prof-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        opacity: 0.85;
    }
    .prof-card h2 {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--ink, #0B1026);
        letter-spacing: -0.01em;
    }
    .prof-card .card-desc {
        font-size: 0.86rem;
        color: var(--ink-mute, #5A6180);
        margin-bottom: 20px;
        line-height: 1.55;
    }

    .prof-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    @media (max-width: 640px) {
        .prof-form-row { grid-template-columns: 1fr; gap: 0; }
    }

    .prof-form-group { margin-bottom: 16px; }
    .prof-form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.86rem;
        margin-bottom: 6px;
        color: var(--ink, #0B1026);
    }
    .prof-form-group label .req { color: #ef4444; }
    .prof-form-group input,
    .prof-form-group textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.94rem;
        background: #fff;
        color: var(--ink, #0B1026);
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .prof-form-group input:focus,
    .prof-form-group textarea:focus {
        outline: none;
        border-color: #2563FF;
        box-shadow: 0 0 0 3px rgba(37,99,255,0.1);
    }
    .prof-form-group textarea {
        resize: vertical;
        min-height: 80px;
    }
    .prof-form-group .hint {
        font-size: 0.78rem;
        color: var(--ink-mute, #5A6180);
        margin-top: 5px;
        line-height: 1.5;
    }

    .prof-btn {
        padding: 13px 26px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.94rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    .prof-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    .prof-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .prof-btn.secondary {
        background: var(--light, #F7F8FC);
        color: var(--ink, #0B1026);
    }
    .prof-btn.secondary:hover:not(:disabled) {
        background: rgba(11,16,38,0.05);
        box-shadow: none;
    }

    /* Small note */
    .prof-note {
        background: var(--light, #F7F8FC);
        border-radius: 10px;
        padding: 14px;
        font-size: 0.84rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.6;
        margin-top: 12px;
    }
    .prof-note strong { color: var(--ink, #0B1026); }

    /* Password strength */
    .prof-strength {
        height: 4px;
        background: var(--line, rgba(11,16,38,0.1));
        border-radius: 2px;
        margin-top: 8px;
        overflow: hidden;
    }
    .prof-strength-bar {
        height: 100%;
        width: 0;
        border-radius: 2px;
        transition: width 0.3s, background 0.3s;
    }
    .prof-strength-text {
        font-size: 0.75rem;
        margin-top: 4px;
        color: var(--ink-mute, #5A6180);
    }
</style>

<!-- HERO CARD -->
<div class="prof-hero">
    <div class="prof-hero-inner">
        <div class="prof-avatar-lg">
            <?php echo strtoupper(substr($user['full_name'] ?? 'U', 0, 1)); ?>
        </div>
        <div class="prof-hero-info">
            <h1><?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?></h1>
            <span class="role"><?php echo htmlspecialchars(ucfirst($user['role'] ?? 'customer')); ?></span>
            <div class="meta">
                <span>👤 <?php echo htmlspecialchars($user['username'] ?? ''); ?></span>
                <span>✉️ <?php echo htmlspecialchars($user['email'] ?? ''); ?></span>
                <?php if (!empty($user['phone'])): ?>
                    <span>📞 <?php echo htmlspecialchars($user['phone']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- FORMS -->
<div class="prof-grid">

    <!-- Personal info -->
    <div class="prof-card" style="grid-column: 1 / -1;">
        <h2>Personal Information</h2>
        <p class="card-desc">Keep your details up to date so orders arrive correctly.</p>

        <form id="profileForm">
            <div class="prof-form-row">
                <div class="prof-form-group">
                    <label for="fullName">Full Name <span class="req">*</span></label>
                    <input type="text" id="fullName" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                <div class="prof-form-group">
                    <label for="username">Username <span class="req">*</span></label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="prof-form-row">
                <div class="prof-form-group">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="prof-form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+256 700 000 000">
                </div>
            </div>

            <div class="prof-form-group">
                <label for="defaultAddress">Default Delivery Address</label>
                <textarea id="defaultAddress" placeholder="Where should we deliver your orders by default?"><?php echo htmlspecialchars($user['default_address'] ?? ''); ?></textarea>
                <div class="hint">We'll pre-fill this on your next order.</div>
            </div>

            <div class="prof-form-group">
                <label for="defaultCity">Default City / Town</label>
                <input type="text" id="defaultCity" value="<?php echo htmlspecialchars($user['default_city'] ?? ''); ?>" placeholder="e.g. Kampala">
            </div>

            <button type="submit" class="prof-btn" id="saveProfileBtn" style="max-width: 260px;">
                💾 Save Changes
            </button>
        </form>
    </div>

    <!-- Password -->
    <div class="prof-card">
        <h2>Change Password</h2>
        <p class="card-desc">Use a strong password with at least 6 characters.</p>

        <form id="passwordForm">
            <div class="prof-form-group">
                <label for="currentPassword">Current Password <span class="req">*</span></label>
                <input type="password" id="currentPassword" required autocomplete="current-password">
            </div>

            <div class="prof-form-group">
                <label for="newPassword">New Password <span class="req">*</span></label>
                <input type="password" id="newPassword" required minlength="6" autocomplete="new-password">
                <div class="prof-strength">
                    <div class="prof-strength-bar" id="strengthBar"></div>
                </div>
                <div class="prof-strength-text" id="strengthText"></div>
            </div>

            <div class="prof-form-group">
                <label for="confirmPassword">Confirm New Password <span class="req">*</span></label>
                <input type="password" id="confirmPassword" required minlength="6" autocomplete="new-password">
            </div>

            <button type="submit" class="prof-btn" id="savePasswordBtn" style="max-width: 260px;">
                🔒 Update Password
            </button>
        </form>
    </div>

    <!-- Account info -->
    <div class="prof-card">
        <h2>Account Information</h2>
        <p class="card-desc">Read-only details about your account.</p>

        <div class="prof-note">
            <strong>User ID:</strong> #<?php echo (int)($user['id'] ?? 0); ?><br>
            <strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'] ?? 'customer')); ?><br>
            <strong>Member since:</strong>
            <?php
                $created = $user['created_at'] ?? null;
                echo $created ? date('M j, Y', strtotime($created)) : 'N/A';
            ?><br>
            <strong>Last login:</strong>
            <?php
                $lastLogin = $user['last_login'] ?? null;
                echo $lastLogin ? date('M j, Y g:i A', strtotime($lastLogin)) : 'N/A';
            ?>
        </div>

        <div class="prof-note" style="margin-top:16px;">
            <strong>🔒 Your data is safe</strong><br>
            We never share your personal information with third parties. Your details are only used to process orders and improve your experience.
        </div>
    </div>

</div>

<script>
function profToast(msg, type = 'success') {
    if (window.showToast) {
        window.showToast(msg, type);
        return;
    }
    alert(msg);
}

// ============ PROFILE ============
document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveProfileBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const payload = {
        full_name: document.getElementById('fullName').value.trim(),
        username: document.getElementById('username').value.trim(),
        email: document.getElementById('email').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        default_address: document.getElementById('defaultAddress').value.trim(),
        default_city: document.getElementById('defaultCity').value.trim()
    };

    try {
        const res = await fetch('../../api/shop-update-profile.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            profToast('✓ Profile updated');
            // Update hero display
            document.querySelector('.prof-hero-info h1').textContent = payload.full_name;
            document.querySelector('.prof-avatar-lg').textContent = payload.full_name.charAt(0).toUpperCase();
        } else {
            profToast(data.message || 'Failed to save', 'error');
        }
    } catch (e) {
        profToast('Network error', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Save Changes';
    }
});

// ============ PASSWORD STRENGTH ============
const newPasswordInput = document.getElementById('newPassword');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');

newPasswordInput.addEventListener('input', () => {
    const val = newPasswordInput.value;
    let score = 0;

    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    let percent = (score / 5) * 100;
    let color = '#ef4444';
    let text = 'Weak';

    if (score >= 2) { color = '#f59e0b'; text = 'Fair'; }
    if (score >= 3) { color = '#10b981'; text = 'Good'; }
    if (score >= 4) { color = '#059669'; text = 'Strong'; }
    if (score >= 5) { color = '#047857'; text = 'Very Strong'; }

    strengthBar.style.width = percent + '%';
    strengthBar.style.background = color;
    strengthText.textContent = val ? text : '';
    strengthText.style.color = color;
});

// ============ PASSWORD ============
document.getElementById('passwordForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const current = document.getElementById('currentPassword').value;
    const newPwd = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;

    if (newPwd !== confirm) {
        profToast('New passwords do not match', 'error');
        return;
    }
    if (newPwd.length < 6) {
        profToast('Password must be at least 6 characters', 'error');
        return;
    }

    const btn = document.getElementById('savePasswordBtn');
    btn.disabled = true;
    btn.textContent = 'Updating...';

    try {
        const res = await fetch('../../api/shop-update-password.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                current_password: current,
                new_password: newPwd
            })
        });
        const data = await res.json();

        if (data.success) {
            profToast('✓ Password updated');
            document.getElementById('passwordForm').reset();
            strengthBar.style.width = '0';
            strengthText.textContent = '';
        } else {
            profToast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        profToast('Network error', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '🔒 Update Password';
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>