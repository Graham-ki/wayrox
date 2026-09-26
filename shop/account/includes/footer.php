    </main>
</div>

<script>
    // Global toast helper for account pages
    function acctToast(msg, type = 'success') {
        const existing = document.querySelector('.acct-toast');
        if (existing) existing.remove();

        const t = document.createElement('div');
        t.className = 'acct-toast ' + type;
        t.textContent = msg;
        t.style.cssText = `
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'error' ? 'linear-gradient(120deg,#ef4444,#dc2626)' : 'linear-gradient(120deg,#10b981,#059669)'};
            color: #fff;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            z-index: 99999;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
            max-width: calc(100vw - 32px);
            text-align: center;
            animation: acctToastIn 0.3s ease;
        `;
        document.body.appendChild(t);

        const styleId = 'acct-toast-style';
        if (!document.getElementById(styleId)) {
            const s = document.createElement('style');
            s.id = styleId;
            s.textContent = `
                @keyframes acctToastIn {
                    from { opacity: 0; transform: translate(-50%, -20px); }
                    to { opacity: 1; transform: translate(-50%, 0); }
                }
            `;
            document.head.appendChild(s);
        }

        setTimeout(() => {
            t.style.transition = 'opacity 0.3s';
            t.style.opacity = '0';
            setTimeout(() => t.remove(), 300);
        }, 2400);
    }

    // Alias for pages already using showToast
    window.showToast = window.showToast || acctToast;
</script>
</body>
</html>