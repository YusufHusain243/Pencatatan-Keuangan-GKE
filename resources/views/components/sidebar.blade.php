<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Gereja Sinar Kasih</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link {{ $title === 'dashboard' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/user" class="nav-link {{ $title === 'user' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kode" class="nav-link {{ $title === 'kode' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Daftar Kode</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/sub-kode" class="nav-link {{ $title === 'sub_kode' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Daftar Sub Kode</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/sub-sub-kode" class="nav-link {{ $title === 'sub_sub_kode' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Daftar Sub Sub-Kode</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/penerimaan" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Catat Penerimaan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pengeluaran" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Catat Pengeluaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/akun-bank" class="nav-link {{ $title === 'akun_bank' ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Akun Bank</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Cetak Laporan</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
