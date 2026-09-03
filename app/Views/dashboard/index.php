<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-tachometer-alt"></i>Dashboard</h4>
            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('l, d F Y') ?></small>
        </div>
        <div>
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <!-- Stat Cards Modern -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        <i class="fas fa-chart-line me-1"></i>+12%
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Total Pesanan</div>
                    <h4 class="mb-0"><?= $total_orders ?></h4>
                    <div class="stat-footer">Semua pesanan terdaftar</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-2">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-clock me-1"></i>Hari Ini
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Pesanan Hari Ini</div>
                    <h4 class="mb-0"><?= $today_orders ?></h4>
                    <div class="stat-footer">Jadwal pemotongan hari ini</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-tag"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-arrow-up me-1"></i>Stok
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Stok Hewan</div>
                    <h4 class="mb-0"><?= $total_stock ?></h4>
                    <div class="stat-footer">Kambing & Domba tersedia</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar me-1"></i>Bulan Ini
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Pendapatan Bulan Ini</div>
                    <h4 class="mb-0">Rp <?= number_format($monthly_revenue, 0, ',', '.') ?></h4>
                    <div class="stat-footer">Total pendapatan kotor</div>
                </div>
            </div>
        </div>
    </div>

<!-- Recap 24 Jam Card -->
<?php if (!empty($upcoming_slaughter)): ?>
<div class="card border-warning mb-4 animate-fade-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Anak</th>
                        <th>Paket</th>
                        <th>Jam Potong</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upcoming_slaughter as $u): ?>
                    <tr>
                        <td><strong>#<?= $u['id_order'] ?></strong></td>
                        <td><?= $u['customer_name'] ?? 'N/A' ?></td>
                        <td><?= $u['child_name'] ?? '-' ?></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $u['package_name'] ?? 'N/A' ?></span></td>
                        <td><?= $u['slaughter_time'] ? date('H:i', strtotime($u['slaughter_time'])) : '<em class="text-muted">Belum diatur</em>' ?></td>
                        <td>
                            <?php if (!empty($u['customer_phone'])): ?>
                            <a href="https://wa.me/62<?= preg_replace('/^0?/', '', $u['customer_phone']) ?>" target="_blank" class="btn btn-sm btn-outline-success" style="border-color:#25D366;color:#25D366;">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/orders/edit/<?= $u['id_order'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Calendar Heatmap + Monthly Chart Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-9">
        <div class="card animate-fade-up stagger-1 h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Peta Pesanan - <?= date('F Y') ?></h5>
                <small class="text-muted">🖱️ Klik tanggal untuk lihat detail pesanan</small>
            </div>
            <div class="card-body p-3">
                <!-- Month Navigation -->
                <div class="d-flex justify-content-between mb-2">
                    <button class="btn btn-sm btn-outline-secondary" id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                    <span class="fw-bold" id="currentMonthLabel"><?= date('F Y') ?></span>
                    <button class="btn btn-sm btn-outline-secondary" id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div id="calendarHeatmap"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card animate-fade-up stagger-2 h-100">
            <div class="card-header py-2">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Total Pesanan Per Bulan</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 200px; margin: 0 auto;">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row: Weekly Orders + Stock -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card animate-fade-up stagger-3 h-100">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2 text-success"></i>Pesanan Mingguan</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 220px;">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card animate-fade-up stagger-4 h-100">
            <div class="card-header">
                <h5><i class="fas fa-warehouse me-2 text-warning"></i>Stok Terkini</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 220px;">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Revenue Chart -->
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card animate-fade-up stagger-5">
            <div class="card-header">
                <h5><i class="fas fa-money-bill-trend-up me-2 text-primary"></i>Pendapatan Bulanan</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 250px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card animate-fade-up stagger-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-list me-2"></i>Pesanan Terbaru</h5>
        <div>
            <a href="/admin/orders" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i> Lihat Semua
            </a>
            <a href="/admin/orders/create" class="btn btn-success btn-sm ms-1">
                <i class="fas fa-plus me-1"></i> Tambah
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Tanggal Potong</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $o): ?>
                    <tr>
                        <td><strong>#<?= $o['id_order'] ?></strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <strong><?= $o['customer_name'] ?? 'N/A' ?></strong>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $o['package_name'] ?? 'N/A' ?></span></td>
                        <td><i class="far fa-calendar me-1 text-muted"></i><?= date('d/m/Y', strtotime($o['slaughter_date'])) ?></td>
                        <td><strong>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></strong></td>
                        <td>
                            <span class="badge badge-status bg-<?= $o['status'] == 'Completed' ? 'success' : ($o['status'] == 'Pending' ? 'warning' : ($o['status'] == 'Processing' ? 'info' : 'secondary')) ?>">
                                <?= $o['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_orders)): ?>
                    <tr><td colspan="6" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h6>Belum Ada Pesanan</h6>
                            <p>Mulai dengan membuat pesanan baru.</p>
                            <a href="/admin/orders/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Pesanan</a>
                        </div>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan. All rights reserved.</footer>
</main>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check me-2"></i>
                    Detail Pesanan - <span id="modalDateLabel"></span>
                    <span class="badge bg-primary ms-2" id="modalCountLabel">0 orders</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modalBodyContent">
                    <!-- Bulk action bar -->
                    <div id="bulkActionBar" class="p-2 bg-light border-bottom d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted" id="selectedCount">0 dipilih</span>
                            <button class="btn btn-sm btn-success" onclick="bulkMarkCompleted()">
                                <i class="fas fa-check-double me-1"></i>Tandai Selesai
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="modalOrderTable">
                            <thead>
                                <tr>
                                    <th width="30"><input class="form-check-input" type="checkbox" id="selectAllOrders"></th>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Anak</th>
                                    <th>Paket</th>
                                    <th>Hewan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="modalOrderList">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="/admin/orders" class="btn btn-primary btn-sm">
                    <i class="fas fa-list me-1"></i>Lihat Semua Pesanan
                </a>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ once: true });

