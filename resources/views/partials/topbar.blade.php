<style>
    .user-avatar {
    position: relative;
    display: inline-block;
}

.user-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background-color: #28a745; /* Couleur verte */
    border-radius: 50%;
    border: 2px solid #ffffff; /* Bordure blanche pour le contraste */
}

</style>
<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">
        <!-- Profil utilisateur -->
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <div class="user-avatar">
                    <!-- Vérification si l'utilisateur a une photo de profil -->
                    @if (auth()->user()->image)
                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="user-image" class="rounded-circle">
                    @else
                        <i class="fe-user noti-icon"></i> <!-- Icône utilisateur par défaut -->
                    @endif
                    <!-- Point vert pour indiquer la connexion -->
                    <span class="user-status"></span>
                </div>
                <span class="pro-user-name ml-1">
                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5 class="m-0 text-white">
                        Bienvenue !
                    </h5>
                </div>

                <!-- item-->
                <a href="{{ route('profile') }}" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>Mon Compte</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-settings"></i>
                    <span>Paramètres</span>
                </a>



                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fe-log-out"></i>
                    <span>Déconnexion</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

        <!-- Paramètres -->
        <li class="dropdown notification-list">
            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect">
                <i class="fe-settings noti-icon"></i>
            </a>
        </li>
    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="{{ route('admin') }}" class="logo text-center">
            <span class="logo-lg">
                <img src="{{ asset('images/Donvital.png') }}" alt="" height="140">
            </span>
            <span class="logo-sm">
                <img src="{{ asset('images/Donvital.png') }}" alt="" height="60">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </li>
    </ul>
</div>
<!-- end Topbar -->
