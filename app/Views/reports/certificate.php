<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Aqiqah - Ibrahim Aqiqah</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
        }
        .certificate {
            border: 5px solid #2E7D32;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .logo-header {
            text-align: center;
            margin-bottom: 10px;
            color: #2E7D32;
        }
        .logo-header .logo-text {
            font-size: 18px;
            font-weight: bold;
            color: #1B5E20;
        }
        .logo-header .logo-sub {
            font-size: 11px;
            color: #666;
        }
        .certificate h1 {
            color: #2E7D32;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .certificate h2 {
            color: #1B5E20;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .certificate .bismillah {
            font-size: 24px;
            margin: 15px 0;
            color: #2E7D32;
        }
        .certificate .hadits {
            font-size: 12px;
            font-style: italic;
            color: #555;
            margin: 5px 0 15px 0;
        }
        .certificate .content {
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
            margin: 15px 0;
        }
        .certificate .content .highlight {
            font-weight: bold;
            color: #2E7D32;
            font-size: 16px;
        }
        .certificate .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .certificate .footer .signature {
            text-align: center;
            width: 200px;
        }
        .certificate .divider {
            border: 1px solid #2E7D32;
            margin: 15px 0;
        }
        .certificate .border-decorative {
            border: 2px solid #4CAF50;
            padding: 30px;
            margin: 10px;
        }
        .certificate .label {
            font-size: 12px;
            color: #666;
        }
        .child-name {
            font-size: 26px;
            margin: 20px 0;
            color: #1B5E20;
        }
        .footer-note {
            margin-top: 25px;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .stamp-area {
            width: 100px;
            height: 100px;
            border: 2px dashed #2E7D32;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #2E7D32;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-decorative">
            <!-- Header Ibrahim Aqiqah -->
            <div class="logo-header">
                <div style="font-size: 32px; margin-bottom: 5px;">🏡</div>
                <div class="logo-text">IBRAHIM AQIQAH</div>
                <div class="logo-sub">Sistem Penjadwalan Aqiqah Terpercaya</div>
                <div class="divider" style="width: 60%; margin: 8px auto;"></div>
            </div>

            <h1>▸ SERTIFIKAT AQIQAH ◂</h1>
            <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</div>
            <div class="hadits">"Setiap anak tergadaikan dengan aqiqahnya..." (HR. Ahmad)</div>
            
            <div class="divider"></div>
            
            <div class="content">
                <p>Telah dilaksanakan ibadah Aqiqah pada hari <span class="highlight"><?= $order['hari'] ?></span>, 
                tanggal <span class="highlight"><?= $order['tanggal'] ?></span>,
                atas nama:</p>
                
                <div class="child-name"><?= $customer['child_name'] ?></div>
                
                <p>Anak dari Bapak/Ibu <span class="highlight"><?= $customer['name'] ?></span></p>
                <p>Jenis Kelamin: <span class="highlight"><?= $customer['gender'] ?></span></p>
                <p>Tanggal Lahir: <span class="highlight"><?= $customer['birth_date'] ?></span></p>
                
                <div class="divider"></div>
                
                <p>Hewan yang disembelih: <span class="highlight"><?= $order['animal_type'] ?> (<?= $order['animal_gender'] ?>)</span></p>
                <p>Paket: <span class="highlight"><?= $package['name'] ?></span></p>
                <p>Jumlah Box: <span class="highlight">
                    <?php 
                    $totalBox = 0;
                    if (!empty($details)) {
                        foreach ($details as $d) {
                            $totalBox += $d['jumlah_box'] ?? 0;
                        }
                    }
                    echo $totalBox . ' box';
                    ?>
                </span></p>
            </div>
            
            <div class="divider"></div>
            
            <div class="footer">
                <div class="signature">
                    <p class="label">Penyelenggara</p>
                    <div class="stamp-area">STEMPEL</div>
                    <br>
                    <p>_____________________</p>
                    <p style="font-size: 11px; margin-top: 5px;">(Ibrahim Aqiqah)</p>
                </div>
                <div class="signature">
                    <p class="label">Pemesan</p>
                    <br><br><br>
                    <p>_____________________</p>
                    <p style="font-size: 11px; margin-top: 5px;">(<?= $customer['name'] ?>)</p>
                </div>
            </div>
            
            <div class="footer-note">
                Dokumen ini sah dan diproses oleh Ibrahim Aqiqah - Sistem Penjadwalan &bull; Dicetak: <?= date('d/m/Y H:i') ?>
            </div>
        </div>
    </div>
</body>
</html>