// Calendar Heatmap Data
let calendarData = {};
let currentYear = <?= date('Y') ?>;
let currentMonth = <?= date('n') ?> - 1; // PHP months are 1-based, JS uses 0-indexed
let selectedOrders = [];

function getMonthLabel(year, month) {
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return months[month] + ' ' + year;
}

function getMaxCount(data) {
    let max = 0;
    Object.values(data).forEach(d => { if (d.count > max) max = d.count; });
    return max || 1;
}

function getDayClasses(count, max) {
    const ratio = count / max;
    if (count === 0) return '';
    if (ratio >= 0.75) return 'bg-dark';
    if (ratio >= 0.5) return 'bg-medium';
    return 'bg-light';
}

function loadCalendarData(month) {
    const monthStr = currentYear + '-' + String(month + 1).padStart(2, '0');
    fetch('/admin/dashboard/calendar-orders?month=' + monthStr)
        .then(res => res.json())
        .then(data => {
            calendarData = data.calendar_data || {};
            renderHeatmap();
        })
        .catch(err => console.log('Calendar data error:', err));
}

function renderHeatmap() {
    const container = document.getElementById('calendarHeatmap');
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const startDay = firstDay === 0 ? 6 : firstDay - 1; // Monday start
    const maxCount = getMaxCount(calendarData);
    
    const dayHeaders = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    let html = '<div class="heatmap-grid"><div class="heatmap-row headers">';
    dayHeaders.forEach(d => { html += '<div class="day-header">' + d + '</div>'; });
    html += '</div>';
    
    // Calculate weeks
    const totalCells = startDay + daysInMonth;
    const weeks = Math.ceil(totalCells / 7);
    
    for (let w = 0; w < weeks; w++) {
        html += '<div class="heatmap-row">';
        for (let d = 0; d < 7; d++) {
            const dayNum = w * 7 + d - startDay + 1;
            if (dayNum >= 1 && dayNum <= daysInMonth) {
                const dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(dayNum).padStart(2, '0');
                const data = calendarData[dateStr] || { count: 0 };
                const cls = getDayClasses(data.count, maxCount);
                const bgOpacity = data.count > 0 ? Math.max(0.15, (data.count / maxCount) * 0.85) : 0;
                
                html += `<div class="day-cell ${cls}" data-date="${dateStr}" data-count="${data.count}" 
                              style="background: rgba(76, 175, 80, ${bgOpacity}); cursor: ${data.count > 0 ? 'pointer' : 'default'};"
                              onclick="${data.count > 0 ? "showDayOrders('" + dateStr + "')" : ''}">
                            <div class="day-number">${dayNum}</div>`;
                if (data.count > 0) {
                    html += `<div class="day-badge">${data.count}</div>`;
                }
                html += '</div>';
            } else {
                html += '<div class="day-cell empty"></div>';
            }
        }
        html += '</div>';
    }
    
    html += '</div>';
    container.innerHTML = html;
    
    // Update label
    document.getElementById('currentMonthLabel').textContent = getMonthLabel(currentYear, currentMonth);
}

