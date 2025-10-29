// ===== Smooth Scroll untuk Anchor =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// ===== Shadow Navbar saat scroll =====
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    navbar.style.boxShadow = window.scrollY > 50 ? '0 4px 10px rgba(0,0,0,0.2)' : 'none';
});

// ===== Toggle Nav Links =====
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('.nav-toggle');
    if (!toggle) return;
    const links = document.querySelector('.nav-links');
    if (links) links.classList.toggle('show');
});

// ===== Animasi Hover =====
(function() {
    const img = document.getElementById('mainImage');
    if (img) {
        img.style.transition = 'transform 220ms ease, box-shadow 220ms ease';
        img.addEventListener('mouseenter', () => img.style.transform = 'scale(1.03)');
        img.addEventListener('mouseleave', () => img.style.transform = 'scale(1)');
        img.addEventListener('focus', () => img.style.transform = 'scale(1.03)');
        img.addEventListener('blur', () => img.style.transform = 'scale(1)');
    }

    const wa = document.getElementById('waOrder');
    if (wa) {
        wa.addEventListener('click', function() {
            wa.animate([
                { transform: 'scale(1)' },
                { transform: 'scale(0.98)' },
                { transform: 'scale(1)' }
            ], { duration: 220 });
        });
    }
})();

// ===== Intersection Observer untuk Reveal =====
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ===== Animasi Hover WhatsApp Large =====
const waLarge = document.querySelector('.whatsapp-order-large');
if (waLarge) {
    waLarge.addEventListener('mouseenter', () => {
        waLarge.animate([
            { transform: 'translateY(0)' },
            { transform: 'translateY(-3px)' }
        ], { duration: 160, fill: 'forwards' });
    });
}

// ===== Upload File Handler =====
document.addEventListener('change', function(e) {
    const input = e.target;
    if (!input.classList.contains('file-input')) return;

    const file = input.files && input.files[0];
    const wrapper = input.closest('.file-control');
    const fileNameEl = wrapper?.querySelector('.file-name');
    const clearBtn = wrapper?.querySelector('.file-clear');
    const fileError = wrapper?.querySelector('.file-error');
    const previewImg = document.querySelector('#previewImg');

    // Reset jika tidak ada file
    if (!file) {
        if (fileNameEl) fileNameEl.textContent = 'Belum memilih file';
        if (previewImg) previewImg.style.display = 'none';
        if (clearBtn) clearBtn.style.display = 'none';
        return;
    }

    // Validasi tipe file
    const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!allowed.includes(file.type)) {
        if (fileError) { 
            fileError.textContent = 'Tipe file tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.';
            fileError.style.display = 'block'; 
        }
        input.value = '';
        if (fileNameEl) fileNameEl.textContent = 'Belum memilih file';
        if (clearBtn) clearBtn.style.display = 'none';
        if (previewImg) previewImg.style.display = 'none';
        return;
    }

    // Validasi ukuran file (max 3MB)
    if (file.size > 3 * 1024 * 1024) {
        if (fileError) { 
            fileError.textContent = 'Ukuran file terlalu besar. Maks 3MB.'; 
            fileError.style.display = 'block'; 
        }
        input.value = '';
        if (fileNameEl) fileNameEl.textContent = 'Belum memilih file';
        if (clearBtn) clearBtn.style.display = 'none';
        if (previewImg) previewImg.style.display = 'none';
        return;
    }

    // Reset error
    if (fileError) { fileError.textContent = ''; fileError.style.display = 'none'; }
    if (fileNameEl) fileNameEl.textContent = file.name;
    if (clearBtn) clearBtn.style.display = 'inline-block';

    // Preview gambar
    if (previewImg) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            previewImg.style.display = 'block';
            previewImg.classList.add('is-visible');
        };
        reader.readAsDataURL(file);
    }
});

// ===== Tombol Clear File =====
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('file-clear')) {
        const wrapper = e.target.closest('.file-control');
        const input = wrapper.querySelector('.file-input');
        const fileNameEl = wrapper.querySelector('.file-name');
        const previewImg = document.querySelector('#previewImg');

        input.value = '';
        if (fileNameEl) fileNameEl.textContent = 'Belum memilih file';
        if (previewImg) previewImg.style.display = 'none';
        e.target.style.display = 'none';
    }
});

// ===== Inisialisasi Preview saat DOM Loaded =====
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('previewImg');
    const clearBtn = document.querySelector('.file-clear');
    const fileName = document.querySelector('.file-name');

    if (!preview) return;

    const src = preview.getAttribute('src') || '';
    if (src && src.includes('uploads/') && src.trim() !== '') {
        preview.style.display = 'block';
        if (clearBtn) clearBtn.style.display = 'inline-block';
        if (fileName && !fileName.textContent.trim()) fileName.textContent = 'Gambar saat ini';
    } else {
        preview.style.display = 'none';
        if (clearBtn) clearBtn.style.display = 'none';
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            const input = document.querySelector('.file-input');
            if (input) input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            clearBtn.style.display = 'none';
            if (fileName) fileName.textContent = 'Belum memilih file';
        });
    }
});