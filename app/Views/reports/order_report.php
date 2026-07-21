<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Order - Ibrahim Aqiqah</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1B5E20;
            font-size: 18px;
            margin: 5px 0;
        }
        .header .sub {
            color: #666;
            font-size: 11px;
        }
        .header .logo-text {
            font-size: 14px;
            font-weight: bold;
            color: #1B5E20;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #2E7D32;
            color: #fff;
            padding: 8px;
            font-size: 10px;
            text-align: center;
        }
        table td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table tr:nth-child(even) {
            background: #f9fff9;
        }
        .summary {
            margin-top: 15px;
            border-top: 2px solid #2E7D32;
            padding-top: 10px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            font-size: 12px;
        }
        .summary-item strong {
            color: #2E7D32;
        }
        .status-completed { color: #2E7D32; font-weight: bold; }
        .status-pending { color: #f39c12; font-weight: bold; }
        .status-scheduled { color: #3498db; font-weight: bold; }
        .status-processing { color: #e67e22; font-weight: bold; }
        .footer-note {
            margin-top: 20px;
            font-size: 9px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <?php if (isset($details)): ?>
    <!-- SINGLE ORDER PDF: Large name display -->
    <div class="header">
        <div class="logo-text">🏡 IBRAHIM AQIQAH</div>
        <h1 style="font-size:24px;margin:20px 0 5px;">LAPORAN PESANAN</h1>
        <div class="sub" style="font-size:13px;">Nomor Order: #<?= $order['id_order'] ?? '' ?></div>
        <div class="sub">Dicetak: <?= date('d/m/Y H:i:s') ?></div>
    </div>

    <div style="text-align:center;margin:30px 0;padding:25px;border:3px solid #2E7D32;border-radius:10px;background:#f0fff0;">
        <div style="font-size:13px;color:#666;margin-bottom:5px;">Atas Nama</div>
        <div style="font-size:28px;font-weight:bold;color:#1B5E20;margin-bottom:5px;"><?= strtoupper($customer['name'] ?? '-') ?></div>
        <div style="font-size:16px;color:#555;">Putra / Putri: <strong><?= $customer['child_name'] ?? '-' ?></strong></div>
        <div style="font-size:14px;color:#2E7D32;margin-top:10px;"><?= $order['jumlah_anak'] ?? '1' ?> Ekor <?= $order['animal_type'] ?? '' ?> - <?= $package['name'] ?? '' ?></div>
    </div>

    <table>
        <tr>
            <th style="width:50%;">Detail Pemesan</th>
            <th style="width:50%;">Detail Pesanan</th>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;">
                <strong>Nama:</strong> <?= $customer['name'] ?? '-' ?><br>
                <strong>Anak:</strong> <?= $customer['child_name'] ?? '-' ?><br>
                <strong>Gender:</strong> <?= $customer['gender'] ?? '-' ?><br>
                <strong>Telepon:</strong> <?= $customer['phone'] ?? '-' ?><br>
                <strong>Alamat:</strong> <?= $customer['address'] ?? '-' ?>
            </td>
            <td style="text-align:left;vertical-align:top;">
                <strong>Paket:</strong> <?= $package['name'] ?? '-' ?><br>
                <strong>Hewan:</strong> <?= $order['animal_type'] ?? '-' ?> (<?= $order['animal_gender'] ?? 'Jantan' ?>)<br>
                <strong>Jumlah:</strong> <?= $order['jumlah_anak'] ?? '1' ?> ekor<br>
                <strong>Tgl Potong:</strong> <?= date('d/m/Y', strtotime($order['slaughter_date'])) ?><br>
                <strong>Tgl Antar:</strong> <?= date('d/m/Y', strtotime($order['delivery_date'])) ?><br>
                <strong>Status:</strong> <?= $order['status'] ?? '-' ?><br>
                <strong>Total:</strong> Rp <?= number_format($order['total_price'] ?? 0, 0, ',', '.') ?>
            </td>
        </tr>
    </table>

    <?php if (!empty($details)): ?>
    <h3 style="color:#1B5E20;margin-top:20px;">Menu & Box</h3>
    <table>
        <thead>
            <tr>
                <th>Menu Tulang</th>
                <th>Menu Daging</th>
                <th>Jenis Box</th>
                <th>Jumlah Box</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $d): ?>
            <tr>
                <td><?= $d['bone_name'] ?? '-' ?></td>
                <td><?= $d['meat_name'] ?? '-' ?></td>
                <td><?= $d['box_type'] ?? '-' ?></td>
                <td><?= $d['jumlah_box'] ?? 0 ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="footer-note">
        Dicetak pada: <?= date('d/m/Y H:i:s') ?> | Ibrahim Aqiqah - Sistem Penjadwalan
    </div>
    <?php else: ?>
    <!-- MULTI-ORDER REPORT -->
    <div class="header">
        <div class="logo-text">🏡 IBRAHIM AQIQAH</div>
        <h1>LAPORAN ORDER</h1>
        <div class="sub">Periode: <?= $tanggal_awal ?? date('01/m/Y') ?> - <?= $tanggal_akhir ?? date('d/m/Y') ?></div>
        <div class="sub">Dicetak: <?= date('d/m/Y H:i:s') ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Order</th>
                <th>Customer</th>
                <th>Anak</th>
                <th>Hewan</th>
                <th>Paket</th>
                <th>Tgl. Potong</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
            <?php $no = 1; $totalSum = 0; ?>
            <?php foreach ($orders as $o): ?>
            <?php $totalSum += $o['total_price']; ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>#<?= $o['id_order'] ?></td>
                <td><?= $o['customer_name'] ?? $o['name'] ?></td>
                <td><?= $o['child_name'] ?></td>
                <td><?= $o['animal_type'] ?> (<?= $o['animal_gender'] ?? 'Jantan' ?>)</td>
                <td><?= $o['package_name'] ?? '-' ?></td>
                <td><?= date('d/m/Y', strtotime($o['slaughter_date'])) ?></td>
                <td class="status-<?= strtolower($o['status']) ?>"><?= $o['status'] ?></td>
                <td>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="9" style="text-align: center; color: #999;">Tidak ada data order</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Order:</strong> <?= count($orders) ?? 0 ?></div>
        <div class="summary-item"><strong>Total Pendapatan:</strong> Rp <?= number_format($totalSum ?? 0, 0, ',', '.') ?></div>
    </div>

    <div class="footer-note">
        Dicetak pada: <?= date('d/m/Y H:i:s') ?> | Ibrahim Aqiqah - Sistem Penjadwalan
    </div>
    <?php endif; ?>
</body>
</html>