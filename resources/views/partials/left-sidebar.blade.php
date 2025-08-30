<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <!-- Tableau de bord -->
                <li>
                    <a href="{{ route('admin') }}">
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
                @can('gestion role')
                <!-- Rôles -->
                <li>
                    <a href="{{ route('roles.index') }}">
                        <i class="la la-shield"></i>
                        <span> Rôles </span>
                    </a>
                </li>
                @endcan

                <!-- Permissions -->
                @can('gestion permission')
                <li>
                    <a href="{{ route('permissions.index') }}">
                        <i class="la la-key"></i>
                        <span> Permissions </span>
                    </a>
                </li>
                @endcan


                 <!-- Annonces with Submenu -->
                 <li>
                    <a href="javascript: void(0);" aria-expanded="false">
                        <i class="la la-bullhorn"></i>
                        <span> Annonces </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('annonce.index') }}">
                                <i class="la la-list"></i>
                                <span>Toutes les annonces</span></a>
                        </li>
                        <li>
                            <a href="{{ route('annonce.attente') }}">
                                <i class="la la-lock"></i>
                                <span>Annonces en attente</span></a>
                        </li>
                        <li>
                            <a href="{{ route('annonce.fermees') }}">
                                <i class="la la-ban"></i>
                                <span>Annonces fermées</span></a>
                        </li>
                    </ul>
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

                <!-- NOUVEAU MENU CAMPAGNES -->
                <!-- Campagnes avec sous-menu -->
                <li>
                    <a href="javascript: void(0);" aria-expanded="false">
                        <i class="la la-calendar-check-o"></i>
                        <span> Campagnes </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('campagnes.index') }}">
                                <i class="la la-list"></i>
                                <span>Toutes les campagnes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('campagnes.participants.all') }}">
                                <i class="la la-users"></i>
                                <span>Participants</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- FIN DU MENU CAMPAGNES -->

                <li>
                    <a href="{{ route('rewards.index') }}">
                        <i class="la la-trophy"></i>
                        <span> Récompenses </span>
                    </a>
                </li>
                <!-- Conversations -->
                <li>
                    <a href="{{ route('admin.conversations.index') }}">
                        <i class="la la-comments"></i>
                        <span> Conversations </span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/dons') ? 'active' : '' }}">
                    <a href="{{ route('admin.dons.index') }}">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Dons</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.donation-records.index') }}">
                        <i class="la la-comments"></i>
                        <span>Carnets</span>
                    </a>
                </li>



                                <li>
                    <a href="javascript: void(0);" aria-expanded="false">
                        <i class="fas fa-graduation-cap"></i>
                        <span> Éducation </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('education.dashboard') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('education.index') }}">
                                <i class="fas fa-book"></i>
                                <span>Contenus éducatifs</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('education.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                <span>Créer un contenu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('education.statistics') }}">
                                <i class="fas fa-chart-pie"></i>
                                <span>Statistiques</span>
                            </a>
                        </li>
                    </ul>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
