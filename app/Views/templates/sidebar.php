<style>
/* ===== Sidebar - Ibrahim Aqiqah Color Palette - Flat ===== */
:root {
    --sidebar-width: 250px;
    --sidebar-bg: #1a2529;
    --sidebar-bg2: #25424C;
    --sidebar-hover: rgba(251, 119, 13, 0.1);
    --sidebar-active: rgba(251, 119, 13, 0.2);
    --sidebar-text: rgba(255, 255, 255, 0.65);
    --sidebar-text-hover: #ffffff;
}

/* Sidebar Toggle Button (Mobile) */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 12px;
    left: 12px;
    z-index: 1050;
    background: #FB770D;
    border: none;
    border-radius: 10px;
    color: white;
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}
.sidebar-toggle:hover {
    background: #25424C;
}

/* Sidebar Overlay */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1029;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.sidebar-overlay.show {
    opacity: 1;
}

/* Main Sidebar - Flat */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    color: var(--sidebar-text);
    z-index: 1030;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    scrollbar-width: thin;
    scrollbar-color: rgba(251, 119, 13, 0.2) transparent;
}

.sidebar::-webkit-scrollbar {
    width: 4px;
}
.sidebar::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(251, 119, 13, 0.2);
    border-radius: 2px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(251, 119, 13, 0.4);
}

/* Logo Area - Flat */
.sidebar-brand {
    padding: 18px 20px;
    background: var(--sidebar-bg2);
    border-bottom: 1px solid rgba(251, 119, 13, 0.1);
    flex-shrink: 0;
    text-decoration: none;
    display: block;
    transition: all 0.3s ease;
}
.sidebar-brand:hover {
    background: #152023;
}

