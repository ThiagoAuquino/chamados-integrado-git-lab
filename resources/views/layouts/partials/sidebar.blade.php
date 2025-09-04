{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Demandas -->
                <li class="nav-item {{ request()->routeIs('demandas.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('demandas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Demandas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('demandas.index') }}"
                                class="nav-link {{ request()->routeIs('demandas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listar Demandas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('demandas.kanban.index') }}"
                                class="nav-link {{ request()->routeIs('demandas.kanban.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kanban</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('demandas.create') }}"
                                class="nav-link {{ request()->routeIs('demandas.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nova Demanda</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Usuários -->
                <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuários
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listar Usuários</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}"
                                class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Novo Usuário</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>

        <!-- User panel NO FINAL da sidebar -->
        <div class="user-panel mt-auto pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->avatar ?? asset('images/user-default.png') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
        </div>

    </div>
</aside>
    