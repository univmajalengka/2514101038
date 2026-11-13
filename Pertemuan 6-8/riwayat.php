<?php
require_once "koneksi.php";

// Fungsi format rupiah
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Proses hapus satu transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus_id"])) {

    $id = (int)$_POST["hapus_id"];

    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: riwayat.php?hapus=berhasil");
    exit;
}

// Proses hapus semua transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus_semua"])) {

    $conn->query("DELETE FROM transaksi");

    header("Location: riwayat.php?hapus=semua");
    exit;
}

// Mengambil seluruh riwayat transaksi
$riwayat = $conn->query(
    "SELECT * FROM transaksi ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Riwayat Transaksi - Pink Discount</title>

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
    max-width: 650px;
    width: 100%;
    margin: auto;
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.header .emoji {
    font-size: 45px;
}

.header h1 {
    color: #d85b8c;
    font-size: 26px;
    margin: 8px 0;
}

.header p {
    color: #ae8b9a;
    font-size: 12px;
}

.card {
    background: white;
    padding: 27px;
    border-radius: 24px;
    box-shadow: 0 12px 35px rgba(214,100,145,.15);
}

.card h2 {
    color: #d85b8c;
    font-size: 19px;
    margin-bottom: 7px;
}

.subtitle {
    font-size: 12px;
    color: #ae8b9a;
    margin-bottom: 20px;
}

.notice {
    background: #fce8f0;
    color: #c94f83;
    padding: 12px;
    border-radius: 12px;
    font-size: 12px;
    margin-bottom: 18px;
}

.transaction {
    background: #fff5f8;
    border: 1px dashed #f0afc8;
    border-radius: 17px;
    padding: 18px;
    margin-bottom: 15px;
}

.transaction-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.transaction-header strong {
    color: #d85b8c;
    font-size: 14px;
}

.status {
    background: #fce4ec;
    color: #c94f83;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
}

.row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    font-size: 12px;
    margin: 11px 0;
}

.discount {
    color: #e65f94;
}

.date {
    border-top: 1px solid #f4d5e1;
    padding-top: 12px;
    margin-top: 14px;
    font-size: 10px;
    color: #ae8b9a;
}

.delete-button {
    width: 100%;
    border: none;
    border-radius: 11px;
    background: #ffe1e8;
    color: #c34f72;
    padding: 11px;
    margin-top: 14px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.delete-button:hover {
    background: #ffcbd8;
}

.delete-all {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 12px;
    background: #e87892;
    color: white;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 10px;
}

.back {
    display: block;
    text-align: center;
    text-decoration: none;
    color: #d85b8c;
    font-size: 12px;
    font-weight: 600;
    margin-top: 20px;
}

.empty {
    text-align: center;
    padding: 35px 15px;
    background: #fff5f8;
    border-radius: 16px;
    color: #ae8b9a;
    font-size: 13px;
}

.empty .icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.footer {
    text-align: center;
    font-size: 11px;
    color: #bd8b9f;
    margin-top: 25px;
}

@media(max-width:480px) {
    .card {
        padding: 20px;
    }

    .header h1 {
        font-size: 22px;
    }
}

</style>

</head>

<body>

<div class="container">

    <div class="header">

        <div class="emoji">🎀 🧾</div>

        <h1>Riwayat Transaksi</h1>

        <p>Semua catatan belanjamu tersimpan di sini!</p>

    </div>


    <div class="card">

        <h2>ꔫ Daftar Transaksi ꔫ</h2>

        <p class="subtitle">
            Kelola dan lihat riwayat belanja kamu.
        </p>


        <?php if (isset($_GET['hapus'])) { ?>

            <div class="notice">
                ♡ <?php
                if ($_GET['hapus'] == 'semua') {
                    echo "Semua riwayat berhasil dihapus.";
                } else {
                    echo "Transaksi berhasil dihapus.";
                }
                ?>
            </div>

        <?php } ?>


        <?php if ($riwayat && $riwayat->num_rows > 0) { ?>


            <?php while ($data = $riwayat->fetch_assoc()) { ?>


                <div class="transaction">

                    <div class="transaction-header">

                        <strong>
                            Transaksi #<?php echo e($data['id']); ?>
                        </strong>

                        <span class="status">Tersimpan ♡</span>

                    </div>


                    <div class="row">

                        <span>Total Belanja</span>

                        <strong>
                            <?php echo rupiah($data['total_belanja']); ?>
                        </strong>

                    </div>


                    <div class="row">

                        <span>Diskon</span>

                        <strong class="discount">
                            - <?php echo rupiah($data['diskon']); ?>
                        </strong>

                    </div>


                    <div class="row">

                        <span>Total Pembayaran</span>

                        <strong>
                            <?php echo rupiah($data['total_bayar']); ?>
                        </strong>

                    </div>


                    <div class="date">
                        🕒 <?php echo e($data['tanggal']); ?>
                    </div>


                    <form method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">

                        <input type="hidden"
                               name="hapus_id"
                               value="<?php echo (int)$data['id']; ?>">

                        <button type="submit" class="delete-button">
                            🗑️ Hapus Transaksi Ini
                        </button>

                    </form>

                </div>


            <?php } ?>


            <form method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus SEMUA riwayat transaksi? Data yang dihapus tidak bisa dikembalikan.');">

                <button type="submit"
                        name="hapus_semua"
                        value="1"
                        class="delete-all">

                    🗑️ Hapus Semua Riwayat

                </button>

            </form>


        <?php } else { ?>


            <div class="empty">

                <div class="icon">🛍️</div>

                <strong>Belum Ada Transaksi</strong>

                <p style="margin-top:8px;">
                    Yuk, mulai belanja dan hitung diskonmu!
                </p>

            </div>


        <?php } ?>

    </div>


    <a href="index.php" class="back">
        ← Kembali ke Kalkulator Diskon
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