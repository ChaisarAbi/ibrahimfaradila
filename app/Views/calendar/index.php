<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/calendar"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kalender</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-calendar-week"></i>Kalender Pesanan</h4>
            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('l, d F Y') ?></small>
        </div>
        <div>
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>

    <div class="alert alert-info d-flex align-items-center animate-slide-in">
        <i class="fas fa-info-circle me-2"></i>
        <span>Klik tanggal pada kalender untuk lihat detail pesanan. Anda dapat mengubah status pesanan langsung dari kotak dialog.</span>
    </div>

    <!-- Calendar Heatmap -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card animate-fade-up stagger-1 h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Peta Pesanan - <?= date('F Y') ?></h5>
                    <small class="text-muted">Klik tanggal untuk lihat detail pesanan</small>
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
    </div>

    <!-- Legend -->
    <div class="card mb-4">
        <div class="card-header py-2">
            <h6 class="mb-0"><i class="fas fa-palette me-2"></i>Legenda</h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-block me-1" style="width:18px;height:18px;border-radius:4px;background:rgba(76,175,80,0.85);"></span> Ramai pesanan
                <span class="d-inline-block me-1" style="width:18px;height:18px;border-radius:4px;background:rgba(76,175,80,0.55);"></span> Jumlah medio
                <span class="d-inline-block me-1" style="width:18px;height:18px;border-radius:4px;background:rgba(76,175,80,0.25);"></span> Jumlah sedikit
                <span class="text-muted small ms-auto"><i class="fas fa-percentage me-1"></i>Warna hijau = jumlah pesanan per tanggal (pemotongan)</span>
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
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Calendar Heatmap Data
let calendarData = {};
let currentYear = <?= date('Y') ?>;
let currentMonth = <?= date('n') ?> - 1; // PHP months are 1-based, JS uses 0-indexed
let selectedOrders = [];
let orderDetailModalInstance = null; // Reuse single modal instance to prevent stacking

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
    fetch('/admin/calendar/orders?month=' + monthStr)
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

    // Destroy previous modal instance if exists to prevent stacking
    if (orderDetailModalInstance) {
        orderDetailModalInstance.dispose();
    }

    // Show modal
    orderDetailModalInstance = new bootstrap.Modal(document.getElementById('orderDetailModal'));
    orderDetailModalInstance.show();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.order-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' dipilih';
}

/**
 * Update order status via AJAX (Admin & RPH)
 * @param {number} orderId - Order ID
 * @param {string} newStatus - New status (Pending/Scheduled/Processing/Completed/Cancelled)
 */
async function updateOrderStatus(orderId, newStatus) {
    try {
        const res = await fetch('/admin/calendar/mark-completed', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: orderId, status: newStatus})
        });
        const result = await res.json();

        if (result.success) {
            // Tutup modal langsung setelah sukses ubah status
            if (orderDetailModalInstance) {
                orderDetailModalInstance.hide();
                orderDetailModalInstance.dispose();
                orderDetailModalInstance = null;
            }

            // Tampilkan notifikasi sukses singkat
            showNotification('Status order #' + orderId + ' diubah ke ' + newStatus, 'success');

            // Reload halaman otomatis setelah delay singkat agar notif terbaca
            setTimeout(() => {
                location.reload();
            }, 800);
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
        const res = await fetch('/admin/calendar/bulk-mark-completed', {
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