<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pemesanan #<?= $order['id_order'] ?></title>
    <style>
        @page { margin: 12mm 10mm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .header h1 {
            color: #1B5E20;
            font-size: 16pt;
            margin: 0;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .header p {
            color: #666;
            font-size: 8pt;
            margin: 2px 0 0;
        }
        .title-section {
            text-align: center;
            margin: 6px 0;
        }
        .title-section h3 {
            font-size: 12pt;
            color: #1B5E20;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 0;
        }
        .child-name-big {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            color: #1B5E20;
            margin: 6px auto;
            padding: 6px 10px;
            border: 2px solid #2E7D32;
            border-radius: 4px;
            background: #f0f8f0;
            max-width: 90%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 9pt;
        }
        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .info-table .label {
            width: 28%;
            font-weight: bold;
            color: #555;
        }
        .info-table .separator {
            width: 3%;
            text-align: center;
        }
        .info-table .value {
            width: 69%;
        }
        h4 {
            color: #1B5E20;
            font-size: 10pt;
            margin: 6px 0 3px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .menu-table th {
            background: #2E7D32;
            color: white;
            padding: 3px 5px;
            text-align: left;
        }
        .menu-table td {
            padding: 2px 5px;
            border-bottom: 1px solid #ddd;
        }
        .menu-table tr:nth-child(even) td {
            background: #f9fdf9;
        }
        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 7.5pt;
            color: #999;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
        .footer .signature {
            margin-top: 20px;
        }
        .footer .signature p {
            margin: 0;
        }
        hr {
            border: none;
            border-top: 1px solid #2E7D32;
            margin: 6px 0;
        }
        .closing {
            text-align: center;
            font-weight: bold;
            color: #2E7D32;
            font-size: 9pt;
            margin: 6px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>IBRAHIM AQIQAH</h1>
        <p>Detail Pemesanan | Dicetak: <?= date('d/m/Y H:i') ?></p>
    </div>
    
    <div class="title-section">
        <h3>BISMILLAHIRRAHMANIRRAHIM</h3>
    </div>
    
    <div class="child-name-big">
        <?= htmlspecialchars($customer['child_name'] ?? 'N/A') ?> bin <?= htmlspecialchars($customer['name'] ?? 'N/A') ?>
    </div>
    
    <table class="info-table">
        <tr>
            <td class="label">Nama Pemesan</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($customer['name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Nama Anak</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($customer['child_name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($customer['gender'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td class="separator">:</td>
            <td class="value"><?= date('d F Y', strtotime($customer['birth_date'])) ?></td>
        </tr>
        <tr>
            <td class="label">No. Telepon</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td class="value"><?= nl2br(htmlspecialchars($customer['address'] ?? 'N/A')) ?></td>
        </tr>
    </table>
    
    <hr>
    
    <h4>Detail Hewan & Paket</h4>
    <table class="info-table">
        <tr>
            <td class="label">Jenis Hewan</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($order['animal_type'] ?? 'N/A') ?> (<?= htmlspecialchars($order['animal_gender'] ?? 'N/A') ?>)</td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td class="separator">:</td>
            <td class="value"><?= $order['jumlah_anak'] ?> ekor</td>
        </tr>
        <tr>
            <td class="label">Paket</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($package['name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Bobot</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($package['weight_type'] ?? 'N/A') ?> (<?= $package['min_weight'] ?> - <?= $package['max_weight'] ?> kg)</td>
        </tr>
        <tr>
            <td class="label">Penyembelihan</td>
            <td class="separator">:</td>
            <td class="value"><?= htmlspecialchars($order['penyembelihan'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Potong</td>
            <td class="separator">:</td>
            <td class="value"><?= date('d F Y', strtotime($order['slaughter_date'])) ?></td>
        </tr>
        <tr>
            <td class="label">Jam Potong</td>
            <td class="separator">:</td>
            <td class="value"><?= substr($order['slaughter_time'], 0, 5) ?> WIB</td>
        </tr>
        <tr>
            <td class="label">Tanggal Antar</td>
            <td class="separator">:</td>
            <td class="value"><?= date('d F Y', strtotime($order['delivery_date'])) ?></td>
        </tr>
        <tr>
            <td class="label">Fitur Foto</td>
            <td class="separator">:</td>
            <td class="value">
                <?php if ($order['use_photo_card']): ?>Kartu Ucapan<?php endif; ?>
                <?php if ($order['use_photo_card'] && $order['use_photo_certificate']): ?>, <?php endif; ?>
                <?php if ($order['use_photo_certificate']): ?>Sertifikat Foto<?php endif; ?>
                <?php if (!$order['use_photo_card'] && !$order['use_photo_certificate']): ?>-<?php endif; ?>
            </td>
        </tr>
    </table>
    
    <?php if (!empty($details)): ?>
    <h4>Menu & Box</h4>
    <table class="menu-table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:30%;">Menu Tulang</th>
                <th style="width:30%;">Menu Daging</th>
                <th style="width:20%;">Tipe Box</th>
                <th style="width:15%;text-align:center;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; $totalBox = 0; ?>
            <?php foreach ($details as $d): ?>
            <?php $totalBox += (int)$d['jumlah_box']; ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['bone_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['meat_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['box_type'] ?? '-') ?></td>
                <td style="text-align:center;"><?= $d['jumlah_box'] ?> box</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight:bold;background:#e8f5e9;">
                <td colspan="4" style="text-align:right;">Total Box</td>
                <td style="text-align:center;"><?= $totalBox ?> box</td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
    
    <hr>
    
    <div class="closing">"Semoga menjadi ibadah dan keberkahan bagi keluarga"</div>
    
    <div class="footer">
        <div class="signature">
            <p>Hormat Kami,</p>
            <br>
            <p><strong>Ibrahim Aqiqah</strong></p>
        </div>
    </div>
</body>
</html>