function showDayOrders(dateStr) {
    const data = calendarData[dateStr];
    if (!data || !data.orders || data.orders.length === 0) return;
    
    selectedOrders = [];
    
    // Update modal header
    const dateParts = dateStr.split('-');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];
    document.getElementById('modalDateLabel').textContent = 
        dateParts[2] + ' ' + months[parseInt(dateParts[1]) - 1] + ' ' + dateParts[0];
    document.getElementById('modalCountLabel').textContent = data.count + ' pesanan';
    
    // Build order list
    const tbody = document.getElementById('modalOrderList');
    let html = '';
    
    data.orders.forEach(order => {
        const canComplete = !order.delivery_date || new Date(order.delivery_date + 'T' + (order.delivery_time || '00:00:00')) <= new Date();
        const isCompleted = order.status === 'Completed' || order.status === 'Cancelled';
        
        let statusColor = 'secondary';
        if (order.status === 'Completed') statusColor = 'success';
        else if (order.status === 'Pending') statusColor = 'warning';
        else if (order.status === 'Processing') statusColor = 'info';
        else if (order.status === 'Scheduled') statusColor = 'secondary';
        else if (order.status === 'Cancelled') statusColor = 'danger';
        
        html += `<tr data-order-id="${order.id_order}">`;
        html += `<td><input class="form-check-input order-checkbox" type="checkbox" value="${order.id_order}" data-delivery="${order.delivery_date || ''}" ${isCompleted || !canComplete ? 'disabled' : ''}></td>`;
        html += `<td><strong>#${order.id_order}</strong></td>`;
        html += `<td>${order.customer_name}</td>`;
        html += `<td>${order.child_name || '-'}</td>`;
        html += `<td><span class="badge bg-primary">${order.package_name}</span></td>`;
        html += `<td>${order.animal_type}</td>`;
        html += `<td><span class="badge badge-status bg-${statusColor}">${order.status}</span></td>`;
        html += '<td>';
        
        // Always show status dropdown for changing status
        html += `<select class="form-select form-select-sm status-select" onchange="updateOrderStatus(${order.id_order}, this.value)" style="font-size:0.7rem;padding:0.1rem 0.2rem;">
            <option value="Pending" ${order.status === 'Pending' ? 'selected' : ''}>Pending</option>
            <option value="Scheduled" ${order.status === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
            <option value="Processing" ${order.status === 'Processing' ? 'selected' : ''}>Processing</option>
            <option value="Completed" ${order.status === 'Completed' ? 'selected' : ''}>Completed</option>
            <option value="Cancelled" ${order.status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
        </select>`;
        
        html += '</td></tr>';
    });
    
    tbody.innerHTML = html;
    
    // Show/hide bulk action bar
    const hasCompleteBtn = data.orders.some(o => {
        const completed = o.status === 'Completed' || o.status === 'Cancelled';
        const due = !o.delivery_date || new Date() >= new Date(o.delivery_date);
        return !completed && due;
    });
    document.getElementById('bulkActionBar').className = hasCompleteBtn ? 'p-2 bg-light border-bottom' : 'p-2 bg-light border-bottom d-none';
    
    // Setup select all checkbox
    document.getElementById('selectAllOrders').onclick = function() {
        const checkboxes = document.querySelectorAll('.order-checkbox:not(:disabled)');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    };
    
    // Setup individual checkboxes
    document.querySelectorAll('.order-checkbox').forEach(cb => {
        cb.onclick = updateSelectedCount;
    });
    
    // Show modal
    new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.order-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' dipilih';
}

