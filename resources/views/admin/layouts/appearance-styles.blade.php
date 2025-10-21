{{-- Tambahkan ini di app.blade.php (layout utama) di bagian <head> --}}

<style>
/* ========================================
   APPEARANCE SETTINGS - DYNAMIC STYLES
   ======================================== */

/* Theme Colors */
:root {
    @if(isset($appearanceSettings['accent_color']))
        @if($appearanceSettings['accent_color'] === 'blue')
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --primary-rgb: 59, 130, 246;
        @elseif($appearanceSettings['accent_color'] === 'purple')
            --primary-color: #a855f7;
            --primary-dark: #9333ea;
            --primary-light: #c084fc;
            --primary-rgb: 168, 85, 247;
        @elseif($appearanceSettings['accent_color'] === 'green')
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --primary-rgb: 16, 185, 129;
        @elseif($appearanceSettings['accent_color'] === 'red')
            --primary-color: #ef4444;
            --primary-dark: #dc2626;
            --primary-light: #f87171;
            --primary-rgb: 239, 68, 68;
        @elseif($appearanceSettings['accent_color'] === 'orange')
            --primary-color: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fb923c;
            --primary-rgb: 249, 115, 22;
        @elseif($appearanceSettings['accent_color'] === 'indigo')
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --primary-rgb: 99, 102, 241;
        @endif
    @else
        --primary-color: #3b82f6;
        --primary-dark: #2563eb;
        --primary-light: #60a5fa;
        --primary-rgb: 59, 130, 246;
    @endif
}

/* Dark Theme */
@if(isset($appearanceSettings['theme']) && $appearanceSettings['theme'] === 'dark')
/* Body & Main Background */
body {
    background-color: #111827 !important;
    color: #f3f4f6 !important;
}

/* Cards & Containers */
.bg-white {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
}

.bg-gray-50 {
    background-color: #1a1f2e !important;
}

.bg-gray-100 {
    background-color: #232934 !important;
}

/* SIDEBAR - Dark Theme */
aside, .sidebar, nav.sidebar {
    background-color: #0f172a !important;
    border-right-color: #1e293b !important;
}

aside .bg-white,
.sidebar .bg-white {
    background-color: #1e293b !important;
}

/* Sidebar Links */
aside a, .sidebar a {
    color: #cbd5e1 !important;
}

aside a:hover, .sidebar a:hover {
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
}

aside a.active, .sidebar a.active {
    background-color: rgba(var(--primary-rgb), 0.2) !important;
    color: var(--primary-light) !important;
}

/* HEADER/NAVBAR - Dark Theme */
header, .header, nav.header, .navbar {
    background-color: #0f172a !important;
    border-bottom-color: #1e293b !important;
    color: #f3f4f6 !important;
}

header .bg-white {
    background-color: #1e293b !important;
}

/* Header Text & Icons */
header, header *, 
.header, .header *,
.navbar, .navbar * {
    color: #cbd5e1 !important;
}

header a:hover, .header a:hover {
    color: var(--primary-light) !important;
}

/* Dropdown in Header */
header .dropdown-menu,
.header .dropdown-menu {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

header .dropdown-item:hover,
.header .dropdown-item:hover {
    background-color: #334155 !important;
}

/* Text Colors */
.text-gray-800, .text-gray-900 {
    color: #f3f4f6 !important;
}

.text-gray-600, .text-gray-700 {
    color: #cbd5e1 !important;
}

.text-gray-500 {
    color: #94a3b8 !important;
}

.text-gray-400 {
    color: #64748b !important;
}

/* Borders */
.border-gray-200, .border-gray-300 {
    border-color: #374151 !important;
}

/* Form Elements */
input, select, textarea {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
    border-color: #374151 !important;
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    background-color: #2d3748 !important;
}

input::placeholder, textarea::placeholder {
    color: #6b7280 !important;
}

/* Tables */
table {
    color: #f3f4f6 !important;
}

thead {
    background-color: #1a1f2e !important;
}

tbody tr {
    border-color: #374151 !important;
}

tbody tr:hover {
    background-color: #1f2937 !important;
}

/* Buttons Keep Their Colors (except text) */
.btn, button {
    color: white !important;
}

/* Cards & Shadows */
.shadow, .shadow-sm, .shadow-md, .shadow-lg {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2) !important;
}

/* Logo & Brand */
.logo, .brand {
    filter: brightness(1.2);
}
@endif

