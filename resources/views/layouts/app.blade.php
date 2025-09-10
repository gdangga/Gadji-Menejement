<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Manajemen Gaji' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="h-full">
<div class="min-h-full">
    <nav class="bg-indigo-600">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-white font-bold">
                        Manajemen Gaji
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }} text-white hover:bg-indigo-500 rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                            <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'bg-indigo-700' : '' }} text-white hover:bg-indigo-500 rounded-md px-3 py-2 text-sm font-medium">Karyawan</a>
                            <a href="{{ route('penggajian.index') }}" class="{{ request()->routeIs('penggajian.*') ? 'bg-indigo-700' : '' }} text-white hover:bg-indigo-500 rounded-md px-3 py-2 text-sm font-medium">Penggajian</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <span class="text-white mr-4">Halo, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-indigo-200 hover:text-white">Logout</button>
                        </form>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button type="button" id="burger-btn" class="relative inline-flex items-center justify-center rounded-md bg-indigo-600 p-2 text-indigo-200 hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }} text-white block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
                <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'bg-indigo-700' : '' }} text-white block rounded-md px-3 py-2 text-base font-medium">Karyawan</a>
                <a href="{{ route('penggajian.index') }}" class="{{ request()->routeIs('penggajian.*') ? 'bg-indigo-700' : '' }} text-white block rounded-md px-3 py-2 text-base font-medium">Penggajian</a>
            </div>
            <div class="border-t border-indigo-700 pb-3 pt-4">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <span class="text-white">Halo, {{ Auth::user()->name }}</span>
                    </div>
                </div>
                <div class="mt-3 space-y-1 px-2">
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-indigo-200 hover:bg-indigo-500 hover:text-white">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <h1 class="text-lg font-semibold leading-6 text-gray-900">{{ $header }}</h1>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Script untuk Toggle Menu Burger
    document.addEventListener('DOMContentLoaded', function () {
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        burgerBtn.addEventListener('click', () => {
            const isExpanded = burgerBtn.getAttribute('aria-expanded') === 'true';
            burgerBtn.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
            burgerBtn.querySelectorAll('svg').forEach(svg => svg.classList.toggle('hidden'));
        });
    });

    // Notifikasi untuk session success
    @if (session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });
    @endif

    // Notifikasi untuk session error
    @if (session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
    @endif

    // Konfirmasi Hapus dengan SweetAlert
    document.body.addEventListener('submit', function (event) {
        const form = event.target;
        if (form.matches('.delete-form')) {
            event.preventDefault();
            const message = form.getAttribute('data-confirm-message') || 'Anda yakin ingin menghapus data ini?';
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>

</body>
</html>