/**
 * Update order status via AJAX
 * @param {number} orderId - Order ID
 * @param {string} newStatus - New status (Pending/Scheduled/Processing/Completed/Cancelled)
 */
async function updateOrderStatus(orderId, newStatus) {
    try {
        const res = await fetch('/admin/dashboard/mark-completed', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: orderId, status: newStatus})
        });
        const result = await res.json();
        
        if (result.success) {
            // Update status badge and action cell in the modal row
            const statusCell = document.querySelector(`[data-order-id="${orderId}"] td:nth-child(7)`);
            const actionCell = document.querySelector(`[data-order-id="${orderId}"] td:nth-child(8)`);
            
            if (statusCell) {
                let color = 'secondary';
                if (newStatus === 'Completed') color = 'success';
                else if (newStatus === 'Pending') color = 'warning';
                else if (newStatus === 'Processing') color = 'info';
                else if (newStatus === 'Cancelled') color = 'danger';
                else if (newStatus === 'Scheduled') color = 'secondary';
                
                statusCell.innerHTML = '<span class="badge badge-status bg-' + color + '">' + newStatus + '</span>';
            }
            
            // If switching to Pending or Processing, show Complete button again
            // If switching to Completed or Cancelled, hide Complete button
            if (actionCell) {
                if (newStatus === 'Completed' || newStatus === 'Cancelled') {
                    actionCell.innerHTML = '<span class="text-success small"><i class="fas fa-check-circle"></i> ' + newStatus + '</span>';
                    // Disable checkbox for this row
                    const cb = actionCell.closest('tr').querySelector('.order-checkbox');
                    if (cb) cb.disabled = true;
                } else {
                    // Show complete/change button for non-completed statuses
                    const canComplete = false; // Will be recalculated
                    actionCell.innerHTML = `
                        <select class="form-select form-select-sm status-select" onchange="updateOrderStatus(${orderId}, this.value)" style="font-size:0.7rem;padding:0.1rem 0.2rem;">
                            <option value="Pending" ${newStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                            <option value="Scheduled" ${newStatus === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
                            <option value="Processing" ${newStatus === 'Processing' ? 'selected' : ''}>Processing</option>
                            <option value="Completed" ${newStatus === 'Completed' ? 'selected' : ''}>Completed</option>
                            <option value="Cancelled" ${newStatus === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>`;
                }
            }
            
            // Refresh the modal content after a short delay
            setTimeout(() => {
                const dateStr = document.querySelector('.day-cell:not(.empty)')?.dataset.date;
                if (dateStr) showDayOrders(dateStr);
                loadCalendarData(currentMonth);
            }, 500);
            
            // Show brief success notification
            showNotification('Success: Status order #' + orderId + ' changed to ' + newStatus, 'success');
        } else {
            showNotification(result.message || 'Gagal mengubah status', 'danger');
        }
    } catch (err) {
        console.error(err);
        showNotification('Terjadi kesalahan saat mengubah status', 'danger');
    }
}

/**
 * Show notification toast/banner
 */