/* Auto Theme - Respect System Preference */
@if(isset($appearanceSettings['theme']) && $appearanceSettings['theme'] === 'auto')
@media (prefers-color-scheme: dark) {
    body {
        background-color: #111827 !important;
        color: #f3f4f6 !important;
    }
    
    .bg-white {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
    }
    
    /* Sidebar Dark */
    aside, .sidebar {
        background-color: #0f172a !important;
    }
    
    aside a {
        color: #cbd5e1 !important;
    }
    
    /* Header Dark */
    header, .header {
        background-color: #0f172a !important;
        color: #f3f4f6 !important;
    }
    
    input, select, textarea {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }
}
@endif

/* Accent Color Application */
.bg-blue-500, .bg-blue-600, 
.bg-indigo-500, .bg-indigo-600 {
    background-color: var(--primary-color) !important;
}

.bg-blue-700, .bg-indigo-700 {
    background-color: var(--primary-dark) !important;
}

.from-blue-500, .from-blue-600,
.from-indigo-500, .from-indigo-600 {
    --tw-gradient-from: var(--primary-color) !important;
}

.to-blue-600, .to-blue-700,
.to-indigo-600, .to-indigo-700 {
    --tw-gradient-to: var(--primary-dark) !important;
}

.text-blue-500, .text-blue-600,
.text-indigo-500, .text-indigo-600 {
    color: var(--primary-color) !important;
}

.border-blue-500, .border-indigo-500 {
    border-color: var(--primary-color) !important;
}

.hover\:bg-blue-600:hover,
.hover\:bg-indigo-600:hover {
    background-color: var(--primary-dark) !important;
}

.hover\:bg-blue-700:hover,
.hover\:bg-indigo-700:hover {
    background-color: var(--primary-dark) !important;
}

.ring-blue-500, .ring-indigo-500 {
    --tw-ring-color: var(--primary-color) !important;
}

.focus\:ring-blue-500:focus,
.focus\:ring-indigo-500:focus {
    --tw-ring-color: var(--primary-color) !important;
}

/* Compact Mode */
@if(isset($appearanceSettings['compact_mode']) && $appearanceSettings['compact_mode'] === '1')
.p-6 {
    padding: 1rem !important;
}

.p-4 {
    padding: 0.75rem !important;
}

.py-4 {
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
}

.px-6 {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
}

.mb-6 {
    margin-bottom: 1rem !important;
}

.space-y-6 > * + * {
    margin-top: 1rem !important;
}

h1 {
    font-size: 1.5rem !important;
}

h2 {
    font-size: 1.25rem !important;
}

h3 {
    font-size: 1.125rem !important;
}
@endif

/* Font Size - Enhanced */
@if(isset($appearanceSettings['text_size']))
    @if($appearanceSettings['text_size'] === 'xs')
        body {
            font-size: 12px !important;
        }
        h1 { font-size: 1.5rem !important; }
        h2 { font-size: 1.25rem !important; }
        h3 { font-size: 1.125rem !important; }
        .text-sm { font-size: 11px !important; }
        .text-xs { font-size: 10px !important; }
    @elseif($appearanceSettings['text_size'] === 'sm')
        body {
            font-size: 13px !important;
        }
        h1 { font-size: 1.75rem !important; }
        h2 { font-size: 1.5rem !important; }
        h3 { font-size: 1.25rem !important; }
    @elseif($appearanceSettings['text_size'] === 'lg')
        body {
            font-size: 16px !important;
        }
        h1 { font-size: 2.25rem !important; }
        h2 { font-size: 1.875rem !important; }
        h3 { font-size: 1.5rem !important; }
    @elseif($appearanceSettings['text_size'] === 'xl')
        body {
            font-size: 18px !important;
        }
        h1 { font-size: 2.5rem !important; }
        h2 { font-size: 2rem !important; }
        h3 { font-size: 1.75rem !important; }
        .text-sm { font-size: 16px !important; }
    @endif
@endif

/* Smooth Scrolling */
@if(isset($appearanceSettings['smooth_scrolling']) && $appearanceSettings['smooth_scrolling'] === '1')
html {
    scroll-behavior: smooth;
}
@endif

/* Animation Speed */
@if(isset($appearanceSettings['animation_speed']))
    @if($appearanceSettings['animation_speed'] === 'slow')
        * {
            transition-duration: 0.5s !important;
        }
    @elseif($appearanceSettings['animation_speed'] === 'fast')
        * {
            transition-duration: 0.1s !important;
        }
    @elseif($appearanceSettings['animation_speed'] === 'none')
        *, *::before, *::after {
            animation-duration: 0s !important;
            transition-duration: 0s !important;
        }
    @endif
@endif
</style>

<script>
// Apply theme immediately to prevent flash
(function() {
    const theme = '{{ $appearanceSettings['theme'] ?? 'light' }}';
    
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
    } else if (theme === 'auto') {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        }
    }
})();
</script>