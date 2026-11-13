<?php
require_once "koneksi.php";

// Fungsi menghitung diskon
function hitungDiskon($totalBelanja) {
    if ($totalBelanja >= 100000) {
        return round(0.10 * $totalBelanja);
    } elseif ($totalBelanja >= 50000) {
        return round(0.05 * $totalBelanja);
    }
    return 0;
}

// Format rupiah
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Variabel awal
$totalBelanja = "";
$diskon = 0;
$totalBayar = 0;
$hasil = false;
$error = "";

// Memproses tombol
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";
    $totalBelanja = max(0, (int)($_POST["totalBelanja"] ?? 0));

    if ($totalBelanja <= 0) {
        $error = "Masukkan nominal belanja yang valid.";

    } else {

        $diskon = hitungDiskon($totalBelanja);
        $totalBayar = $totalBelanja - $diskon;

        // Jika pengguna memilih simpan
        if ($action == "simpan") {

            $stmt = $conn->prepare(
                "INSERT INTO transaksi
                (total_belanja, diskon, total_bayar)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "iii",
                $totalBelanja,
                $diskon,
                $totalBayar
            );

            if ($stmt->execute()) {
                $stmt->close();

                header("Location: index.php?sukses=1");
                exit;
            } else {
                $error = "Transaksi gagal disimpan.";
            }

            $stmt->close();

        } elseif ($action == "hitung") {

            // Hanya menampilkan hasil, belum menyimpan
            $hasil = true;
        }
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pink Discount Calculator</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#ffe4ef,#fff0f6,#fce4ec);
    min-height: 100vh;
    padding: 35px 15px;
    color: #754052;
}

.container {
    max-width: 480px;
    width: 100%;
    margin: auto;
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.emoji {
    font-size: 48px;
}

.header h1 {
    color: #d85b8c;
    font-size: 27px;
    margin: 8px 0;
    font-weight: 800;
}

.header p {
    font-size: 12px;
    color: #ae8b9a;
}

.card {
    background: rgba(255,255,255,.96);
    padding: 28px;
    border-radius: 25px;
    border: 2px solid white;
    box-shadow: 0 12px 35px rgba(214,100,145,.15);
}

.card h2 {
    color: #d85b8c;
    font-size: 19px;
    margin-bottom: 8px;
}

.subtitle {
    font-size: 12px;
    color: #ae8b9a;
    margin-bottom: 22px;
}

label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 9px;
}

.input-group {
    display: flex;
    align-items: center;
    background: #fff3f7;
    border: 1.5px solid #f5c5d8;
    border-radius: 13px;
    padding: 0 14px;
    margin-bottom: 18px;
}

.input-group span {
    color: #d85b8c;
    font-weight: 700;
}

input {
    width: 100%;
    padding: 14px 10px;
    border: none;
    outline: none;
    background: transparent;
    font-family: inherit;
    font-size: 15px;
    color: #754052;
}

button, .btn {
    width: 100%;
    display: block;
    padding: 14px;
    border: none;
    border-radius: 13px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: .3s;
}

.btn-pink {
    background: linear-gradient(135deg,#f48fb1,#e65f94);
    color: white;
    box-shadow: 0 5px 13px rgba(230,95,148,.25);
}

.btn-pink:hover {
    transform: translateY(-2px);
}

.btn-light {
    background: #fff0f5;
    color: #c94f83;
    border: 1px solid #f5c5d8;
}

.btn-light:hover {
    background: #ffe1ec;
}

.btn-danger {
    background: #fff0f0;
    color: #c34f72;
    border: 1px solid #f4c6d0;
}

.result {
    margin-top: 25px;
    background: #fff5f8;
    padding: 20px;
    border-radius: 17px;
    border: 1px dashed #f0afc8;
}

.result h3 {
    color: #d85b8c;
    font-size: 15px;
    margin-bottom: 18px;
}

.row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    margin: 14px 0;
}

.value {
    font-weight: 600;
}

.discount {
    color: #e65f94;
}

