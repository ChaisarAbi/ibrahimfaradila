<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Ibrahim Aqiqah</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap');
        
        :root {
            --primary-color: #2E7D32;
            --primary-dark: #1B5E20;
            --primary-light: #4CAF50;
            --primary-gradient: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            --primary-gradient-hover: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            --secondary-color: #FFA000;
            --accent-color: #00BCD4;
            --bg-gray: #f4f6f9;
            --bg-card: #ffffff;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.12);
            --shadow-glow: 0 0 20px rgba(46,125,50,0.3);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-gray);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #e0e0e0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(46,125,50,0.4); }
            50% { box-shadow: 0 0 0 15px rgba(46,125,50,0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes rotate-in {
            from { transform: rotate(-180deg) scale(0); opacity: 0; }
            to { transform: rotate(0) scale(1); opacity: 1; }
        }

        .animate-fade-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-fade-left { animation: fadeInLeft 0.6s ease-out both; }
        .animate-slide-in { animation: slideIn 0.4s ease-out both; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-rotate-in { animation: rotate-in 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55) both; }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }

        /* Page Title */
        .page-title {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 24px;
            font-size: 1.75rem;
            position: relative;
            padding-bottom: 12px;
            letter-spacing: -0.5px;
        }
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 4px;
        }
        .page-title i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Cards Modern */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            background: var(--bg-card);
            overflow: hidden;
        }
        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .card-header {
            background: linear-gradient(135deg, #f8fdf8 0%, #f0f7f0 100%);
            border-bottom: 2px solid var(--primary-color);
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 16px 24px;
        }
        .card-header h5 {
            color: var(--primary-dark);
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }
        .card-body { padding: 24px; }
        .card-footer {
            background: #f8fdf8;
            border-top: 1px solid #e8f0e8;
            border-radius: 0 0 var(--border-radius) var(--border-radius) !important;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .glass-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Stat Cards Modern */
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 20px 24px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(46,125,50,0.06);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px 3px 0 0;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: var(--transition);
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.05);
        }
        .stat-card h4 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 500;
        }
        .stat-card .stat-footer {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Buttons Premium */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 9px 18px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            font-size: 0.9rem;
        }
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            box-shadow: 0 3px 10px rgba(46,125,50,0.25);
        }
        .btn-primary:hover {
            background: var(--primary-gradient-hover);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(46,125,50,0.35);
            color: white;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-1px);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            box-shadow: 0 3px 10px rgba(40,167,69,0.25);
        }
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.35);
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            border: none;
            box-shadow: 0 3px 10px rgba(220,53,69,0.2);
        }
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(220,53,69,0.3);
        }
        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            border: none;
            color: white;
        }
        .btn-sm { padding: 5px 12px; font-size: 0.78rem; }
        .btn-lg { padding: 12px 24px; font-size: 1rem; }
        .btn-group-sm > .btn, .btn-sm.btn {
            padding: 4px 10px;
            font-size: 0.75rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(46,125,50,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        .input-group-text {
            background: #f8fdf8;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: var(--primary-color);
            font-size: 1rem;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            color: white;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border: none;
            white-space: nowrap;
        }
        .table thead th:first-child { border-radius: 10px 0 0 0; }
        .table thead th:last-child { border-radius: 0 10px 0 0; }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 14px;
            border-bottom: 1px solid #edf2f7;
            font-size: 0.88rem;
            color: #4a5568;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: #f0fff4;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background: #fafdfa;
        }
        .table-hover tbody tr:hover {
            background: #e8f5e9 !important;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-status.bg-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
        .badge-status.bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14) !important; }
        .badge-status.bg-info { background: linear-gradient(135deg, #17a2b8, #00bcd4) !important; }
        .badge-status.bg-danger { background: linear-gradient(135deg, #dc3545, #e74c3c) !important; }
        .badge-status.bg-secondary { background: linear-gradient(135deg, #6c757d, #95a5a6) !important; }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            animation: slideIn 0.4s ease-out;
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }
        .modal-header {
            background: linear-gradient(135deg, #f8fdf8, #f0f7f0);
            border-bottom: 2px solid var(--primary-color);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        .modal-title { color: var(--primary-dark); font-weight: 700; }

        /* FullCalendar Override */
        #calendar {
            min-height: 500px;
        }
        .fc {
            font-family: 'Inter', sans-serif !important;
        }
        .fc .fc-toolbar-title {
            color: var(--primary-dark) !important;
            font-weight: 700 !important;
            font-size: 1.4rem !important;
        }
        .fc .fc-button-primary {
            background: var(--primary-gradient) !important;
            border: none !important;
            box-shadow: 0 2px 10px rgba(46,125,50,0.2) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            transition: var(--transition) !important;
        }
        .fc .fc-button-primary:hover {
            background: var(--primary-gradient-hover) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(46,125,50,0.3) !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--primary-dark) !important;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(46,125,50,0.05) !important;
        }
        .fc .fc-daygrid-day-number {
            color: var(--text-dark) !important;
            font-weight: 500 !important;
        }
        .fc-event {
            border-radius: 8px !important;
            padding: 4px 8px !important;
            font-size: 0.8rem !important;
            cursor: pointer;
            border: none !important;
            font-weight: 500 !important;
            transition: var(--transition) !important;
        }
        .fc-event:hover {
            transform: scale(1.03) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        }

        /* Footer */
        footer {
            color: var(--text-muted);
            font-size: 0.8rem;
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
        }

        /* Notification Badge Pulse */
        .pulse-dot {
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-glow 2s infinite;
            margin-left: 6px;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 16px;
            font-size: 0.85rem;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .breadcrumb-custom .breadcrumb-item.active {
            color: var(--text-muted);
        }
        .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
            content: '›';
            font-size: 1.1rem;
        }

        /* Page Header with Breadcrumb */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header .page-title {
            margin-bottom: 4px;
        }
        .page-header .page-subtitle {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        /* Filter Card Compact */
        .filter-card .card-body {
            padding: 12px 16px;
        }
        .filter-card .form-label {
            font-size: 0.75rem;
            margin-bottom: 4px;
        }
        .filter-card .form-control-sm, 
        .filter-card .form-select-sm {
            font-size: 0.8rem;
            padding: 6px 10px;
        }

        /* Action Buttons Group */
        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        /* Empty State */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 12px;
            display: block;
        }
        .empty-state h6 {
            color: var(--text-dark);
            font-weight: 600;
        }
        .empty-state p {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        /* Smooth scrollbar for main content */
        .main-content {
            scrollbar-width: thin;
        }
        .main-content::-webkit-scrollbar {
            width: 6px;
        }
        .main-content::-webkit-scrollbar-track {
            background: transparent;
        }
        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        .main-content::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title { font-size: 1.2rem; }
            .stat-card h4 { font-size: 1.3rem; }
            .card-body { padding: 14px; }
            .table { font-size: 0.78rem; }
            .table thead th, .table tbody td { padding: 8px 10px; }
            .stat-card { padding: 16px; }
        }

        /* Print Styles */
        @media print {
            body { background: white !important; }
            .sidebar, .navbar, .btn, footer, .mobile-bottom-nav { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            .stat-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            .table thead th { background: #f7fafc !important; color: #2d3748 !important; }
        }
    </style>
</head>
<body></body>