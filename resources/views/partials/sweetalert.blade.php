{{-- ✅ SweetAlert2 Configuration - FINAL FIX VERSION --}}
{{-- 🎯 Fixed: No Stuck, Visible Spinner, Auto-Hide Toast --}}
@if(!isset($sweetalertLoaded))
    @php $sweetalertLoaded = true; @endphp
@endif

<script>
    // ========================================
    // GLOBAL SWAL STATE MANAGER
    // ========================================
    window.SwalState = {
        isProcessing: false,
        
        reset: function() {
            this.isProcessing = false;
            if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                Swal.close();
            }
        },
        
        forceClose: function() {
            this.isProcessing = false;
            if (typeof Swal !== 'undefined') {
                try {
                    Swal.close();
                } catch(e) {
                    console.log('Force close error:', e);
                }
            }
        }
    };

    // ========================================
    // WAIT FOR DOM READY
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal === 'undefined') {
            console.error('❌ SweetAlert2 tidak dimuat!');
            return;
        }

        console.log('✅ SweetAlert2 berhasil dimuat');

        // ========================================
        // ✅ SUCCESS TOAST (AUTO HIDE)
        // ========================================
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#d4edda',
                color: '#155724',
                iconColor: '#28a745',
                backdrop: false,
                customClass: {
                    popup: 'colored-toast success-toast'
                },
                didOpen: (toast) => {
                    // Pause on hover
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
                willClose: () => {
                    console.log('✅ Toast closed');
                }
            });
        @endif

        // ========================================
        // ERROR NOTIFICATION
        // ========================================
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Tutup',
                allowOutsideClick: true,
                allowEscapeKey: true
            });
        @endif

        // ========================================
        // ✅ PASSWORD ERROR (SPECIAL)
        // ========================================
        @if(session('password_error'))
            Swal.fire({
                icon: 'error',
                title: 'Password Salah',
                html: `
                    <div class="text-center">
                        <p class="text-gray-700 mb-3">{{ session('password_error') }}</p>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded mt-3">
                            <p class="text-sm text-yellow-700">
                                <i class="fas fa-lightbulb mr-2"></i>Pastikan Caps Lock tidak aktif
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Coba Lagi',
                footer: '<a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm"><i class="fas fa-key mr-1"></i> Lupa Password?</a>',
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'animated-popup error-shake'
                }
            });
        @endif

        // ========================================
        // LOGIN ERROR
        // ========================================
        @if(session('login_error'))
            Swal.fire({
                icon: 'warning',
                title: 'Login Gagal',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">{{ session('login_error') }}</p>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded mt-3">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-2"></i>Periksa kembali email/username dan role yang Anda pilih
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'Mengerti',
                allowOutsideClick: true,
                allowEscapeKey: true
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'Mengerti',
                allowOutsideClick: true
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                confirmButtonColor: '#17a2b8',
                confirmButtonText: 'Baik',
                allowOutsideClick: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<div style="text-align: left;"><ul style="padding-left: 20px; margin: 0;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Perbaiki',
                allowOutsideClick: true
            });
        @endif
    });

    // ========================================
    // ✅ FUNCTION: CONFIRM DELETE (NO STUCK VERSION)
    // ========================================
    window.confirmDelete = function(button, message = 'Data akan dihapus secara permanen') {
        // Prevent spam clicks
        if (window.SwalState.isProcessing) {
            console.log('⏳ Masih memproses...');
            return false;
        }

        const form = button?.closest('form');
        
        if (!form) {
            console.error('❌ Form tidak ditemukan');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Form tidak ditemukan. Silakan muat ulang halaman.',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Tutup'
                });
            }
            return false;
        }

        if (typeof Swal === 'undefined') {
            if (confirm('Yakin ingin menghapus?\n\n' + message)) {
                form.submit();
            }
            return false;
        }

        // ✅ Force close any existing modal
        window.SwalState.forceClose();

        // Small delay to ensure previous modal is closed
        setTimeout(() => {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">${message}</p>
                        <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                            <p class="text-sm text-red-700 font-semibold">
                                ⚠️ Peringatan: Data yang dihapus tidak dapat dikembalikan!
                            </p>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Hapus',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                allowOutsideClick: true,
                allowEscapeKey: true,
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'animated-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set processing state
                    window.SwalState.isProcessing = true;
                    
                    // ✅ SHOW LOADING WITH SMOOTH TRANSITION
                    Swal.fire({
                        title: 'Sedang Memproses',
                        html: `
                            <div class="loading-smooth-container">
                                <div class="spinner-wrapper">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                                <p class="loading-text">Menghapus data...</p>
                                <div class="loading-progress">
                                    <div class="loading-progress-bar"></div>
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'loading-popup-smooth'
                        },
                        didOpen: () => {
                            // Start progress animation
                            const progressBar = document.querySelector('.loading-progress-bar');
                            if (progressBar) {
                                setTimeout(() => {
                                    progressBar.style.width = '100%';
                                }, 100);
                            }
                        }
                    });
                    
                    // Disable button with smooth transition
                    button.disabled = true;
                    button.style.transition = 'all 0.3s ease';
                    button.style.opacity = '0.5';
                    button.style.transform = 'scale(0.95)';
                    
                    // Submit form with proper delay for smooth UX
                    setTimeout(() => {
                        console.log('🚀 Submitting form...');
                        form.submit();
                    }, 1200);
                    
                } else {
                    // ✅ EXPLICIT CLOSE - NO STUCK
                    console.log('❌ Dibatalkan');
                    window.SwalState.forceClose();
                }
            }).catch((error) => {
                console.error('Error:', error);
                window.SwalState.forceClose();
            });
        }, 100);
        
        return false;
    };

    // ========================================
    // ✅ FUNCTION: CONFIRM LOGOUT (NO STUCK VERSION)
    // ========================================
    window.confirmLogout = function() {
        if (window.SwalState.isProcessing) return false;

        if (typeof Swal === 'undefined') {
            if (confirm('Keluar dari sistem?')) {
                document.getElementById('logoutForm')?.submit();
            }
            return false;
        }

        window.SwalState.forceClose();

        setTimeout(() => {
            Swal.fire({
                title: 'Keluar dari Sistem',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Ya, Keluar',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                allowOutsideClick: true,
                allowEscapeKey: true,
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'animated-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.SwalState.isProcessing = true;
                    
                    // ✅ SHOW LOADING WITH SMOOTH TRANSITION
                    Swal.fire({
                        title: 'Sedang Keluar',
                        html: `
                            <div class="loading-smooth-container">
                                <div class="spinner-wrapper">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                                <p class="loading-text">Mengeluarkan Anda...</p>
                                <div class="loading-progress">
                                    <div class="loading-progress-bar"></div>
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'loading-popup-smooth'
                        },
                        didOpen: () => {
                            // Start progress animation
                            const progressBar = document.querySelector('.loading-progress-bar');
                            if (progressBar) {
                                setTimeout(() => {
                                    progressBar.style.width = '100%';
                                }, 100);
                            }
                        }
                    });
                    
                    setTimeout(() => {
                        document.getElementById('logoutForm')?.submit();
                    }, 1200);
                } else {
                    console.log('❌ Logout dibatalkan');
                    window.SwalState.forceClose();
                }
            }).catch(() => {
                window.SwalState.forceClose();
            });
        }, 100);
        
        return false;
    };

    // ========================================
    // ✅ FUNCTION: CONFIRM TOGGLE FAVORIT (NO STUCK VERSION)
    // ========================================
    window.confirmToggleFavorite = function(button, isFavorite, title = 'arsip ini') {
        if (window.SwalState.isProcessing) return false;

        const form = button?.closest('form');
        if (!form) {
            console.error('❌ Form tidak ditemukan');
            return false;
        }

        if (typeof Swal === 'undefined') {
            form.submit();
            return false;
        }

        window.SwalState.forceClose();

        setTimeout(() => {
            Swal.fire({
                title: isFavorite ? 'Hapus dari Favorit?' : 'Tambahkan ke Favorit?',
                text: title,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isFavorite ? '#dc2626' : '#eab308',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isFavorite ? 'Ya, Hapus' : 'Ya, Tambahkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'animated-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.SwalState.isProcessing = true;

                    Swal.fire({
                        title: 'Sedang Memproses',
                        html: `
                            <div class="loading-smooth-container">
                                <div class="spinner-wrapper">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                                <p class="loading-text">Mohon tunggu...</p>
                                <div class="loading-progress">
                                    <div class="loading-progress-bar"></div>
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'loading-popup-smooth'
                        },
                        didOpen: () => {
                            const progressBar = document.querySelector('.loading-progress-bar');
                            if (progressBar) {
                                setTimeout(() => { progressBar.style.width = '100%'; }, 100);
                            }
                        }
                    });

                    button.disabled = true;

                    setTimeout(() => {
                        form.submit();
                    }, 800);
                } else {
                    window.SwalState.forceClose();
                }
            }).catch(() => {
                window.SwalState.forceClose();
            });
        }, 100);

        return false;
    };

    // ========================================
    // ✅ FUNCTION: CONFIRM DOWNLOAD (SATU LOADING SAJA)
    // ========================================
    window.confirmDownload = function(downloadUrl, fileName = 'file') {
        if (window.SwalState.isProcessing) return false;

        if (typeof Swal === 'undefined') {
            window.location.href = downloadUrl;
            return false;
        }

        window.SwalState.forceClose();

        setTimeout(() => {
            Swal.fire({
                title: 'Download File?',
                text: fileName,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Download',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'animated-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.SwalState.isProcessing = true;

                    // Satu loading singkat, lalu langsung tutup setelah file mulai diunduh
                    Swal.fire({
                        title: 'Menyiapkan File...',
                        html: `
                            <div class="loading-smooth-container">
                                <div class="spinner-wrapper">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                                <p class="loading-text">Mohon tunggu...</p>
                                <div class="loading-progress">
                                    <div class="loading-progress-bar"></div>
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'loading-popup-smooth'
                        },
                        didOpen: () => {
                            const progressBar = document.querySelector('.loading-progress-bar');
                            if (progressBar) {
                                setTimeout(() => { progressBar.style.width = '100%'; }, 100);
                            }
                        }
                    });

                    window.location.href = downloadUrl;

                    setTimeout(() => {
                        window.SwalState.forceClose();
                    }, 1000);
                } else {
                    window.SwalState.forceClose();
                }
            }).catch(() => {
                window.SwalState.forceClose();
            });
        }, 100);

        return false;
    };

    // ========================================
    // UTILITY FUNCTIONS
    // ========================================
    
    window.showLoading = function(message = 'Memproses') {
        if (typeof Swal === 'undefined') return;
        
        Swal.fire({
            title: message,
            html: `
                <div class="loading-smooth-container">
                    <div class="spinner-wrapper">
                        <i class="fas fa-spinner fa-pulse"></i>
                    </div>
                    <p class="loading-text">Mohon tunggu...</p>
                    <div class="loading-progress">
                        <div class="loading-progress-bar"></div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'loading-popup-smooth'
            },
            didOpen: () => {
                const progressBar = document.querySelector('.loading-progress-bar');
                if (progressBar) {
                    setTimeout(() => {
                        progressBar.style.width = '100%';
                    }, 100);
                }
            }
        });
    };

    window.closeLoading = function() {
        window.SwalState.forceClose();
    };

    window.showSuccessToast = function(message = 'Berhasil') {
        if (typeof Swal === 'undefined') return;
        
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            backdrop: false,
            customClass: {
                popup: 'colored-toast success-toast'
            }
        });
    };

    window.showErrorToast = function(message = 'Terjadi kesalahan') {
        if (typeof Swal === 'undefined') return;
        
        Swal.fire({
            icon: 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            backdrop: false,
            customClass: {
                popup: 'colored-toast error-toast'
            }
        });
    };

    // ========================================
    // ✅ GLOBAL HANDLERS (ANTI-STUCK)
    // ========================================
    
    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !window.SwalState.isProcessing) {
            window.SwalState.forceClose();
        }
    });

    // Click outside handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('swal2-container') && 
            !window.SwalState.isProcessing &&
            typeof Swal !== 'undefined' && 
            Swal.isVisible()) {
            window.SwalState.forceClose();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        window.SwalState.forceClose();
    });

    console.log('✅ SweetAlert berhasil dimuat');
