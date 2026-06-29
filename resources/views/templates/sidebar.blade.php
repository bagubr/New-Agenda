<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="./index.html" class="brand-link"> <!--begin::Brand Image--> <img src="{{asset('/dist')}}/assets/img/logo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">{{config('app.name')}}</span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item"> <a href="{{route('dashboard')}}" class="nav-link {{Request::routeIs('dashboard')?'active':''}}"> <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{route('surat-masuk')}}" class="nav-link {{Request::routeIs('surat-masuk')?'active':''}}"> <i class="nav-icon bi bi-inbox-fill"></i>
                        <p>
                            Surat Masuk
                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{route('belum-disposisi')}}" class="nav-link {{Request::routeIs('belum-disposisi')?'active':''}}"> <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            Belum Disposisi
                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{route('surat-terlewat')}}" class="nav-link {{Request::routeIs('surat-terlewat')?'active':''}}"> <i class="nav-icon bi bi-exclamation-triangle-fill"></i>
                        <p>
                            Surat Terlewat
                        </p>
                    </a>
                </li>
                @if(auth()->user()->role !== 'user')
                <li class="nav-item"> <a href="{{route('surat-keluar')}}" class="nav-link {{Request::routeIs('surat-keluar')?'active':''}}"> <i class="nav-icon bi bi-send"></i>
                        <p>
                            Surat Keluar
                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{route('arsip-surat')}}" class="nav-link {{Request::routeIs('arsip-surat')?'active':''}}"> <i class="nav-icon bi bi-inboxes"></i>
                        <p>
                            Arsip Surat
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item"> <a href="#" class="nav-link"> <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            Buat Surat Baru
                        </p>
                    </a>
                </li> -->
                <li class="nav-item"> <a href="#" class="nav-link"> <i class="nav-icon bi bi-bar-chart"></i>
                        <p>
                            Laporan Bulanan / Tahunan
                        </p>
                    </a>
                </li>
                @if (Auth::user()->role === 'admin')
                    
                    <li class="nav-item {{Request::routeIs('asal', 'ruang-rapat')?'menu-open':''}}"> <a href="#" class="nav-link {{Request::routeIs('asal')?'active':''}}"> <i class="nav-icon bi bi-gear"></i>
                            <p>
                                Pengaturan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a href="{{route('asal')}}" class="nav-link {{Request::routeIs('asal')?'active':''}}"> <i class="nav-icon bi bi-circle"></i>
                                    <p>Asal</p>
                                </a> 
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a href="{{route('ruang-rapat')}}" class="nav-link {{Request::routeIs('ruang-rapat')?'active':''}}"> <i class="nav-icon bi bi-circle"></i>
                                    <p>Ruang Rapat</p>
                                </a> 
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a href="{{route('users.index')}}" class="nav-link {{Request::routeIs('users.*')?'active':''}}"> <i class="nav-icon bi bi-circle"></i>
                                    <p>Manajemen User</p>
                                </a> 
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a href="{{route('disposisi.index')}}" class="nav-link {{Request::routeIs('disposisi.*')?'active':''}}"> <i class="nav-icon bi bi-circle"></i>
                                    <p>Disposisi</p>
                                </a> 
                            </li>
                        </ul>
                    </li>
                @endif
                @endif
            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->