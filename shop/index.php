<?php
session_start();
$shopCurrentPage = 'shop';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tech Shop — Laptops, Desktops & Hardware | WayronX</title>
<meta name="description" content="Buy laptops, desktops, printers, networking gear, storage, and computer accessories. Fast delivery across Uganda. Warranty included.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    /* ---------- HERO ---------- */
    .shop-hero {
        background: linear-gradient(135deg, #050816 0%, #0B1026 100%);
        color: #fff;
        padding: 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .shop-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(37,99,255,0.2), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .shop-hero-content { position: relative; z-index: 1; }
    .shop-hero h1 {
        font-size: clamp(1.7rem, 4vw, 2.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 12px;
        line-height: 1.15;
    }
    .shop-hero h1 .accent {
        background: linear-gradient(120deg, #22D3EE, #7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .shop-hero p {
        color: rgba(255,255,255,0.7);
        font-size: clamp(0.9rem, 2.2vw, 1.05rem);
        max-width: 560px;
        margin: 0 auto 24px;
        line-height: 1.6;
        padding: 0 8px;
    }
    .shop-hero .search-wrap {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
        padding: 0 8px;
    }
    .shop-hero .search-wrap input {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.06);
        color: #fff;
        font-family: inherit;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.2s;
    }
    .shop-hero .search-wrap input::placeholder { color: rgba(255,255,255,0.5); }
    .shop-hero .search-wrap input:focus {
        background: rgba(255,255,255,0.1);
        border-color: var(--blue, #2563FF);
        box-shadow: 0 0 0 4px rgba(37,99,255,0.15);
    }
    .shop-hero .search-wrap .search-icon {
        position: absolute;
        left: 26px; top: 50%;
        transform: translateY(-50%);
        opacity: 0.6;
        pointer-events: none;
        font-size: 1rem;
    }

    /* ---------- CATEGORY STRIP ---------- */
    .category-section {
        padding: 20px 0;
        border-bottom: 1px solid var(--line, #e5e7eb);
        background: rgba(255,255,255,0.92);
        position: sticky;
        top: 0;
        z-index: 50;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    .category-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .carousel-btn {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid var(--line, #e5e7eb);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--ink, #0f172a);
        flex-shrink: 0;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .carousel-btn:hover:not(:disabled) {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border-color: transparent;
        transform: scale(1.08);
    }
    .carousel-btn:disabled { opacity: 0.3; cursor: not-allowed; }

    .category-scroll-wrapper {
        flex: 1;
        overflow: hidden;
        mask-image: linear-gradient(90deg, transparent, #000 24px, #000 calc(100% - 24px), transparent);
        -webkit-mask-image: linear-gradient(90deg, transparent, #000 24px, #000 calc(100% - 24px), transparent);
    }
    .category-scroll {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 4px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        cursor: grab;
        -webkit-overflow-scrolling: touch;
    }
    .category-scroll::-webkit-scrollbar { display: none; }
    .category-scroll.dragging { cursor: grabbing; scroll-behavior: auto; }

    .category-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 24px;
        border: 1.5px solid var(--line, #e5e7eb);
        background: #fff;
        cursor: pointer;
        white-space: nowrap;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.25s;
        color: var(--ink, #0f172a);
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
    }
    .category-chip:hover:not(.active) {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
        transform: translateY(-2px);
    }
    .category-chip.active {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        border-color: transparent;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(37,99,255,0.25);
    }

    /* ---------- TOOLBAR ---------- */
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 24px 0;
        flex-wrap: wrap;
    }
    .toolbar-info { color: var(--ink-mute, #6b7280); font-size: 0.95rem; font-weight: 500; }
    .toolbar-info strong { color: var(--ink, #0f172a); font-weight: 700; }
    .sort-select {
        padding: 10px 16px;
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 8px;
        font-family: inherit;
        font-weight: 500;
        background: #fff;
        cursor: pointer;
        font-size: 0.9rem;
        transition: border-color 0.2s;
        min-width: 180px;
        color: var(--ink, #0f172a);
    }
    .sort-select:hover { border-color: var(--blue, #2563FF); }

    /* ---------- PRODUCT GRID ---------- */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        padding-bottom: 30px;
        min-height: 300px;
    }
    .product-card {
        background: #fff;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    .product-image {
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        overflow: hidden;
        position: relative;
        text-decoration: none;
    }
    .product-image img {
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 12px;
        box-sizing: border-box;
        transition: transform 0.4s;
    }
    .product-card:hover .product-image img { transform: scale(1.06); }
    .product-badge {
        position: absolute;
        top: 12px; left: 12px;
        padding: 5px 10px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 2;
        letter-spacing: 0.02em;
    }
    .product-badge.out-of-stock { background: var(--danger, #ef4444); }
    .product-badge.sale { background: var(--success, #10b981); }

    .product-info {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .product-cat {
        font-size: 0.72rem;
        color: var(--purple, #7C3AED);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }
    .product-name {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        line-height: 1.35;
        flex: 1;
        color: var(--ink, #0f172a);
        text-decoration: none;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-name:hover { color: var(--blue, #2563FF); }
    .product-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin: 10px 0 14px;
        flex-wrap: wrap;
    }
    .product-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--blue, #2563FF);
    }
    .product-old-price {
        font-size: 0.85rem;
        color: var(--ink-mute, #6b7280);
        text-decoration: line-through;
    }
    .btn-add {
        width: 100%;
        padding: 10px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-add:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(37,99,255,0.3);
    }
    .btn-add:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-add.in-cart {
        background: linear-gradient(120deg, #10b981, #059669);
        opacity: 1;
    }

    /* ---------- PAGINATION ---------- */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 20px 0 60px;
        flex-wrap: wrap;
    }
    .page-btn {
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1.5px solid var(--line, #e5e7eb);
        background: #fff;
        color: var(--ink, #0f172a);
        font-family: inherit;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .page-btn:hover:not(:disabled):not(.active) {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
    }
    .page-btn.active {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        border-color: transparent;
        color: #fff;
        box-shadow: 0 8px 16px rgba(37,99,255,0.25);
    }
    .page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .page-ellipsis {
        padding: 0 8px;
        color: var(--ink-mute, #6b7280);
        font-weight: 700;
        user-select: none;
    }

    /* ---------- STATES ---------- */
    .state-loading {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 80px 20px;
        color: var(--ink-mute, #6b7280);
    }
    .state-loading .spinner {
        width: 44px; height: 44px;
        border: 3px solid var(--line, #e5e7eb);
        border-top-color: var(--blue, #2563FF);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
        margin-bottom: 16px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .state-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        background: linear-gradient(180deg, #f8fafc, #fff);
        border-radius: 16px;
        border: 1px dashed var(--line, #e5e7eb);
    }
    .state-empty .state-icon {
        font-size: 4rem;
        margin-bottom: 16px;
        opacity: 0.6;
    }
    .state-empty h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .state-empty p {
        color: var(--ink-mute, #6b7280);
        font-size: 0.95rem;
        max-width: 40ch;
        margin: 0 auto 20px;
        line-height: 1.6;
    }
    .state-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .state-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }

    /* ---------- ACCESSIBILITY ---------- */
    .category-chip:focus-visible,
    .btn-add:focus-visible,
    .carousel-btn:focus-visible,
    .sort-select:focus-visible,
    .state-btn:focus-visible,
    .product-name:focus-visible,
    .page-btn:focus-visible {
        outline: 2px solid var(--blue, #2563FF);
        outline-offset: 2px;
    }
    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }

    /* =========================================================
       TOAST — FIXED POSITIONING (top center, full width band)
       ========================================================= */
    #toastContainer.toast-container {
        position: fixed !important;
        top: 20px !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 10px !important;
        pointer-events: none !important;
        z-index: 2147483647 !important;
        padding: 0 16px !important;
        box-sizing: border-box !important;
    }

    #toastContainer .toast {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 14px 22px !important;
        border-radius: 12px !important;
        font-family: 'Manrope', sans-serif !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        color: #ffffff !important;
        background: linear-gradient(120deg, #10b981, #059669) !important;
        box-shadow:
            0 12px 30px rgba(0,0,0,0.25),
            0 2px 6px rgba(0,0,0,0.15),
            0 0 0 1px rgba(255,255,255,0.1) inset !important;
        pointer-events: auto !important;
        min-width: 260px !important;
        max-width: 480px !important;
        width: auto !important;
        line-height: 1.4 !important;
        animation: toastSlideDown 0.35s cubic-bezier(0.22, 0.61, 0.36, 1) !important;
        margin: 0 !important;
    }

    #toastContainer .toast.error {
        background: linear-gradient(120deg, #ef4444, #dc2626) !important;
    }
    #toastContainer .toast.info {
        background: linear-gradient(120deg, #2563FF, #7C3AED) !important;
    }

    #toastContainer .toast .toast-icon {
        font-size: 1.15rem !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 26px !important;
        height: 26px !important;
        border-radius: 50% !important;
        background: rgba(255,255,255,0.25) !important;
        color: #ffffff !important;
        font-weight: 800 !important;
    }

    #toastContainer .toast .toast-message {
        flex: 1 !important;
        min-width: 0 !important;
        color: #ffffff !important;
    }

    #toastContainer .toast.removing {
        animation: toastSlideUp 0.3s ease forwards !important;
    }

    @keyframes toastSlideDown {
        from {
            opacity: 0;
            transform: translateY(-24px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    @keyframes toastSlideUp {
        to {
            opacity: 0;
            transform: translateY(-24px) scale(0.95);
        }
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 768px) {
        .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
        .product-info { padding: 12px; }
        .product-name { font-size: 0.88rem; }
        .product-price { font-size: 1rem; }
        .product-old-price { font-size: 0.75rem; }
        .btn-add { font-size: 0.82rem; padding: 9px; }
        .category-section { padding: 14px 0; }
        .toolbar { padding: 18px 0; }
        .page-btn { min-width: 38px; height: 38px; font-size: 0.85rem; padding: 0 10px; }
    }
    @media (max-width: 640px) {
        .carousel-btn { display: none; }
        .category-scroll-wrapper {
            mask-image: none;
            -webkit-mask-image: none;
        }
    }
    @media (max-width: 480px) {
        .shop-hero { padding: 44px 0; }
        .shop-hero .search-wrap .search-icon { left: 22px; }
        .toolbar { flex-direction: column; align-items: stretch; gap: 10px; }
        .sort-select { width: 100%; min-width: 0; }
        .product-grid { grid-template-columns: repeat(2, 1fr); }
        .state-empty { padding: 60px 16px; }
        .state-empty .state-icon { font-size: 3rem; }
        #toastContainer.toast-container {
            top: 12px !important;
            padding: 0 12px !important;
        }
        #toastContainer .toast {
            font-size: 0.88rem !important;
            padding: 12px 18px !important;
            min-width: 200px !important;
            max-width: 100% !important;
        }
    }
    @media (max-width: 380px) {
        .product-grid { grid-template-columns: 1fr; }
        .shop-hero .search-wrap input {
            font-size: 0.85rem;
            padding: 12px 16px 12px 42px;
        }
        .shop-hero .search-wrap .search-icon { left: 18px; font-size: 0.9rem; }
    }
</style>
</head>

<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<!-- ============================================================
     TOAST CONTAINER — must exist in DOM before JS runs
     ============================================================ -->
<div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

<section class="shop-hero">
    <div class="container shop-hero-content">
        <h1>Shop <span class="accent">Tech Hardware</span></h1>
        <p>Laptops, desktops, printers, networking gear and accessories — delivered to your door.</p>
        <div class="search-wrap">
            <span class="search-icon" aria-hidden="true">🔍</span>
            <input
                type="text"
                id="searchInput"
                placeholder="Search for laptops, monitors, printers..."
                aria-label="Search products"
                autocomplete="off"
            >
        </div>
    </div>
</section>

<section class="category-section">
    <div class="container">
        <div class="category-wrapper">
            <button class="carousel-btn" id="scrollLeft" aria-label="Scroll categories left" disabled>‹</button>
            <div class="category-scroll-wrapper">
                <div class="category-scroll" id="categoryScroll" role="tablist" aria-label="Product categories">
                    <button class="category-chip active" data-category="" role="tab" aria-selected="true">
                        <span aria-hidden="true">🏠</span> All Products
                    </button>
                </div>
            </div>
            <button class="carousel-btn" id="scrollRight" aria-label="Scroll categories right">›</button>
        </div>
    </div>
</section>

<div class="container" id="productsTop">
    <div class="toolbar">
        <div class="toolbar-info" id="toolbarInfo" aria-live="polite">Loading products...</div>
        <label class="sr-only" for="sortSelect">Sort products</label>
        <select class="sort-select" id="sortSelect">
            <option value="newest">Newest First</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
            <option value="name">Name: A to Z</option>
        </select>
    </div>

    <div class="product-grid" id="productGrid"></div>

    <nav class="pagination" id="pagination" aria-label="Product pages"></nav>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
// ============================================================
//                     CONFIG
// ============================================================
const API = '../api/shop-products.php';
const PER_PAGE = 12;

// ============================================================
//                     STATE
// ============================================================
let currentCategory = '';
let currentSort     = 'newest';
let currentSearch   = '';
let currentPage     = 1;
let totalProducts   = 0;
let totalPages      = 1;
let searchTimer;
let cartProductIds  = new Set();
let allProducts     = []; // cache for pagination (if API returns all)

// ============================================================
//                     DOM REFS
// ============================================================
const scrollEl    = document.getElementById('categoryScroll');
const leftBtn     = document.getElementById('scrollLeft');
const rightBtn    = document.getElementById('scrollRight');
const gridEl      = document.getElementById('productGrid');
const toolbarEl   = document.getElementById('toolbarInfo');
const searchInput = document.getElementById('searchInput');
const sortSelect  = document.getElementById('sortSelect');
const toastCtn    = document.getElementById('toastContainer');
const paginationEl = document.getElementById('pagination');
const productsTop = document.getElementById('productsTop');

// ============================================================
//                     TOAST SYSTEM
// ============================================================
function showToast(message, type) {
    type = type || 'success';

    // Safety: ensure container exists
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = {
        success: '✓',
        error: '⚠',
        info: 'ℹ'
    };

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
    toast.innerHTML =
        '<span class="toast-icon">' + (icons[type] || '✓') + '</span>' +
        '<span class="toast-message">' + escapeHtml(message) + '</span>';

    container.appendChild(toast);

    setTimeout(function () {
        toast.classList.add('removing');
        setTimeout(function () { toast.remove(); }, 300);
    }, 2400);
}

// ============================================================
//                     HELPERS
// ============================================================
async function fetchJSON(url, options) {
    options = options || {};
    const res = await fetch(url, options);

    if (!res.ok) {
        const t = await res.text().catch(function () { return ''; });
        console.error('HTTP', res.status, t.substring(0, 300));
        throw new Error('Request failed (' + res.status + ')');
    }

    const text = await res.text();
    if (text.trim().startsWith('<')) {
        console.error('HTML response:', text.substring(0, 500));
        throw new Error('Server returned unexpected response');
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        throw new Error('Invalid JSON response');
    }
}

function formatPrice(n) {
    return 'UGX ' + Number(n).toLocaleString('en-US');
}

function escapeHtml(str) {
    return String(str == null ? '' : str).replace(/[&<>"']/g, function (c) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[c];
    });
}

// ============================================================
//                     CATEGORIES
// ============================================================
async function loadCategories() {
    try {
        const data = await fetchJSON(API + '?action=categories');
        if (data.success && Array.isArray(data.data)) {
            data.data.forEach(function (cat) {
                const btn = document.createElement('button');
                btn.className = 'category-chip';
                btn.dataset.category = cat.slug;
                btn.setAttribute('role', 'tab');
                btn.setAttribute('aria-selected', 'false');
                btn.innerHTML = '<span aria-hidden="true">' + escapeHtml(cat.icon || '📦') + '</span> ' + escapeHtml(cat.name);
                btn.addEventListener('click', function () { selectCategory(cat.slug); });
                scrollEl.appendChild(btn);
            });
            requestAnimationFrame(updateCarouselButtons);
        }
    } catch (e) {
        console.error('Categories:', e);
    }
}

function selectCategory(slug) {
    currentCategory = slug;
    currentPage = 1;
    document.querySelectorAll('.category-chip').forEach(function (c) {
        c.classList.remove('active');
        c.setAttribute('aria-selected', 'false');
    });
    const active = document.querySelector('.category-chip[data-category="' + CSS.escape(slug) + '"]');
    if (active) {
        active.classList.add('active');
        active.setAttribute('aria-selected', 'true');
        active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }
    loadProducts();
}

// ============================================================
//                     PRODUCTS
// ============================================================
async function loadProducts() {
    gridEl.innerHTML =
        '<div class="state-loading"><div class="spinner" role="status" aria-label="Loading"></div><div>Loading...</div></div>';
    paginationEl.innerHTML = '';

    try {
        // Ask the API for the current page
        const params = new URLSearchParams({
            action: 'list',
            category: currentCategory,
            search: currentSearch,
            sort: currentSort,
            page: currentPage,
            per_page: PER_PAGE,
            _t: Date.now()
        });

        const data = await fetchJSON(API + '?' + params);

        if (!data.success) {
            renderError(data.message || 'Server error');
            return;
        }

        const products = Array.isArray(data.data) ? data.data : [];
        totalProducts = Number(data.total || products.length);
        totalPages = Math.max(1, Math.ceil(totalProducts / PER_PAGE));

        if (products.length === 0) {
            renderEmpty();
            return;
        }

        // Compute "Showing X–Y of Z"
        const startIdx = (currentPage - 1) * PER_PAGE + 1;
        const endIdx = Math.min(startIdx + products.length - 1, totalProducts);

        if (totalProducts > PER_PAGE) {
            toolbarEl.innerHTML =
                'Showing <strong>' + startIdx + '–' + endIdx + '</strong> of <strong>' + totalProducts + '</strong> product' +
                (totalProducts !== 1 ? 's' : '');
        } else {
            toolbarEl.innerHTML =
                'Showing <strong>' + products.length + '</strong> product' +
                (products.length !== 1 ? 's' : '');
        }

        gridEl.innerHTML = products.map(renderProduct).join('');

        // After render, re-apply "in cart" state
        markProductsInCart();

        // Render pagination
        renderPagination();

    } catch (e) {
        renderError(e.message);
    }
}

function renderProduct(p) {
    const imgSrc = p.image ? '../' + p.image : '';
    const img = imgSrc
        ? '<img src="' + escapeHtml(imgSrc) + '" alt="' + escapeHtml(p.name) +
          '" loading="lazy" onerror="this.style.display=\'none\';this.parentElement.classList.add(\'img-fallback\')">'
        : '<span aria-hidden="true">💻</span>';

    const finalPrice = formatPrice(p.sale_price || p.price);
    const oldPrice   = p.sale_price
        ? '<span class="product-old-price">' + formatPrice(p.price) + '</span>'
        : '';
    const isOut      = Number(p.stock_quantity) <= 0;

    let badge = '';
    if (isOut) badge = '<span class="product-badge out-of-stock">Out of Stock</span>';
    else if (p.sale_price) badge = '<span class="product-badge sale">Sale</span>';

    const url = 'product.php?slug=' + encodeURIComponent(p.slug);
    const inCart = cartProductIds.has(Number(p.id));

    return (
        '<div class="product-card">' +
            '<a href="' + url + '" class="product-image" aria-label="' + escapeHtml(p.name) + '">' +
                badge + img +
            '</a>' +
            '<div class="product-info">' +
                '<div class="product-cat">' + escapeHtml(p.category_name || '') + '</div>' +
                '<a href="' + url + '" class="product-name">' + escapeHtml(p.name) + '</a>' +
                '<div class="product-price-row">' +
                    '<span class="product-price">' + finalPrice + '</span>' +
                    oldPrice +
                '</div>' +
                '<button class="btn-add' + (inCart ? ' in-cart' : '') +
                    '" data-id="' + p.id + '" data-out="' + (isOut ? '1' : '0') + '"' +
                    (isOut || inCart ? ' disabled' : '') + '>' +
                    (isOut ? 'Out of Stock' : (inCart ? '✓ In Cart' : '🛒 Add to Cart')) +
                '</button>' +
            '</div>' +
        '</div>'
    );
}

// ============================================================
//                     PAGINATION
// ============================================================
function renderPagination() {
    paginationEl.innerHTML = '';

    if (totalPages <= 1) return;

    const createBtn = function (label, page, opts) {
        opts = opts || {};
        const btn = document.createElement('button');
        btn.className = 'page-btn' + (opts.active ? ' active' : '');
        btn.innerHTML = label;
        btn.disabled = !!opts.disabled;
        btn.setAttribute('aria-label', opts.ariaLabel || ('Page ' + page));
        if (opts.active) btn.setAttribute('aria-current', 'page');
        btn.addEventListener('click', function () {
            if (opts.disabled) return;
            goToPage(page);
        });
        return btn;
    };

    const createEllipsis = function () {
        const span = document.createElement('span');
        span.className = 'page-ellipsis';
        span.textContent = '…';
        return span;
    };

    // Prev button
    paginationEl.appendChild(
        createBtn('‹ Prev', currentPage - 1, {
            disabled: currentPage <= 1,
            ariaLabel: 'Previous page'
        })
    );

    // Page numbers with ellipsis
    const pages = getPageList(currentPage, totalPages);

    pages.forEach(function (p) {
        if (p === '...') {
            paginationEl.appendChild(createEllipsis());
        } else {
            paginationEl.appendChild(
                createBtn(String(p), p, { active: p === currentPage })
            );
        }
    });

    // Next button
    paginationEl.appendChild(
        createBtn('Next ›', currentPage + 1, {
            disabled: currentPage >= totalPages,
            ariaLabel: 'Next page'
        })
    );
}

function getPageList(current, total) {
    // Always show first and last, ellipsis in between
    const delta = 1;
    const range = [];
    const rangeWithDots = [];
    let last;

    for (let i = 1; i <= total; i++) {
        if (
            i === 1 ||
            i === total ||
            (i >= current - delta && i <= current + delta)
        ) {
            range.push(i);
        }
    }

    for (let i = 0; i < range.length; i++) {
        const p = range[i];
        if (last) {
            if (p - last === 2) {
                rangeWithDots.push(last + 1);
            } else if (p - last > 2) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(p);
        last = p;
    }

    return rangeWithDots;
}

function goToPage(page) {
    if (page < 1 || page > totalPages || page === currentPage) return;
    currentPage = page;

    // Scroll to top of products
    const y = productsTop.getBoundingClientRect().top + window.pageYOffset - 100;
    window.scrollTo({ top: y, behavior: 'smooth' });

    loadProducts();
}

// ============================================================
//                     STATES
// ============================================================
function renderError(msg) {
    gridEl.innerHTML =
        '<div class="state-empty">' +
            '<div class="state-icon" aria-hidden="true">⚠️</div>' +
            '<h3>Couldn\'t load products</h3>' +
            '<p>' + escapeHtml(msg) + '</p>' +
            '<button class="state-btn" id="retryBtn">🔄 Try Again</button>' +
        '</div>';
    toolbarEl.textContent = 'Error loading';
    paginationEl.innerHTML = '';
    const retry = document.getElementById('retryBtn');
    if (retry) retry.addEventListener('click', loadProducts);
}

function renderEmpty() {
    const isFiltered = currentSearch || currentCategory;
    gridEl.innerHTML =
        '<div class="state-empty">' +
            '<div class="state-icon" aria-hidden="true">' + (isFiltered ? '🔍' : '📦') + '</div>' +
            '<h3>' + (isFiltered ? 'No products match your search' : 'Shop coming soon') + '</h3>' +
            '<p>' + (isFiltered
                ? 'Try a different keyword or browse another category.'
                : 'We\'re stocking up. Check back soon!') + '</p>' +
            (isFiltered ? '<button class="state-btn" id="clearBtn">Clear Filters</button>' : '') +
        '</div>';
    toolbarEl.textContent = isFiltered ? 'No products found' : 'Coming soon';
    paginationEl.innerHTML = '';
    const clearBtn = document.getElementById('clearBtn');
    if (clearBtn) clearBtn.addEventListener('click', clearFilters);
}

// ============================================================
//                     CART
// ============================================================
async function loadCartCount() {
    try {
        const data = await fetchJSON('../api/shop-cart.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get' })
        });
        if (data.success) {
            const count = (data.cart && data.cart.count) || 0;
            updateCartCount(count);
            cartProductIds = new Set((data.cart && data.cart.items || []).map(function (i) {
                return Number(i.id);
            }));
        }
    } catch (e) { /* silent */ }
}

function markProductsInCart() {
    document.querySelectorAll('.btn-add').forEach(function (btn) {
        const id = Number(btn.dataset.id);
        if (!id) return;

        if (cartProductIds.has(id)) {
            btn.classList.add('in-cart');
            btn.innerHTML = '✓ In Cart';
            btn.disabled = true;
        } else {
            btn.classList.remove('in-cart');
            const isOut = btn.dataset.out === '1';
            btn.innerHTML = isOut ? 'Out of Stock' : '🛒 Add to Cart';
            btn.disabled = isOut;
        }
    });
}

async function addToCart(productId, btn) {
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Adding...';
    }

    try {
        const data = await fetchJSON('../api/shop-cart.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', product_id: productId, quantity: 1 })
        });

        if (data.success) {
            const count = (data.cart && data.cart.count) || 0;
            updateCartCount(count);
            cartProductIds = new Set((data.cart && data.cart.items || []).map(function (i) {
                return Number(i.id);
            }));

            if (btn) {
                btn.classList.add('in-cart');
                btn.innerHTML = '✓ In Cart';
                btn.disabled = true;
            }

            // ✅ GREEN TOAST AT TOP
            showToast('Added to cart', 'success');
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '🛒 Add to Cart';
            }
            showToast(data.message || 'Failed to add to cart', 'error');
        }
    } catch (e) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '🛒 Add to Cart';
        }
        showToast(e.message || 'Network error', 'error');
    }
}

function updateCartCount(n) {
    document.querySelectorAll('#cartCount, #headerCartCount, [data-cart-count]').forEach(function (el) {
        if (el) el.textContent = n;
    });
}

// ============================================================
//                     FILTERS
// ============================================================
function clearFilters() {
    currentSearch = '';
    currentCategory = '';
    currentPage = 1;
    searchInput.value = '';
    document.querySelectorAll('.category-chip').forEach(function (c) {
        c.classList.remove('active');
        c.setAttribute('aria-selected', 'false');
    });
    const allChip = document.querySelector('.category-chip[data-category=""]');
    if (allChip) {
        allChip.classList.add('active');
        allChip.setAttribute('aria-selected', 'true');
    }
    loadProducts();
}

// ============================================================
//                     CAROUSEL
// ============================================================
function updateCarouselButtons() {
    const max = scrollEl.scrollWidth - scrollEl.clientWidth;
    leftBtn.disabled  = scrollEl.scrollLeft <= 4;
    rightBtn.disabled = scrollEl.scrollLeft >= max - 4;
}

function scrollCarousel(dir) {
    scrollEl.scrollBy({ left: scrollEl.clientWidth * 0.6 * dir, behavior: 'smooth' });
}

leftBtn.addEventListener('click', function () { scrollCarousel(-1); });
rightBtn.addEventListener('click', function () { scrollCarousel(1); });
scrollEl.addEventListener('scroll', updateCarouselButtons, { passive: true });
window.addEventListener('resize', updateCarouselButtons);

let isDragging = false;
let startX = 0;
let scrollStartX = 0;
let hasDragged = false;

scrollEl.addEventListener('pointerdown', function (e) {
    if (e.pointerType === 'touch') return;
    isDragging = true;
    hasDragged = false;
    startX = e.clientX;
    scrollStartX = scrollEl.scrollLeft;
    scrollEl.classList.add('dragging');
});

document.addEventListener('pointermove', function (e) {
    if (!isDragging) return;
    const dx = e.clientX - startX;
    if (Math.abs(dx) > 5) hasDragged = true;
    scrollEl.scrollLeft = scrollStartX - dx;
});

document.addEventListener('pointerup', function () {
    if (!isDragging) return;
    isDragging = false;
    scrollEl.classList.remove('dragging');
});

scrollEl.addEventListener('click', function (e) {
    if (hasDragged) {
        e.preventDefault();
        e.stopPropagation();
        hasDragged = false;
    }
}, true);

// ============================================================
//                     ADD TO CART — EVENT DELEGATION
// ============================================================
gridEl.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-add');
    if (!btn || btn.disabled) return;

    const id = Number(btn.dataset.id);
    if (!id) return;

    if (cartProductIds.has(id)) {
        showToast('Already in cart', 'info');
        return;
    }

    addToCart(id, btn);
});

// ============================================================
//                     SEARCH & SORT
// ============================================================
searchInput.addEventListener('input', function (e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function () {
        currentSearch = e.target.value.trim();
        currentPage = 1;
        loadProducts();
    }, 400);
});

sortSelect.addEventListener('change', function (e) {
    currentSort = e.target.value;
    currentPage = 1;
    loadProducts();
});

// ============================================================
//                     INIT
// ============================================================
loadCategories();
loadCartCount().then(loadProducts);
requestAnimationFrame(updateCarouselButtons);

// ============================================================
//                     DEBUG — manual toast test (uncomment to verify)
// ============================================================
// window.addEventListener('load', function () {
//     setTimeout(function () { showToast('Test toast — it works!', 'success'); }, 1000);
// });
</script>

</body>
</html>