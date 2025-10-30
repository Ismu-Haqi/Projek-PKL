{{-- ✅ Pastikan SweetAlert2 sudah di-load --}}
@if(!isset($sweetalertLoaded))
    @php $sweetalertLoaded = true; @endphp
@endif

<script>
    // ========================================
    // WAIT FOR DOM READY
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Swal is loaded
        if (typeof Swal === 'undefined') {
            console.error('❌ SweetAlert2 not loaded! Please include SweetAlert2 CDN.');
            return;
        }

        console.log('✅ SweetAlert2 loaded successfully');

        // ========================================
        // SUCCESS NOTIFICATION (FIXED - NO BACKDROP)
        // ========================================
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#d4edda',
                color: '#155724',
                iconColor: '#28a745',
                backdrop: false,
                customClass: {
                    popup: 'colored-toast',
                    container: 'no-backdrop-toast'
                },
                didOpen: (toast) => {
                    const container = toast.closest('.swal2-container');
                    if (container) {
                        container.style.background = 'transparent';
                        container.style.backdropFilter = 'none';
                    }
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
    });

    // ========================================
    // FUNCTION: CONFIRM DELETE (PRIMARY)
    // ========================================
    window.confirmDelete = function(button, message = 'Data akan dihapus permanen!') {
        if (typeof Swal === 'undefined') {
            if (confirm('Yakin ingin menghapus? ' + message)) {
                button.closest('form').submit();
            }
            return;
        }

        const form = button.closest('form');
        
        if (!form) {
            console.error('❌ Error: Form tidak ditemukan!');
            Swal.fire({
                icon: 'error',
                title: 'Error Sistem',
                text: 'Form tidak ditemukan. Silakan refresh halaman.',
                confirmButtonColor: '#dc2626'
            });
            return false;
        }

        console.log('✅ Form ditemukan:', form);
        console.log('📝 Action URL:', form.action);

        Swal.fire({
            title: '⚠️ Konfirmasi Hapus',
            html: `
                <div class="text-left">
                    <p class="text-gray-700 mb-2">${message}</p>
                    <p class="text-sm text-red-600 font-semibold">Data yang dihapus <strong>TIDAK DAPAT</strong> dikembalikan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus Data...',
                    html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-blue-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
                
                console.log('🚀 Submitting delete form...');
                form.submit();
            } else {
                console.log('❌ Delete cancelled by user');
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM LOGOUT
    // ========================================
    window.confirmLogout = function() {
        if (typeof Swal === 'undefined') {
            if (confirm('Keluar dari sistem?')) {
                document.getElementById('logoutForm').submit();
            }
            return;
        }

        Swal.fire({
            title: '🚪 Keluar dari Sistem?',
            text: 'Anda akan keluar dari GANDARIA',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i> Ya, Keluar',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging Out...',
                    html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-blue-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const logoutForm = document.getElementById('logoutForm');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    console.error('Logout form not found!');
                }
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM FAVORITE TOGGLE
    // ========================================
    window.confirmToggleFavorite = function(button, isFavorite = false, itemName = 'Arsip') {
        if (typeof Swal === 'undefined') {
            button.closest('form').submit();
            return;
        }

        const action = isFavorite ? 'menghapus dari' : 'menambahkan ke';
        const icon = isFavorite ? 'question' : 'question';
        const confirmColor = isFavorite ? '#dc2626' : '#eab308';
        
        Swal.fire({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Favorit?`,
            text: `${itemName} akan ${action} daftar favorit Anda`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: isFavorite ? '<i class="fas fa-trash mr-2"></i> Hapus dari Favorit' : '<i class="fas fa-star mr-2"></i> Tambahkan ke Favorit',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading toast
                Swal.fire({
                    icon: 'info',
                    title: 'Memproses...',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
                
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM VIEW (Opsional - dengan notifikasi)
    // ========================================
    window.confirmView = function(url, itemName = 'Arsip') {
        // Langsung redirect tanpa konfirmasi
        window.location.href = url;
    }

    // ========================================
    // FUNCTION: CONFIRM DOWNLOAD
    // ========================================
    window.confirmDownload = function(url, filename = 'Dokumen') {
        if (typeof Swal === 'undefined') {
            window.location.href = url;
            return;
        }

        Swal.fire({
            title: '📥 Download Dokumen?',
            text: `File "${filename}" akan diunduh`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-download mr-2"></i> Ya, Download',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Mengunduh...',
                    text: 'File sedang diunduh',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                
                window.location.href = url;
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM EDIT (Opsional)
    // ========================================
    window.confirmEdit = function(url, itemName = 'data') {
        // Langsung redirect ke halaman edit tanpa konfirmasi
        window.location.href = url;
    }

    // ========================================
    // FUNCTION: SHOW LOADING
    // ========================================
    window.showLoading = function(message = 'Memproses data...') {
        Swal.fire({
            title: message,
            html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-blue-500"></i></div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // ========================================
    // FUNCTION: CLOSE LOADING
    // ========================================
    window.closeLoading = function() {
        Swal.close();
    }

    // ========================================
    // FUNCTION: SHOW SUCCESS TOAST
    // ========================================
    window.showSuccessToast = function(message = 'Berhasil!') {
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            backdrop: false
        });
    }

    // ========================================
    // FUNCTION: SHOW ERROR TOAST
    // ========================================
    window.showErrorToast = function(message = 'Terjadi kesalahan!') {
        Swal.fire({
            icon: 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            backdrop: false
        });
    }

    // ========================================
    // FUNCTION: CONFIRM SEND DISPOSITION
    // ========================================
    window.confirmSendDisposition = function(button) {
        if (typeof Swal === 'undefined') {
            if (confirm('Kirim disposisi ke staff?')) {
                button.closest('form').submit();
            }
            return;
        }

        Swal.fire({
            title: '📨 Kirim Disposisi?',
            html: `
                <div class="text-left">
                    <p class="text-gray-700 mb-2">Disposisi akan dikirim ke staff yang ditunjuk</p>
                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                        <li>Penerima akan mendapat notifikasi</li>
                        <li>Nomor disposisi dibuat otomatis</li>
                        <li>Status awal: Menunggu</li>
                    </ul>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-paper-plane mr-2"></i> Ya, Kirim Disposisi',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim Disposisi...',
                    html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-purple-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
                button.closest('form').submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM UPDATE DISPOSITION STATUS (Staff)
    // ========================================
    window.confirmUpdateDisposition = function(button) {
        if (typeof Swal === 'undefined') {
            if (confirm('Update status disposisi?')) {
                button.closest('form').submit();
            }
            return;
        }

        const form = button.closest('form');
        const status = form.querySelector('select[name="status"]').value;
        const notes = form.querySelector('textarea[name="notes"]').value;
        
        let statusText = {
            'in_progress': 'Sedang Diproses',
            'completed': 'Selesai',
            'rejected': 'Ditolak'
        };

        Swal.fire({
            title: '✅ Update Status Disposisi?',
            html: `
                <div class="text-left">
                    <p class="text-gray-700 mb-3">Status akan diubah menjadi: <strong class="text-blue-600">${statusText[status] || status}</strong></p>
                    ${notes ? `<p class="text-sm text-gray-600 mb-2">Dengan catatan:</p><p class="text-sm italic text-gray-700 bg-gray-50 p-2 rounded">"${notes}"</p>` : ''}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check mr-2"></i> Ya, Update Status',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memperbarui Status...',
                    html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-green-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui...';
                form.submit();
            }
        });
    }

    // ========================================
    // FUNCTION: CONFIRM APPROVE
    // ========================================
    window.confirmApprove = function(button, message = 'Data akan disetujui') {
        Swal.fire({
            title: '✅ Setujui Data?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check mr-2"></i> Ya, Setujui',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
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
    window.confirmReject = function(button, message = 'Data akan ditolak') {
        Swal.fire({
            title: '❌ Tolak Data?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-times-circle mr-2"></i> Ya, Tolak',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
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
    window.confirmResetPassword = function(button) {
        Swal.fire({
            title: '🔑 Reset Password User?',
            text: 'Password akan direset dan user harus login dengan password baru',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#eab308',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-key mr-2"></i> Ya, Reset',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
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
    window.confirmToggleStatus = function(button, currentStatus) {
        const newStatus = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
        const statusText = currentStatus ? 'dinonaktifkan' : 'diaktifkan';
        
        Swal.fire({
            title: `${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)} User?`,
            text: `User akan ${statusText} dalam sistem`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#eab308' : '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `<i class="fas fa-check mr-2"></i> Ya, ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}`,
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Log when script is loaded
    console.log('✅ SweetAlert functions loaded');
</script>

<style>
    /* ========================================
       TOAST NOTIFICATION STYLING (FIXED)
       ======================================== */
    
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

    /* ✅ FIX: Hilangkan backdrop/bayangan untuk toast */
    .swal2-container.swal2-top-end,
    .swal2-container.swal2-top-right,
    .no-backdrop-toast {
        background: transparent !important;
        backdrop-filter: none !important;
        pointer-events: none !important;
    }

    /* ✅ FIX: Toast popup tanpa shadow panjang */
    .swal2-popup.swal2-toast,
    .colored-toast {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        border-radius: 0.75rem !important;
        pointer-events: auto !important;
    }

    /* Animation untuk popup */
    .animated-popup {
        animation: zoomIn 0.3s ease-out;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
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

    /* Custom button styling */
    .swal2-confirm {
        font-weight: 600 !important;
        padding: 10px 24px !important;
    }

    .swal2-cancel {
        font-weight: 600 !important;
        padding: 10px 24px !important;
    }
</style>