function showNotification(message, type) {
    const div = document.createElement('div');
    div.className = 'alert alert-' + type + ' position-fixed';
    div.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:300px;animation:slideIn 0.3s ease;';
    div.textContent = message;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

async function bulkMarkCompleted() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
    
    if (ids.length === 0) {
        alert('Pilih pesanan terlebih dahulu');
        return;
    }
    
    if (!confirm('Tandai ' + ids.length + ' pesanan sebagai Selesai?')) return;
    
    try {
        const res = await fetch('/admin/dashboard/bulk-mark-completed', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ids: ids})
        });
        const result = await res.json();
        
        if (result.success) {
            alert(result.message);
            // Re-render modal order list from scratch
            const dateStr = document.querySelector('.day-cell:not(.empty)')?.dataset.date;
            if (dateStr) {
                showDayOrders(dateStr);
            }
            // Reload heatmap
            loadCalendarData(currentMonth);
        } else {
            alert(result.message || 'Gagal memproses');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Load heatmap data
    loadCalendarData(currentMonth);
    
    // Month navigation
    document.getElementById('prevMonth').onclick = function() {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        loadCalendarData(currentMonth);
    };
    
    document.getElementById('nextMonth').onclick = function() {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        loadCalendarData(currentMonth);
    };
    
    // Load chart data for monthly, weekly, stock charts
    fetch('/admin/dashboard/chart-data')
        .then(res => res.json())
        .then(data => {
            // Weekly Orders Chart (Bar)
            const weeklyCtx = document.getElementById('weeklyChart');
            if (weeklyCtx) {
                new Chart(weeklyCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.weekly_labels,
                        datasets: [{
                            label: 'Pesanan',
                            data: data.weekly_data,
                            backgroundColor: 'rgba(40, 167, 69, 0.6)',
                            borderColor: '#28a745',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
            
            // Stock Chart (Horizontal Bar)
            const stockCtx = document.getElementById('stockChart');
            if (stockCtx) {
                new Chart(stockCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.stock_labels,
                        datasets: [{
                            label: 'Jumlah',
                            data: data.stock_data,
                            backgroundColor: data.stock_colors,
                            borderColor: data.stock_colors.map(c => c),
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
            
            // Monthly Orders Chart (Bar) - Right sidebar
            const orderCtx = document.getElementById('orderChart');
            if (orderCtx) {
                new Chart(orderCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.monthly_labels,
                        datasets: [{
                            label: 'Pesanan',
                            data: data.monthly_orders,
                            backgroundColor: 'rgba(13, 110, 253, 0.6)',
                            borderColor: '#0d6efd',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }
            
            // Monthly Revenue Chart (Line)
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                new Chart(monthlyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.monthly_labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: data.monthly_revenue,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#0d6efd',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(err => console.log('Chart data not available:', err));
});
</script>
<style>
.heatmap-grid { display: flex; flex-direction: column; gap: 2px; }
.heatmap-row { display: flex; gap: 2px; }
.heatmap-row.headers .day-header {
    flex: 1; text-align: center; font-size: 0.7rem; font-weight: 600; 
    color: var(--text-muted); padding: 4px 0;
}
.day-cell {
    flex: 1; aspect-ratio: 1; border-radius: 6px; 
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    position: relative; transition: all 0.2s ease; min-height: 40px;
    border: 2px solid transparent;
}
.day-cell:hover { transform: scale(1.1); z-index: 1; border-color: var(--primary-color); }
.day-cell.empty { background: transparent !important; cursor: default; }
.day-number { font-size: 0.65rem; font-weight: 600; line-height: 1; }
.day-badge {
    font-size: 0.55rem; font-weight: 700; color: white;
    background: rgba(0,0,0,0.3); border-radius: 50%;
    width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;
    position: absolute; bottom: 2px; right: 2px;
}
.bg-dark { background: rgba(76, 175, 80, 0.85) !important; color: white; }
.bg-medium { background: rgba(76, 175, 80, 0.55) !important; color: white; }
.bg-light { background: rgba(76, 175, 80, 0.25) !important; color: white; }
</style>
</body>
</html>