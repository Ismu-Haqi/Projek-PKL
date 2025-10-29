<script>
    // ========================================
    // SUCCESS NOTIFICATION
    // ========================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#d4edda',
            color: '#155724',
            iconColor: '#28a745',
            customClass: {
                popup: 'colored-toast'
            }
        });
    @endif

    // ========================================
    // ERROR NOTIFICATION
    // ========================================
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Mengerti',
            allowOutsideClick: false,
            @if(session('error_type') === 'password')
            footer: '<a href="{{ route('password.request') }}" style="color: #007bff; text-decoration: none;">Lupa password? Klik di sini untuk reset</a>'
            @endif
        });
    @endif

    // ========================================
    // PASSWORD ERROR NOTIFICATION (Khusus)
    // ========================================
    @if(session('password_error'))
        Swal.fire({
            icon: 'error',
            title: 'Password Salah!',
            text: '{{ session('password_error') }}',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Coba Lagi',
            allowOutsideClick: false,
            footer: '<a href="{{ route('password.request') }}" style="color: #007bff; text-decoration: none; font-weight: 500;"><i class="fas fa-key"></i> Lupa password? Reset di sini</a>'
        });
    @endif

    // ========================================
    // LOGIN ERROR NOTIFICATION
    // ========================================
    @if(session('login_error'))
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            html: '<p style="margin-bottom: 10px;">{{ session('login_error') }}</p>',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Coba Lagi',
            allowOutsideClick: false,
            footer: '<div style="text-align: center;"><a href="{{ route('password.request') }}" style="color: #007bff; text-decoration: none; font-weight: 500;"><i class="fas fa-key"></i> Lupa Password?</a><br><small style="color: #6c757d; margin-top: 5px; display: block;">Hubungi admin jika masalah berlanjut</small></div>'
        });
    @endif

    // ========================================
    // WARNING NOTIFICATION
    // ========================================
    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Baik',
        });
    @endif

    // ========================================
    // INFO NOTIFICATION
    // ========================================
    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{{ session('info') }}',
            confirmButtonColor: '#17a2b8',
            confirmButtonText: 'OK',
        });
    @endif

    // ========================================
    // VALIDATION ERRORS
    // ========================================
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: '<div style="text-align: left;"><ul style="padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Perbaiki',
            allowOutsideClick: false,
        });
    @endif

    // ========================================
    // FUNCTION: CONFIRM DELETE
    // ========================================
    function confirmDelete(button, message = 'Data yang dihapus tidak dapat dikembalikan!') {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form terdekat
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM LOGOUT
    // ========================================
    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari Sistem?',
            text: 'Anda akan keluar dari sistem GANDARIA',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Keluar',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Mengakhiri Sesi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit logout form
                document.getElementById('logoutForm').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM ACTION (GENERIC)
    // ========================================
    function confirmAction(button, title = 'Apakah Anda yakin?', message = '', confirmText = 'Ya, Lanjutkan') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM RESET PASSWORD
    // ========================================
    function confirmResetPassword(button) {
        Swal.fire({
            title: 'Reset Password User?',
            text: 'Password akan direset dan user harus login dengan password baru',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-key"></i> Ya, Reset',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM TOGGLE STATUS
    // ========================================
    function confirmToggleStatus(button, currentStatus) {
        const newStatus = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
        const statusText = currentStatus ? 'dinonaktifkan' : 'diaktifkan';
        
        Swal.fire({
            title: `${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)} User?`,
            text: `User akan ${statusText} dalam sistem`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#ffc107' : '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="fas fa-check"></i> Ya, ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}`,
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: SHOW LOADING
    // ========================================
    function showLoading(message = 'Memproses data...') {
        Swal.fire({
            title: message,
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // ========================================
    // FUNCTION: CLOSE LOADING
    // ========================================
    function closeLoading() {
        Swal.close();
    }

    // ========================================
    // FUNCTION: CONFIRM SEND DISPOSITION
    // ========================================
    function confirmSendDisposition(button) {
        Swal.fire({
            title: 'Kirim Disposisi?',
            text: 'Disposisi akan dikirim ke staff yang ditunjuk',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Mengirim disposisi...');
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM ARCHIVE FAVORITE
    // ========================================
    function confirmToggleFavorite(button, isFavorite) {
        const action = isFavorite ? 'menghapus dari' : 'menambahkan ke';
        const icon = isFavorite ? 'warning' : 'question';
        
        Swal.fire({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Favorit?`,
            text: `Arsip akan ${action} daftar favorit Anda`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: isFavorite ? '#dc3545' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isFavorite ? '<i class="fas fa-trash"></i> Hapus' : '<i class="fas fa-star"></i> Tambahkan',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM APPROVE
    // ========================================
    function confirmApprove(button, message = 'Data akan disetujui') {
        Swal.fire({
            title: 'Setujui Data?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya, Setujui',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM REJECT
    // ========================================
    function confirmReject(button, message = 'Data akan ditolak') {
        Swal.fire({
            title: 'Tolak Data?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-times-circle"></i> Ya, Tolak',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM DOWNLOAD
    // ========================================
    function confirmDownload(url, filename = 'dokumen') {
        Swal.fire({
            title: 'Download Dokumen?',
            text: `File ${filename} akan diunduh`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-download"></i> Ya, Download',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Mengunduh dokumen...');
                window.location.href = url;
                setTimeout(() => {
                    closeLoading();
                }, 2000);
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM RESTORE
    // ========================================
    function confirmRestore(button, message = 'Data akan dipulihkan') {
        Swal.fire({
            title: 'Pulihkan Data?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-undo"></i> Ya, Pulihkan',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM PERMANENT DELETE
    // ========================================
    function confirmPermanentDelete(button) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Data akan dihapus secara permanen dan tidak dapat dipulihkan!',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus Permanen!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>

<style>
    /* Custom styling for toast notifications */
    .colored-toast.swal2-icon-success {
        background-color: #d4edda !important;
        border-left: 4px solid #28a745 !important;
    }
    
    .colored-toast.swal2-icon-error {
        background-color: #f8d7da !important;
        border-left: 4px solid #dc3545 !important;
    }
    
    .colored-toast.swal2-icon-warning {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107 !important;
    }
    
    .colored-toast.swal2-icon-info {
        background-color: #d1ecf1 !important;
        border-left: 4px solid #17a2b8 !important;
    }

    /* Animation untuk toast */
    .swal2-toast {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>