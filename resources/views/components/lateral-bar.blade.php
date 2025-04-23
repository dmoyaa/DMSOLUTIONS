<aside class="sidebar">
    <h2 class="sidebar-title">Bienvenido</h2>
    <nav>
        <ul class="menu">
            <li class="menu-item">
                <a href="{{route('home')}}">
                    <i class="fas fa-home menu-icon"></i>
                    <span class="menu-text">Inicio</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('browse')}}">
                    <i class="fas fa-search menu-icon"></i>
                    <span class="menu-text">Proyectos</span>
                </a>
            </li>
        </ul>
        <h3 class="sidebar-subtitle">Brochure</h3>
        <ul class="submenu">
            <li class="menu-item">
                <a href="{{route('quote')}}">
                    <i class="fas fa-file-alt menu-icon"></i>
                    <span class="menu-text">Cotizaciones</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('products')}}">
                    <i class="fas fa-tshirt menu-icon"></i>
                    <span class="menu-text">Productos</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('clients')}}">
                    <i class="fas fa-smile menu-icon"></i>
                    <span class="menu-text">Clientes</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('providers')}}">
                    <i class="fas fa-truck menu-icon"></i>
                    <span class="menu-text">Proveedores</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('dashboard')}}">
                    <i class="fas fa-smile menu-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            @php
                $user = session('user_id') ? DB::table('users')->where('id', session('user_id'))->first() : null;
            @endphp

            @if ($user && $user->user_role == '1')
                <li class="menu-item">
                    <a href="{{ route('administration') }}">
                        <i class="fas fa-pencil menu-icon"></i>
                        <span class="menu-text">Administración</span>
                    </a>
                </li>
            @endif
            <li class="menu-item">
                <a href="{{route('reminders')}}">
                    <i class="fas fa-book menu-icon"></i>
                    <span class="menu-text">Recordatorios</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="logout-section">
        <a href="{{route('logout')}}">
            <button class="logout-button" id="logout-button">
                <i class="fas fa-sign-out-alt logout-icon"></i>
                <span class="logout-text">Cerrar sesión</span>
            </button>
        </a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(!session('user_id'))
            window.location.href = "{{route("login")}}";
        @endif
    })
</script>
