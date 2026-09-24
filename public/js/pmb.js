// ============================================================
// PMB Online - JavaScript umum (dipakai di seluruh halaman)
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // 1. Konfirmasi hapus data (dipasang pada semua form dengan class .form-delete)
    document.querySelectorAll('.form-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (typeof Swal === 'undefined') {
                if (confirm('Yakin ingin menghapus data ini?')) form.submit();
                return;
            }
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang sudah dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // 2. Validasi generik: input dengan class .only-numeric hanya menerima angka
    document.querySelectorAll('.only-numeric').forEach(function (input) {
        input.addEventListener('input', function () {
            const cleaned = this.value.replace(/[^0-9]/g, '');
            if (this.value !== cleaned && typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Input tidak valid', text: 'Field ini hanya boleh berisi angka.' });
            }
            this.value = cleaned;
        });
    });

});
