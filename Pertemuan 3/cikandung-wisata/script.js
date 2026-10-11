const $ = id => document.getElementById(id);
const money = n => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
const adultPrice = 10000;
const childPrice = 5000;
const toast = new bootstrap.Toast($('appToast'), { delay: 2200 });
function showToast(message) { $('toastMessage').textContent = message; toast.show(); }

// Navbar and back-to-top
window.addEventListener('scroll', () => {
  document.querySelector('.glass-nav').classList.toggle('scrolled', window.scrollY > 20);
  $('backTop').classList.toggle('show', window.scrollY > 500);
});
$('backTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
document.querySelectorAll('a[href^="#"]').forEach(link => link.addEventListener('click', () => {
  const menu = $('navMenu');
  if (menu.classList.contains('show')) bootstrap.Collapse.getOrCreateInstance(menu).hide();
}));

// Scroll reveal
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
  }), { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
} else document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));

// Visit type selection (not an official bundled package)
function setVisitType(name) {
  $('packageSelect').value = name;
  updateSummary();
  $('booking').scrollIntoView({ behavior: 'smooth' });
  setTimeout(() => showToast(`${name} dipilih. Isi detail kunjunganmu.`), 400);
}
document.querySelectorAll('.package-select').forEach(button => button.addEventListener('click', () => setVisitType(button.dataset.package)));

function numberValue(id) {
  const value = Math.max(0, Math.min(50, Number($(id).value) || 0));
  $(id).value = value;
  return value;
}
function getTotal() {
  return numberValue('adults') * adultPrice + numberValue('children') * childPrice;
}
function updateSummary() {
  $('sumPackage').textContent = $('packageSelect').value || '—';
  $('sumAdults').textContent = numberValue('adults');
  $('sumChildren').textContent = numberValue('children');
  $('sumDate').textContent = $('visitDate').value
    ? new Date($('visitDate').value + 'T00:00:00').toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
  $('sumTime').textContent = $('visitTime').value || '—';
  $('totalPrice').textContent = money(getTotal());
}
['packageSelect', 'duration', 'visitDate', 'visitTime', 'adults', 'children']
  .forEach(id => {
    const element = $(id);
    if (element) {
      element.addEventListener('input', updateSummary);
    }
  });

[['minusAdults', 'adults', -1], ['plusAdults', 'adults', 1],
 ['minusChildren', 'children', -1], ['plusChildren', 'children', 1]]
  .forEach(([buttonId, inputId, delta]) => {
    const button = $(buttonId);
    const input = $(inputId);

    if (button && input) {
      button.addEventListener('click', () => {
        input.value = Math.max(0, Math.min(50, (Number(input.value) || 0) + delta));
        updateSummary();
      });
    }
  });
// Date cannot be earlier than today
const today = new Date();
$('visitDate').min = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

// Contact method field
function updateContactField() {
  const method = document.querySelector('input[name="contactMethod"]:checked').value;
  const options = {
    WhatsApp: ['Nomor WhatsApp', '08xxxxxxxxxx', 'tel'],
    Instagram: ['Username Instagram', '@username', 'text'],
    Email: ['Alamat Email', 'nama@email.com', 'email']
  };
  const [label, placeholder, type] = options[method];
  $('contactExtraLabel').textContent = label;
  $('contactExtraInput').placeholder = placeholder;
  $('contactExtraInput').type = type;
}
document.querySelectorAll('input[name="contactMethod"]').forEach(radio => radio.addEventListener('change', updateContactField));
updateContactField();

// Photo gallery lightbox
 document.querySelectorAll('.gallery-item').forEach(item => item.addEventListener('click', () => {
  $('lightboxImage').src = item.querySelector('img').src;
  bootstrap.Modal.getOrCreateInstance($('lightboxModal')).show();
}));

// Music button: no autoplay
const musicButton = $('musicToggle');
const bgMusic = $('bgMusic');

if (musicButton && bgMusic) {
  musicButton.addEventListener('click', async () => {
    if (bgMusic.paused) {
      try {
        await bgMusic.play();
        musicButton.classList.add('active');
        musicButton.innerHTML =
          '<i class="bi bi-pause-fill"></i><span>Music</span>';
        musicButton.title = 'Matikan musik';
      } catch (error) {
        console.error('Musik gagal diputar:', error);
        showToast('Musik gagal diputar. Periksa file MP3.');
      }
    } else {
      bgMusic.pause();
      musicButton.classList.remove('active');
      musicButton.innerHTML =
        '<i class="bi bi-music-note"></i><span>Music</span>';
      musicButton.title = 'Nyalakan musik';
    }
  });

  bgMusic.addEventListener('ended', () => {
    musicButton.classList.remove('active');
    musicButton.innerHTML =
      '<i class="bi bi-music-note"></i><span>Music</span>';
  });
}
// Booking validation and e-ticket
$('bookingForm').addEventListener('submit', event => {
  event.preventDefault();
  const form = event.currentTarget;
  const adults = numberValue('adults');
  const children = numberValue('children');
  if (!form.checkValidity() || adults + children < 1) {
    event.stopPropagation();
    form.classList.add('was-validated');
    showToast(adults + children < 1 ? 'Isi minimal satu pengunjung.' : 'Cek kembali data pemesananmu.');
    return;
  }
  const name = $('customerName').value.trim();
  const visitType = $('packageSelect').value;
  const code = 'CKG-' + String(Date.now()).slice(-8);
  const dateText = new Date($('visitDate').value + 'T00:00:00').toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
  const total = adults * adultPrice + children * childPrice;
  $('ticketName').textContent = name;
  $('ticketPackage').textContent = visitType;
  $('ticketAdults').textContent = `${adults} orang`;
  $('ticketChildren').textContent = `${children} orang`;
  $('ticketDate').textContent = dateText;
  $('ticketTime').textContent = $('visitTime').value + ' WIB';
  $('ticketDuration').textContent = $('duration').value;
  $('ticketPayment').textContent = document.querySelector('input[name="paymentMethod"]:checked').value;
  $('ticketCode').textContent = code;
  $('ticketTotal').textContent = money(total);
  $('qrCode').innerHTML = '';
  if (window.QRCode) new QRCode($('qrCode'), { text: `Cikandung|${code}|${name}|${visitType}|${dateText}|dewasa:${adults}|anak:${children}|total:${total}`, width: 110, height: 110, colorDark: '#3b2730', colorLight: '#ffffff' });
  $('ticketSection').classList.remove('d-none');
  setTimeout(() => { $('ticketSection').scrollIntoView({ behavior: 'smooth', block: 'start' }); showToast('E-ticket berhasil dibuat.'); }, 150);
});

// Download e-ticket as PNG
$('downloadTicket').addEventListener('click', async () => {
  if (!window.html2canvas) { showToast('Fitur unduh belum termuat. Coba buka dengan koneksi internet.'); return; }
  try {
    const canvas = await html2canvas($('ticket'), { scale: 2, backgroundColor: '#ffffff', useCORS: true });
    const link = document.createElement('a');
    link.download = `e-ticket-cikandung-${$('ticketCode').textContent}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
    showToast('Tiket siap disimpan.');
  } catch { showToast('Tiket belum bisa diunduh. Coba gunakan tombol Cetak.'); }
});
$('printTicket').addEventListener('click', () => window.print());
$('year').textContent = new Date().getFullYear();
try {
  updateSummary();
} catch (error) {
  console.error('Ringkasan pesanan:', error);
}
