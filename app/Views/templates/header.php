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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            /* Ibrahim Aqiqah Color Palette - Flat */
            --dark-teal: #25424C;
            --light-orange: #FFA45B;
            --cream: #FFEBDB;
            --orange: #FB770D;
            --dark-red: #8B2635;
            --white: #FFFFFF;
            
            /* Theme Variables */
            --primary-color: var(--orange);
            --primary-dark: var(--dark-teal);
            --primary-light: var(--light-orange);
            --secondary-color: var(--dark-teal);
            --secondary-dark: #1a3038;
            --secondary-light: var(--light-orange);
            --accent-color: var(--light-orange);
            --bg-gray: #FAF5F0;
            --bg-card: var(--white);
            --text-dark: var(--dark-teal);
            --text-muted: #7a8a94;
            --border-radius: 12px;
            --transition: all 0.3s ease;
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
        ::-webkit-scrollbar-track { background: var(--cream); border-radius: 10px; }
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
            0%, 100% { box-shadow: 0 0 0 0 rgba(251, 119, 13, 0.3); }
            50% { box-shadow: 0 0 0 8px rgba(251, 119, 13, 0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .animate-fade-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-fade-left { animation: fadeInLeft 0.6s ease-out both; }
        .animate-slide-in { animation: slideIn 0.4s ease-out both; }
        .animate-float { animation: float 3s ease-in-out infinite; }

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
            background: var(--primary-color);
            border-radius: 4px;
        }
        .page-title i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Cards Modern - Flat */
        .card {
            border: none;
            border-radius: var(--border-radius);
            background: var(--bg-card);
            overflow: hidden;
        }
        .card-header {
            background: var(--cream);
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
            background: var(--cream);
            border-top: 1px solid #ffd5a3;
            border-radius: 0 0 var(--border-radius) var(--border-radius) !important;
        }

        /* Glassmorphism Card - Flat */
        .glass-card {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: var(--border-radius);
        }

        /* Stat Cards Modern - Flat */
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--cream);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            background: var(--cream);
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

        /* Buttons Premium - Flat */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 9px 18px;
            transition: var(--transition);
            font-size: 0.9rem;
        }
        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            color: white;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .btn-success {
            background: var(--dark-teal);
            border: none;
            color: white;
        }
        .btn-success:hover {
            background: var(--secondary-dark);
            color: white;
        }
        .btn-danger {
            background: var(--dark-red);
            border: none;
            color: white;
        }
        .btn-danger:hover {
            background: #6e1f2a;
            color: white;
        }
        .btn-warning {
            background: var(--primary-color);
            border: none;
            color: white;
        }
        .btn-sm { padding: 5px 12px; font-size: 0.78rem; }
        .btn-lg { padding: 12px 24px; font-size: 1rem; }

        /* Form Controls - Flat */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        .input-group-text {
            background: var(--cream);
            border: 2px solid #ffd5a3;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: var(--primary-dark);
            font-size: 1rem;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        /* Tables - Flat */
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: var(--primary-dark);
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
        .table tbody tr:hover {
            background: var(--cream);
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background: #fdf8f3;
        }
        .table-hover tbody tr:hover {
            background: var(--cream) !important;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges - Flat */
        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-status.bg-success { background: var(--dark-teal) !important; color: white; }
        .badge-status.bg-warning { background: var(--primary-color) !important; color: white; }
        .badge-status.bg-info { background: #00897B !important; color: white; }
        .badge-status.bg-danger { background: var(--dark-red) !important; color: white; }
        .badge-status.bg-secondary { background: #6c757d !important; color: white; }

        /* Alerts - Flat */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            animation: slideIn 0.4s ease-out;
        }
        .alert-success {
            background: #e8f5e9;
            color: #1b5e20;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Modal - Flat */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
        }
        .modal-header {
            background: var(--cream);
            border-bottom: 2px solid var(--primary-color);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        .modal-title { color: var(--primary-dark); font-weight: 700; }

        /* FullCalendar Override - Flat */
        #calendar {
            min-height: 380px;
        }
        .fc {
            font-family: 'Inter', sans-serif !important;
        }
        .fc table {
            font-size: 0.85rem !important;
        }
        .fc .fc-toolbar {
            flex-wrap: wrap !important;
            gap: 6px !important;
        }
        .fc .fc-toolbar-title {
            color: var(--primary-dark) !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
        }
        .fc .fc-button {
            padding: 4px 10px !important;
            font-size: 0.78rem !important;
        }
        .fc .fc-button-primary {
            background: var(--primary-color) !important;
            border: none !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            padding: 4px 12px !important;
            font-size: 0.78rem !important;
        }
        .fc .fc-button-primary:hover {
            background: var(--primary-dark) !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--primary-dark) !important;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(251, 119, 13, 0.06) !important;
        }
        .fc .fc-daygrid-day-frame {
            min-height: 50px !important;
        }
        .fc .fc-daygrid-day-number {
            color: var(--text-dark) !important;
            font-weight: 500 !important;
            font-size: 0.82rem !important;
            padding: 2px 4px !important;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            padding: 4px 2px !important;
        }
        .fc-daygrid-more-link {
            font-size: 0.72rem !important;
        }
        .fc-event {
            border-radius: 4px !important;
            padding: 2px 5px !important;
            font-size: 0.7rem !important;
            cursor: pointer;
            border: none !important;
            font-weight: 500 !important;
            margin: 1px 2px !important;
        }
        .fc .fc-toolbar-chunk {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
        }

        /* Footer - Flat */
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
            background: var(--primary-color);
            border-radius: 50%;
            display: inline-block;
            animation: pulse-glow 2s infinite;
            margin-left: 6px;
        }

        /* Breadcrumb - Flat */
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

        /* Page Header */
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

        /* Action Buttons */
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

        /* Smooth scrollbar */
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
            background: #ffd5a3;
            border-radius: 3px;
        }

        /* Icon sizing */
        .icon-md { font-size: 1rem; }
        .icon-lg { font-size: 1.3rem; }
        .icon-sm { font-size: 0.78rem; }

        /* Report card */
        .report-card .card-body {
            padding: 18px 20px;
        }
        .report-card .report-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: var(--cream);
            flex-shrink: 0;
        }
        .report-card h6 {
            font-size: 0.92rem;
            margin-bottom: 1px;
        }
        .report-card small.text-muted {
            font-size: 0.72rem;
        }
        .report-card p.small {
            font-size: 0.78rem;
            line-height: 1.4;
        }

        /* Form section title */
        .form-section-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section-title i {
            font-size: 1rem;
            color: var(--primary-color);
        }

        /* Logo image */
        .brand-icon img, .sidebar-brand img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
<body>
</body>
</html>