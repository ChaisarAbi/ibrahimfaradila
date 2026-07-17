<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Kerja Dapur - Ibrahim Aqiqah</title>
    <style>
        @page {
            margin: 12px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #1B5E20;
            font-size: 16px;
            margin: 3px 0;
        }
        .header .logo-text {
            font-size: 13px;
            font-weight: bold;
            color: #1B5E20;
        }
        .header .date {
            color: #666;
            font-size: 10px;
        }
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-title {
            background: #2E7D32;
            color: #fff;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        table th {
            background: #4CAF50;
            color: #fff;
            padding: 5px;
            font-size: 9px;
            text-align: center;
        }
        table td {
            padding: 4px 5px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f9fff9;
        }
        .summary-box {
            border: 1px solid #4CAF50;
            padding: 10px;
            margin: 10px 0;
            background: #f0fff0;
            border-radius: 3px;
        }
        .summary-box table {
            margin: 0;
        }
        .summary-box td {
            border: none;
            padding: 3px 8px;
        }
        .label-badge {
            display: inline-block;
            background: #2E7D32;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8px;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 8px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">🏡 IBRAHIM AQIQAH</div>
        <h1>LEMBAR KERJA DAPUR</h1>
        <div class="date"><?= $tanggal ?? date('d/m/Y') ?> | Dicetak: <?= date('d/m/Y H:i:s') ?></div>
    </div>

    <!-- Ringkasan Order Hari Ini -->
    <div class="section">
        <div class="section-title">📋 RINGKASAN ORDER HARI INI</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Anak</th>
                    <th>Hewan</th>
                    <th>Paket</th>
                    <th>Box</th>
                    <th>Menu Tulang</th>
                    <th>Menu Daging</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                <?php $no = 1; $totalBox = 0; ?>
                <?php foreach ($orders as $o): ?>
                <?php $totalBox += $o['jumlah_box'] ?? 0; ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $o['customer_name'] ?? $o['name'] ?></td>
                    <td><?= $o['child_name'] ?></td>
                    <td><?= $o['animal_type'] ?> <?= $o['animal_gender'] ?? '' ?></td>
                    <td><?= $o['package_name'] ?? '-' ?></td>
                    <td><?= $o['jumlah_box'] ?? 0 ?> box</td>
                    <td><?= $o['bone_menu'] ?? 'Gulai' ?></td>
                    <td><?= $o['meat_menu'] ?? 'Sate' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">Tidak ada order untuk hari ini</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Rekap Bahan Baku -->
    <div class="section">
        <div class="section-title">📦 REKAP BOX & BAHAN BAKU</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td><strong>Total Box Hari Ini</strong></td>
                    <td>: <?= $totalBox ?? 0 ?> box</td>
                    <td style="width: 20px;"></td>
                    <td><strong>Total Hewan</strong></td>
                    <td>: <?= count($orders) ?? 0 ?> ekor</td>
                </tr>
                <tr>
                    <td><strong>Kambing</strong></td>
                    <td>: <?= $stock_kambing ?? 0 ?> ekor</td>
                    <td></td>
                    <td><strong>Domba</strong></td>
                    <td>: <?= $stock_domba ?? 0 ?> ekor</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Daftar Menu -->
    <div class="section">
        <div class="section-title">🍽️ RINCIAN MENU PER ORDER</div>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $i => $o): ?>
        <div style="border: 1px solid #ddd; padding: 8px; margin: 5px 0; border-radius: 3px;">
            <strong>#<?= ($i+1) ?> <?= $o['customer_name'] ?? $o['name'] ?></strong> 
            <span class="label-badge"><?= $o['animal_type'] ?? 'Domba' ?></span>
            <table style="margin: 5px 0 0 0;">
                <tr>
                    <td style="border: none; width: 50%;"><strong>Menu Tulang:</strong> <?= $o['bone_menu'] ?? 'Gulai' ?></td>
                    <td style="border: none;"><strong>Menu Daging:</strong> <?= $o['meat_menu'] ?? 'Sate' ?></td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Box Type:</strong> <?= $o['box_type'] ?? 'Box Premium' ?></td>
                    <td style="border: none;"><strong>Jumlah:</strong> <?= $o['jumlah_box'] ?? 0 ?> box</td>
                </tr>
            </table>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Catatan Dapur -->
    <div class="section">
        <div class="section-title">📝 CATATAN DAPUR</div>
        <div style="border: 1px dashed #ccc; height: 80px; padding: 10px; margin: 5px 0;">
            <br><br><br><br>
        </div>
    </div>

    <div class="footer-note">
        Dicetak pada: <?= date('d/m/Y H:i:s') ?> | Ibrahim Aqiqah - Sistem Penjadwalan
    </div>
</body>
</html>