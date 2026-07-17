<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pemotongan Aqiqah - Ibrahim Aqiqah</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
        }
        .invitation {
            border: 3px solid #2E7D32;
            padding: 30px;
            text-align: center;
        }
        .logo-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo-header .logo-text {
            font-size: 16px;
            font-weight: bold;
            color: #1B5E20;
        }
        .logo-header .logo-sub {
            font-size: 10px;
            color: #666;
        }
        .invitation h1 {
            color: #2E7D32;
            font-size: 22px;
            margin: 10px 0;
        }
        .invitation .bismillah {
            font-size: 20px;
            margin: 12px 0;
            color: #2E7D32;
        }
        .invitation .salam {
            font-style: italic;
            color: #555;
            margin: 10px 0;
        }
        .invitation .content {
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
        }
        .invitation .highlight {
            font-weight: bold;
            color: #2E7D32;
        }
        .invitation .detail-box {
            border: 1px solid #4CAF50;
            padding: 15px;
            margin: 15px 0;
            background: #f9fff9;
            border-radius: 5px;
        }
        .invitation .detail-box p {
            margin: 5px 0;
        }
        .invitation .footer {
            margin-top: 30px;
        }
        .child-name-title {
            font-size: 20px;
            color: #1B5E20;
            margin: 15px 0;
        }
        .invitation .border-decorative {
            border: 1px dashed #4CAF50;
            padding: 20px;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="invitation">
        <div class="border-decorative">
            <!-- Header Ibrahim Aqiqah -->
            <div class="logo-header">
                <div style="font-size: 28px; margin-bottom: 3px;">🏡</div>
                <div class="logo-text">IBRAHIM AQIQAH</div>
                <div class="logo-sub">Sistem Penjadwalan Aqiqah Terpercaya</div>
                <hr style="border: 1px solid #2E7D32; width: 50%; margin: 8px auto;">
            </div>

            <h1>◈ UNDANGAN PEMOTONGAN AQIQAH ◈</h1>
            <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</div>
            <div class="salam">Assalamu'alaikum Warahmatullahi Wabarakatuh</div>
            
            <div class="content">
                <p>Dengan ini kami mengundang Bapak/Ibu/Saudara untuk menyaksikan 
                proses pemotongan hewan Aqiqah atas:</p>
                
                <div class="child-name-title"><?= $customer['child_name'] ?></div>
                <p>Anak dari Bapak/Ibu <span class="highlight"><?= $customer['name'] ?></span></p>
                
                <div class="detail-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 4px; width: 35%; text-align: right;"><strong>Hari/Tanggal</strong></td>
                            <td style="padding: 4px; text-align: center; width: 5%;">:</td>
                            <td style="padding: 4px; text-align: left;"><?= $order['hari'] ?>, <?= $order['tanggal'] ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 4px; text-align: right;"><strong>Waktu</strong></td>
                            <td style="padding: 4px; text-align: center;">:</td>
                            <td style="padding: 4px; text-align: left;"><?= $order['waktu'] ?? '08:00 - 12:00 WIB' ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 4px; text-align: right;"><strong>Jenis Hewan</strong></td>
                            <td style="padding: 4px; text-align: center;">:</td>
                            <td style="padding: 4px; text-align: left;"><?= $order['animal_type'] ?> (<?= $order['animal_gender'] ?>)</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px; text-align: right;"><strong>Metode</strong></td>
                            <td style="padding: 4px; text-align: center;">:</td>
                            <td style="padding: 4px; text-align: left;"><?= $order['penyembelihan'] ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 4px; text-align: right;"><strong>Lokasi</strong></td>
                            <td style="padding: 4px; text-align: center;">:</td>
                            <td style="padding: 4px; text-align: left;">Ibrahim Aqiqah - <?= $customer['address'] ?></td>
                        </tr>
                    </table>
                </div>
                
                <p>Demikian undangan ini disampaikan. Atas perhatian dan kehadirannya, 
                kami ucapkan terima kasih.</p>
            </div>
            
            <div class="footer">
                <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh</p>
                <br>
                <p><strong><?= $customer['name'] ?></strong></p>
            </div>
            
            <div class="footer-note">
                Dicetak oleh Ibrahim Aqiqah - Sistem Penjadwalan &bull; <?= date('d/m/Y H:i') ?>
            </div>
        </div>
    </div>
</body>
</html>