.sidebar-brand .brand-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sidebar-brand .brand-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: #FB770D;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}
.sidebar-brand .brand-text {
    flex: 1;
    min-width: 0;
}
.sidebar-brand .brand-text h6 {
    color: white;
    font-weight: 700;
    font-size: 1rem;
    margin: 0;
    letter-spacing: -0.3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sidebar-brand .brand-text small {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.7rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* User Info - Flat */
.sidebar-user {
    padding: 14px 20px;
    border-bottom: 1px solid rgba(251, 119, 13, 0.08);
    flex-shrink: 0;
}
.sidebar-user .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #FB770D;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.sidebar-user .user-name {
    color: white;
    font-weight: 600;
    font-size: 0.88rem;
}
.sidebar-user .user-role {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.sidebar-user .user-notif {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #FB770D;
    display: inline-block;
}

/* Navigation Header - Flat */
.sidebar-nav-header {
    padding: 16px 20px 6px;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: rgba(251, 119, 13, 0.3);
    font-weight: 700;
    flex-shrink: 0;
}

/* Navigation Items - Flat */
.sidebar-nav {
    flex: 1;
    padding: 4px 10px;
    overflow-y: auto;
}

.sidebar-item {
    margin-bottom: 2px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border-radius: 10px;
    color: var(--sidebar-text);
    text-decoration: none;
    transition: all 0.25s ease;
    gap: 12px;
    position: relative;
    font-size: 0.9rem;
    font-weight: 500;
}
.sidebar-link i {
    width: 20px;
    text-align: center;
    font-size: 1.05rem;
    transition: all 0.25s ease;
    flex-shrink: 0;
}
.sidebar-link .link-text {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sidebar-link .link-badge {
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 700;
    background: #FB770D;
    color: white;
    flex-shrink: 0;
}

.sidebar-link:hover {
    background: var(--sidebar-hover);
    color: var(--sidebar-text-hover);
}
.sidebar-link:hover i {
    color: #FB770D;
}

.sidebar-link.active {
    background: var(--sidebar-active);
    color: var(--sidebar-text-hover);
    font-weight: 600;
}
.sidebar-link.active i {
    color: #FB770D;
}
.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: #FB770D;
    border-radius: 0 3px 3px 0;
}

/* Footer in Sidebar - Flat */
.sidebar-footer {
    padding: 14px 18px;
    border-top: 1px solid rgba(251, 119, 13, 0.08);
    flex-shrink: 0;
}
.sidebar-footer .logout-link {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.25s ease;
    font-size: 0.85rem;
}
.sidebar-footer .logout-link:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}
.sidebar-footer .logout-link i {
    width: 20px;
    text-align: center;
}

/* Main Content Adjustment */
.main-content {
    margin-left: var(--sidebar-width);
    padding: 24px 28px;
    min-height: 100vh;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar-toggle {
        display: block;
    }
    
    .sidebar {
        transform: translateX(-100%);
    }
    .sidebar.open {
        transform: translateX(0);
    }
    
    .sidebar-overlay.show {
        display: block;
    }
    
    .main-content {
        margin-left: 0;
        padding: 16px;
        padding-top: 60px;
    }
}

@media (min-width: 769px) {
    .sidebar-overlay {
        display: none !important;
    }
}
</style>

<!-- Mobile Toggle -->
<button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="/admin/dashboard" class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">
                <i class="fas fa-mosque"></i>
            </div>
            <div class="brand-text">
                <h6>Ibrahim Aqiqah</h6>
                <small>Manajemen Penjadwalan</small>
            </div>
        </div>
    </a>
    
    <!-- User Info -->
    <div class="sidebar-user">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">
                <?= strtoupper(substr(session()->get('fullname') ?? 'A', 0, 1)) ?>
            </div>
            <div class="flex-grow-1 min-width-0">
                <div class="user-name"><?= session()->get('fullname') ?? 'Administrator' ?></div>
                <div class="user-role">
                    <span class="user-notif me-1"></span>
                    <?= session()->get('role') ?? 'admin' ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <div class="sidebar-nav-header">Menu Utama</div>
    
    <nav class="sidebar-nav">
        <?php $userRole = session()->get('role'); ?>
        
        <!-- Dashboard (Admin only) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/dashboard" class="sidebar-link <?= (current_url() == base_url('/admin/dashboard')) ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Pesanan (Admin only) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/orders" class="sidebar-link <?= strpos(current_url(), '/admin/orders') !== false || strpos(current_url(), '/admin/orders/') !== false ? 'active' : '' ?>">
                <i class="fas fa-shopping-cart"></i>
                <span class="link-text">Pesanan</span>
                <span class="link-badge" id="orderBadge">0</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Pelanggan (Admin) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/customers" class="sidebar-link <?= strpos(current_url(), '/admin/customers') !== false ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span class="link-text">Pelanggan</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Paket (Admin) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/packages" class="sidebar-link <?= strpos(current_url(), '/admin/packages') !== false ? 'active' : '' ?>">
                <i class="fas fa-box"></i>
                <span class="link-text">Paket</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Jadwalkan / Scheduler (Admin & RPH) -->
        <?php if ($userRole === 'admin' || $userRole === 'rph'): ?>
        <div class="sidebar-item">
            <a href="/admin/scheduler" class="sidebar-link <?= strpos(current_url(), '/admin/scheduler') !== false ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span class="link-text">Jadwalkan (EDF)</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Dapur (Admin & Dapur) -->
        <?php if ($userRole === 'admin' || $userRole === 'dapur'): ?>
        <div class="sidebar-item">
            <a href="/admin/kitchen" class="sidebar-link <?= strpos(current_url(), '/admin/kitchen') !== false ? 'active' : '' ?>">
                <i class="fas fa-utensils"></i>
                <span class="link-text">Dapur</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Stok (Semua role) -->
        <?php if (true): ?>
        <div class="sidebar-item">
            <a href="/admin/stock" class="sidebar-link <?= strpos(current_url(), '/admin/stock') !== false ? 'active' : '' ?>">
                <i class="fas fa-warehouse"></i>
                <span class="link-text">Stok</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Notifikasi (Admin) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/notifications/manual" class="sidebar-link <?= (strpos(current_url(), '/admin/notifications') !== false && strpos(current_url(), '/admin/notifications/recipients') === false) ? 'active' : '' ?>">
                <i class="fas fa-bell"></i>
                <span class="link-text">Notifikasi</span>
            </a>
        </div>
        <div class="sidebar-item ms-3">
            <a href="/admin/notifications/recipients" class="sidebar-link small <?= strpos(current_url(), '/admin/notifications/recipients') !== false ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span class="link-text">Kelola Penerima</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Laporan (Admin only) -->
        <?php if ($userRole === 'admin'): ?>
        <div class="sidebar-item">
            <a href="/admin/reports" class="sidebar-link <?= strpos(current_url(), '/admin/reports') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-pdf"></i>
                <span class="link-text">Laporan</span>
            </a>
        </div>
        <?php endif; ?>
    </nav>
    
    <!-- Footer -->
    <div class="sidebar-footer">
        <a href="/logout" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

// Fetch pending order count for badge
fetch('/admin/orders/pending-count')
    .then(r => r.json())
    .then(d => {
        if (d.count !== undefined) {
            const badge = document.getElementById('orderBadge');
            badge.textContent = d.count;
            badge.style.display = d.count > 0 ? '' : 'none';
        }
    })
    .catch(() => {});
</script>