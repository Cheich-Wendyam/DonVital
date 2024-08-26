<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <!-- Tableau de bord -->
                <li>
                    <a href="javascript: void(0);">
                        <i class="la la-dashboard"></i>
                        <span> Tableau de bord </span>
                    </a>
                </li>

                <!-- Utilisateurs -->
                <li>
                    <a href="{{ route('utilisateurs') }}">
                        <i class="la la-user"></i>
                        <span> Utilisateurs </span>
                    </a>
                </li>

                <!-- Rôles -->
                <li>
                    <a href="{{ route('roles.index') }}">
                        <i class="la la-shield"></i>
                        <span> Rôles </span>
                    </a>
                </li>

                <!-- Permissions -->
                <li>
                    <a href="{{ route('permissions.index') }}">
                        <i class="la la-key"></i>
                        <span> Permissions </span>
                    </a>
                </li>

                <!-- Annonces -->
                <li>
                    <a href="{{ route('annonce.index') }}">
                        <i class="la la-bullhorn"></i>
                        <span> Annonces </span>
                    </a>
                </li>

                <!-- Centre de santé -->
                <li>
                    <a href="{{ route('centre_sante.index') }}">
                        <i class="la la-medkit"></i>
                        <span> Centre de santé </span>
                    </a>
                </li>

                <!-- Publicité -->
                <li>
                    <a href="{{route('pub.index')}}">
                        <i class="la la-eye"></i>
                        <span> Publicité </span>
                    </a>
                </li>

                <!-- Notifications -->
                <li>
                    <a href="javascript: void(0);">
                        <i class="la la-bell"></i>
                        <span> Notifications </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
