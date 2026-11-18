// Dialog (modal) untuk form tambah/ubah akun
document.querySelectorAll('[data-open]').forEach((btn) =>
  btn.addEventListener('click', () => document.getElementById(btn.dataset.open)?.showModal())
);
document.querySelectorAll('dialog [data-close]').forEach((btn) =>
  btn.addEventListener('click', () => btn.closest('dialog').close())
);

// Isi dialog "ubah akun" dari atribut data-* pada tombol
const setField = (form, name, value) => {
  const el = form.querySelector(`[name="${name}"]`);
  if (el) el.value = value;
};
document.querySelectorAll('[data-edit]').forEach((btn) =>
  btn.addEventListener('click', () => {
    const dlg = document.getElementById('dlg-edit');
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
    (rooms[building.value] || []).forEach((r) => room.add(new Option(r.name, r.id, false, String(r.id) === String(keep))));
    room.disabled = !building.value;
  };
  building.addEventListener('change', () => { room.dataset.selected = ''; fill(); });
  fill();
}