</script>

<style>
    /* ========================================
       SWEETALERT STYLING - FIXED VERSION
       ======================================== */
    
    /* ✅ Toast with Auto-Hide */
    .colored-toast {
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        animation: slideInRight 0.4s ease-out !important;
    }
    
    .colored-toast.success-toast {
        background-color: #d4edda !important;
        border-left: 5px solid #28a745 !important;
    }
    
    .colored-toast.error-toast {
        background-color: #f8d7da !important;
        border-left: 5px solid #dc3545 !important;
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

    /* Toast Container */
    .swal2-container.swal2-top-end,
    .swal2-container.swal2-top-right {
        background: transparent !important;
        backdrop-filter: none !important;
        pointer-events: none !important;
    }

    .swal2-container.swal2-top-end .swal2-popup,
    .swal2-container.swal2-top-right .swal2-popup {
        pointer-events: auto !important;
    }

    /* ✅ Popup Animation */
    .animated-popup {
        animation: swalZoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    @keyframes swalZoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* ✅ Error Shake */
    .error-shake {
        animation: shake 0.5s !important;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
        20%, 40%, 60%, 80% { transform: translateX(8px); }
    }

    /* ✅ Loading Spinner - SMOOTH & VISIBLE */
    .loading-smooth-container {
        text-align: center;
        padding: 30px 20px;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .spinner-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin-bottom: 30px;
    }

    .spinner-wrapper::before {
        content: '';
        position: absolute;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #e0e7ff;
        animation: pulse-ring 1.5s ease-out infinite;
    }

    .spinner-wrapper i {
        font-size: 48px !important;
        color: #3b82f6 !important;
        animation: spinPulse 1.2s ease-in-out infinite !important;
        display: block;
        position: relative;
        z-index: 1;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }
        100% {
            transform: scale(1.4);
            opacity: 0;
        }
    }

    @keyframes spinPulse {
        0% {
            transform: rotate(0deg) scale(1);
        }
        50% {
            transform: rotate(180deg) scale(1.15);
        }
        100% {
            transform: rotate(360deg) scale(1);
        }
    }

    .loading-text {
        color: #64748b !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        margin: 0 0 20px 0 !important;
        animation: fadeInOut 1.5s ease-in-out infinite;
    }

    @keyframes fadeInOut {
        0%, 100% {
            opacity: 0.6;
        }
        50% {
            opacity: 1;
        }
    }

    /* ✅ Progress Bar */
    .loading-progress {
        width: 100%;
        max-width: 300px;
        height: 4px;
        background: #e0e7ff;
        border-radius: 2px;
        overflow: hidden;
        position: relative;
    }

    .loading-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
        background-size: 200% 100%;
        border-radius: 2px;
        width: 0%;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Loading Popup Styling */
    .loading-popup-smooth {
        animation: loadingPopupIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    @keyframes loadingPopupIn {
        from {
            transform: scale(0.8) translateY(-20px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .loading-popup-smooth .swal2-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        margin-bottom: 0 !important;
    }

    /* Container & Backdrop */
    .swal2-container {
        z-index: 99999 !important;
        pointer-events: auto !important;
    }

    .swal2-backdrop-show {
        background: rgba(0, 0, 0, 0.45) !important;
    }

    .swal2-popup {
        pointer-events: auto !important;
        border-radius: 16px !important;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2) !important;
    }

    /* ✅ Buttons - Always Clickable */
    .swal2-actions {
        gap: 12px !important;
    }

    .swal2-actions button {
        pointer-events: auto !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        font-weight: 600 !important;
        padding: 12px 28px !important;
        border-radius: 8px !important;
        font-size: 15px !important;
        border: none !important;
        min-width: 120px !important;
    }

    .swal2-confirm {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .swal2-cancel {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .swal2-confirm:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
    }

    .swal2-cancel:hover {
        transform: translateY(-2px) !important;
        opacity: 0.9 !important;
    }

    .swal2-confirm:active,
    .swal2-cancel:active {
        transform: translateY(0) !important;
    }

    /* Icon */
    .swal2-icon {
        border-width: 3px !important;
    }

    /* Title */
    .swal2-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
    }

    /* Content */
    .swal2-html-container {
        font-size: 1rem !important;
        line-height: 1.6 !important;
        color: #475569 !important;
    }

    /* Footer */
    .swal2-footer {
        border-top: 1px solid #e2e8f0 !important;
        padding: 1rem !important;
    }

    /* Scrollbar */
    .swal2-html-container::-webkit-scrollbar {
        width: 6px;
    }

    .swal2-html-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .swal2-popup {
            width: 90% !important;
            padding: 1.5rem !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
        }

        .swal2-actions button {
            padding: 10px 20px !important;
            font-size: 14px !important;
            min-width: 100px !important;
        }

        /* Mobile Loading */
        .loading-smooth-container {
            padding: 20px 15px;
            min-height: 180px;
        }

        .spinner-wrapper {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
        }

        .spinner-wrapper::before {
            width: 60px;
            height: 60px;
        }

        .spinner-wrapper i {
            font-size: 36px !important;
        }

        .loading-text {
            font-size: 14px !important;
            margin-bottom: 15px !important;
        }

        .loading-progress {
            max-width: 250px;
            height: 3px;
        }
    }

    /* ✅ Force Close Animation */
    .swal2-hide {
        animation: swalHide 0.2s !important;
    }

    @keyframes swalHide {
        to {
            opacity: 0;
            transform: scale(0.9);
        }
    }
</style>