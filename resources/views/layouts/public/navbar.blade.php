<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-5 ps-5">
            <a href="/" style="color: #cc4a4a; font-size: 26px; font-weight: bold;">
                <img src="{{ asset('image/nabdLogo.png') }}" width="35" alt="" />
                <span class="ms-2" style="font-family: 'Tajawal', sans-serif; font-style: italic;">NABD</span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">ACCUEIL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Tableau de bord</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">URGENCES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('urgences*') ? 'active' : '' }}" href="{{ route('urgences') }}" aria-expanded="false">
                        <span><i class="ti ti-alert-triangle"></i></span>
                        <span class="hide-menu">Urgences</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('demandes*') ? 'active' : '' }}" href="{{ route('demandes.liste') }}" aria-expanded="false">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Demandes</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">ACTIVITÉS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('compagnes*') ? 'active' : '' }}" href="{{ route('compagnes') }}" aria-expanded="false">
                        <span><i class="ti ti-building"></i></span>
                        <span class="hide-menu">Campagnes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('centres*') ? 'active' : '' }}" href="{{ route('centres') }}" aria-expanded="false">
                        <span><i class="ti ti-building"></i></span>
                        <span class="hide-menu">Centres</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ (request()->is('membres*') || request()->is('dons*')) ? 'active' : '' }}" href="{{ route('membres') }}" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Membres</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">ADMINISTRATION</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('statistiques*') ? 'active' : '' }}" href="./icon-tabler.html" aria-expanded="false">
                        <span><i class="ti ti-chart-arrows"></i></span>
                        <span class="hide-menu">Statistiques</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('historique*') ? 'active' : '' }}"  href="{{ route('historique') }}"aria-expanded="false">
                        <span><i class="ti ti-timeline"></i></span>
                        <span class="hide-menu">Historique</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('parametres*') ? 'active' : '' }}" href="./sample-page.html" aria-expanded="false">
                        <span><i class="ti ti-settings"></i></span>
                        <span class="hide-menu">Paramètres</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