.total {
    background: linear-gradient(135deg,#f48fb1,#e65f94);
    color: white;
    padding: 20px;
    border-radius: 15px;
    margin-top: 20px;
    text-align: center;
}

.total p {
    font-size: 12px;
}

.total h2 {
    color: white;
    font-size: 25px;
    margin-top: 8px;
}

.confirmation {
    margin-top: 20px;
    padding: 17px;
    background: #fff0f5;
    border-radius: 15px;
}

.confirmation h4 {
    color: #d85b8c;
    font-size: 13px;
    margin-bottom: 8px;
}

.confirmation p {
    font-size: 11px;
    color: #ae8b9a;
    line-height: 1.7;
}

.actions {
    display: grid;
    gap: 10px;
    margin-top: 18px;
}

.history-link {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-top: 20px;
    padding: 17px;
    background: white;
    border-radius: 17px;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(214,100,145,.10);
}

.history-icon {
    font-size: 25px;
    background: #ffe5ef;
    padding: 10px;
    border-radius: 12px;
}

.history-text {
    flex: 1;
}

.history-text strong {
    display: block;
    color: #d85b8c;
    font-size: 12px;
}

.history-text small {
    color: #ae8b9a;
    font-size: 10px;
}

.footer {
    text-align: center;
    color: #bd8b9f;
    font-size: 11px;
    margin-top: 25px;
}

.notice {
    background: #fce8f0;
    color: #c94f83;
    padding: 12px;
    border-radius: 12px;
    font-size: 12px;
    margin-bottom: 18px;
}

.error {
    background: #fff0f0;
    color: #bd4b63;
    padding: 12px;
    border-radius: 12px;
    font-size: 12px;
    margin-bottom: 18px;
}

.info {
    background: #fff0f5;
    padding: 15px;
    margin-top: 22px;
    border-radius: 14px;
}

.info h4 {
    color: #d85b8c;
    font-size: 13px;
    margin-bottom: 10px;
}

.info p {
    font-size: 11px;
    color: #966c7e;
    margin: 7px 0;
}

@media(max-width:480px) {
    .card {
        padding: 22px;
    }

    .header h1 {
        font-size: 23px;
    }
}

</style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">

        <div class="emoji">🎀 🛍️</div>

        <h1>Kalkulator Diskon Belanja</h1>

        <p>Calculate your shopping discount ♡</p>

    </div>


    <!-- KALKULATOR -->
    <div class="card">

        <h2>Hai, Happy Shopping! ♥︎</h2>

        <p class="subtitle">
            Masukkan total belanja untuk mengetahui diskonmu.
        </p>

        <?php if (isset($_GET['sukses'])) { ?>
            <div class="notice">
                ♡ Transaksi berhasil disimpan ke riwayat!
            </div>
        <?php } ?>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo e($error); ?>
            </div>
        <?php } ?>


        <!-- FORM HITUNG -->
        <form method="POST">

            <input type="hidden" name="action" value="hitung">

            <label>Total Belanja</label>

            <div class="input-group">

                <span>Rp</span>

                <input
                    type="number"
                    name="totalBelanja"
                    placeholder="Masukkan nominal belanja"
                    min="1"
                    value="<?php echo e($totalBelanja); ?>"
                    required
                >

            </div>

            <button type="submit" class="btn-pink">
            ˗ˏˋ &nbsp;Hitung Diskon Sekarang&nbsp; ˎˊ˗
            </button>

        </form>


        <!-- URAIAN TRANSAKSI MUNCUL SETELAH HITUNG -->
        <?php if ($hasil) { ?>

            <div class="result">

                <h3>ꔫ&nbsp;Rincian Transaksi&nbsp;ꔫ</h3>

                <div class="row">
                    <span>Total Belanja</span>
                    <strong><?php echo rupiah($totalBelanja); ?></strong>
                </div>

                <div class="row">
                    <span>Persentase Diskon</span>
                    <strong class="discount">
                        <?php
                        if ($totalBelanja >= 100000) {
                            echo "10%";
                        } elseif ($totalBelanja >= 50000) {
                            echo "5%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </strong>
                </div>

                <div class="row">
                    <span>Potongan Diskon</span>
                    <strong class="discount">
                        - <?php echo rupiah($diskon); ?>
                    </strong>
                </div>

                <div class="total">

                    <p>Total yang Harus Dibayar</p>

                    <h2><?php echo rupiah($totalBayar); ?></h2>

                    <p>Terima kasih sudah berbelanja!</p>

                </div>

            </div>


            <!-- PILIHAN SIMPAN ATAU BATAL -->
            <div class="confirmation">

                <h4>♡ Konfirmasi Transaksi</h4>

                <p>
                    Periksa kembali rincian belanja kamu.
                    Apakah transaksi ini ingin disimpan
                    ke riwayat?
                </p>

                <div class="actions">

                    <!-- SIMPAN KE DATABASE -->
                    <form method="POST">

                        <input type="hidden"
                               name="action"
                               value="simpan">

                        <input type="hidden"
                               name="totalBelanja"
                               value="<?php echo $totalBelanja; ?>">

                        <button type="submit" class="btn-pink">
                            ⤿&nbsp;Simpan ke Riwayat
                        </button>

                    </form>


                    <!-- BATALKAN HASIL -->
                    <a href="index.php" class="btn btn-light">
                        ⤿&nbsp;Hapus Hasil
                    </a>

                </div>

            </div>

        <?php } ?>


        <!-- KETENTUAN DISKON -->
        <div class="info">

            <h4>🎀 Ketentuan Diskon</h4>

            <p>❥ Belanja ≥ Rp100.000 : Diskon 10%</p>

            <p>❥ Belanja ≥ Rp50.000 : Diskon 5%</p>

            <p>❥ Belanja &lt; Rp50.000 : Tanpa diskon</p>

        </div>

    </div>


    <!-- MENU RIWAYAT -->
    <a href="riwayat.php" class="history-link">

        <div class="history-icon">🧾</div>

        <div class="history-text">

            <strong>Lihat Riwayat Transaksi</strong>

            <small>Lihat dan kelola transaksi belanjamu</small>

        </div>

        <span style="color:#d85b8c;">›</span>

    </a>


    <div class="footer">
        Made with love ♡ Pink Discount Calculator
    </div>

</div>

</body>
</html>

<?php
$conn->close();
?>