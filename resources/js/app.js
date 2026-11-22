// SIAKAM — interaksi UI ringan tanpa framework.

// Sidebar mobile
const app = document.querySelector('[data-app]');
document.querySelectorAll('[data-nav-toggle]').forEach((btn) =>
    btn.addEventListener('click', () => app?.classList.toggle('nav-open'))
);
document.querySelectorAll('[data-nav-close]').forEach((el) =>
    el.addEventListener('click', () => app?.classList.remove('nav-open'))
);

// Dialog (modal)
document.querySelectorAll('[data-open]').forEach((btn) =>
    btn.addEventListener('click', () => document.getElementById(btn.dataset.open)?.showModal())
);
document.querySelectorAll('dialog').forEach((dlg) => {
    dlg.addEventListener('click', (e) => {
        if (e.target === dlg) dlg.close();
    });
});
document.querySelectorAll('[data-close]').forEach((btn) =>
    btn.addEventListener('click', () => btn.closest('dialog')?.close())
);

// Isi dialog "ubah akun" dari atribut data-* pada tombol
const setField = (form, name, value) => {
    const el = form.querySelector(`[name="${name}"]`);
    if (el) el.value = value;
};
document.querySelectorAll('[data-edit]').forEach((btn) =>
    btn.addEventListener('click', () => {
        const dlg = document.getElementById('dlg-edit');
        if (!dlg) return;
        const form = dlg.querySelector('form');
        form.action = btn.dataset.action;
        setField(form, 'name', btn.dataset.name);
        setField(form, 'email', btn.dataset.email);
        setField(form, 'is_active', btn.dataset.active);
        setField(form, 'password', '');
        setField(form, 'password_confirmation', '');
        dlg.showModal();
    })
);

// Dropdown ruangan mengikuti gedung yang dipilih
const building = document.querySelector('[data-building]');
const room = document.querySelector('[data-room]');
if (building && room) {
    const rooms = JSON.parse(room.dataset.rooms || '{}');
    const fill = () => {
        const keep = room.dataset.selected;
        room.innerHTML = '<option value="">' + (building.value ? 'Pilih ruangan' : 'Pilih gedung dulu') + '</option>';
        (rooms[building.value] || []).forEach((r) =>
            room.add(new Option(r.name, r.id, false, String(r.id) === String(keep)))
        );
        room.disabled = !building.value;
    };
    building.addEventListener('change', () => {
        room.dataset.selected = '';
        fill();
    });
    fill();
}

// Pratinjau foto bukti sebelum diunggah
const photoInput = document.querySelector('[data-photo-input]');
const photoPreview = document.querySelector('[data-photo-preview]');
if (photoInput && photoPreview) {
    photoInput.addEventListener('change', () => {
        const file = photoInput.files?.[0];
        photoPreview.innerHTML = '';
        if (!file) return;
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Pratinjau foto bukti';
        photoPreview.appendChild(img);
    });
}

// Konfirmasi hapus tanpa inline handler
document.querySelectorAll('form[data-confirm]').forEach((form) =>
    form.addEventListener('submit', (e) => {
        if (!confirm(form.dataset.confirm)) e.preventDefault();
    })
);

// Filter yang langsung menerapkan pilihan
document.querySelectorAll('[data-auto]').forEach((el) =>
    el.addEventListener('change', () => el.closest('form')?.submit())
);
