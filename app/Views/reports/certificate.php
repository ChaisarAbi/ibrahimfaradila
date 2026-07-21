<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Aqiqah - Ibrahim Aqiqah</title>
    <style>
        @page {
            margin: 10mm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Times New Roman', serif;
            color: #2c3e50;
        }
        .certificate-border {
            border: 6px solid #b8860b;
            padding: 6px;
        }
        .certificate-inner {
            border: 2px solid #c9a84c;
            padding: 25px 30px;
        }
        .header-section {
            text-align: center;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 10px;
        }
        .header-section .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a3c2e;
            letter-spacing: 3px;
        }
        .header-section .brand-sub {
            font-size: 10px;
            color: #7f8c8d;
        }
        .certificate-title {
            text-align: center;
            margin: 10px 0;
        }
        .certificate-title h1 {
            font-size: 28px;
            color: #b8860b;
            letter-spacing: 4px;
            margin: 0;
        }
        .gold-line {
            width: 180px;
            height: 2px;
            background-color: #c9a84c;
            margin: 5px auto;
        }
        .bismillah-section {
            text-align: center;
            font-size: 20px;
            color: #1a3c2e;
            margin: 10px 0 3px;
        }
        .hadits-section {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            font-style: italic;
            margin: 0 0 10px;
        }
        .content-section {
            text-align: center;
            margin: 8px 0;
            font-size: 12px;
            line-height: 1.6;
        }
        .content-section .highlight {
            color: #1a3c2e;
            font-weight: bold;
        }
        .child-name-box {
            text-align: center;
            margin: 10px auto;
            padding: 8px 20px;
            border: 2px solid #c9a84c;
            background-color: #fdfcf0;
            width: 80%;
        }
        .child-name-box .child-name {
            font-size: 22px;
            font-weight: bold;
            color: #1a3c2e;
        }
        .child-name-box .bin-text {
            font-size: 16px;
            color: #b8860b;
            font-style: italic;
        }
        .details-table {
            margin: 8px auto;
            border-collapse: collapse;
            width: 80%;
        }
        .details-table td {
            padding: 3px 10px;
            text-align: center;
            vertical-align: top;
            border: none;
        }
        .details-table .label {
            font-size: 8px;
            color: #7f8c8d;
        }
        .details-table .value {
            font-size: 12px;
            font-weight: bold;
            color: #1a3c2e;
        }
        .divider {
            height: 1px;
            background-color: #c9a84c;
            margin: 10px 0;
        }
        .package-info {
            text-align: center;
            margin: 8px 0;
            font-size: 11px;
        }
        .package-info .highlight {
            color: #1a3c2e;
            font-weight: bold;
            font-size: 13px;
        }
        .footer-section {
            margin-top: 20px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 10px;
        }
        .signature-label {
            font-size: 8px;
            color: #7f8c8d;
        }
        .stamp-circle {
            width: 60px;
            height: 60px;
            border: 2px dashed #b8860b;
            border-radius: 50%;
            margin: 3px auto;
            text-align: center;
            font-size: 7px;
            color: #b8860b;
        }
        .signature-line {
            width: 120px;
            border-top: 1px solid #333;
            margin: 20px auto 3px;
        }
        .signature-name {
            font-size: 11px;
            font-weight: bold;
            color: #1a3c2e;
        }
        .footer-note {
            text-align: center;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #e0e0e0;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate-border">
        <div class="certificate-inner">
            <!-- Header -->
            <div class="header-section">
                <div style="font-size:30px;margin-bottom:3px;">🕌</div>
                <div class="brand-name">Ibrahim Aqiqah</div>
                <div class="brand-sub">Sistem Penjadwalan Aqiqah Terpercaya</div>
            </div>
            
            <!-- Title -->
            <div class="certificate-title">
                <h1>SERTIFIKAT AQIQAH</h1>
                <div class="gold-line"></div>
            </div>
            
            <!-- Bismillah -->
            <div class="bismillah-section">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</div>
            <div class="hadits-section">"Setiap anak tergadaikan dengan aqiqahnya..." (HR. Ahmad)</div>
            
            <div class="divider"></div>
            
            <!-- Content -->
            <div class="content-section">
                Telah dilaksanakan ibadah Aqiqah pada hari 
                <span class="highlight"><?= $order['hari'] ?></span>, 
                tanggal <span class="highlight"><?= $order['tanggal'] ?></span>,
                atas nama:
            </div>
            
            <!-- Child Name -->
            <div class="child-name-box">
                <div class="child-name">
                    <?= $customer['child_name'] ?> <span class="bin-text">bin</span> <?= $customer['name'] ?>
                </div>
            </div>
            
            <!-- Details -->
            <table class="details-table">
                <tr>
                    <td><div class="label">Jenis Kelamin</div><div class="value"><?= $customer['gender'] ?></div></td>
                    <td><div class="label">Tanggal Lahir</div><div class="value"><?= $customer['birth_date'] ?></div></td>
                    <td><div class="label">Hewan</div><div class="value"><?= $order['animal_type'] ?> (<?= $order['animal_gender'] ?>)</div></td>
                </tr>
            </table>
            
            <div class="divider"></div>
            
   
            
            <!-- Footer Signatures -->
            <div class="footer-section">
                <table class="footer-table">
                    <tr>
                        <td>
                            <div class="signature-label">Penyelenggara</div>
                        
                            <div class="signature-line"></div>
                            <div class="signature-name">Ibrahim Aqiqah</div>
                        </td>
                        <td>
                            <div class="signature-label">Pemesan</div>
                            <div style="height:66px;"></div>
                            <div class="signature-line"></div>
                            <div class="signature-name"><?= $customer['name'] ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Footer Note -->
            <div class="footer-note">
                Dokumen ini sah dan diproses oleh Ibrahim Aqiqah &bull; Dicetak: <?= date('d/m/Y H:i') ?>
            </div>
        </div>
    </div>
</body>
</html>