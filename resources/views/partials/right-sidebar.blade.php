<!-- Barre latérale droite -->
<div class="right-bar">
    <div class="rightbar-title">
        <a href="javascript:void(0);" class="right-bar-toggle float-right">
            <i class="mdi mdi-close"></i>
        </a>
        <h5 class="m-0 text-white">Paramètres</h5>
    </div>
    <div class="slimscroll-menu">
        <!-- Boîte utilisateur -->
        <div class="user-box">
            <div class="user-img">
                <img src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : asset('images/users/default.png') }}" 
                     alt="user-img" 
                     title="{{ auth()->user()->name }}" 
                     class="rounded-circle img-fluid">
                <a href="javascript:void(0);" class="user-edit"><i class="mdi mdi-pencil"></i></a>
            </div>
            <h5><a href="javascript: void(0);">{{ auth()->user()->name }}</a></h5>
            <p class="text-muted mb-0"><small>{{ auth()->user()->getRoleNames()->first() }}</small></p>
        </div>

        <!-- Barre de recherche -->
        <hr class="mt-0" />
        <div class="p-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Rechercher...">
                <span class="mdi mdi-magnify search-icon"></span>
            </div>
        </div>

        <!-- Paramètres -->
        <hr class="mt-0" />
        <div class="p-3">
            <div class="custom-control custom-switch mb-2">
                <input type="checkbox" class="custom-control-input" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">Accès API</label>
            </div>
            <div class="custom-control custom-switch mb-2">
                <input type="checkbox" class="custom-control-input" id="customSwitch3" checked>
                <label class="custom-control-label" for="customSwitch3">Mises à jour automatiques</label>
            </div>
            <div class="custom-control custom-switch mb-2">
                <input type="checkbox" class="custom-control-input" id="customSwitch4" checked>
                <label class="custom-control-label" for="customSwitch4">Statut en ligne</label>
            </div>
        </div>
    </div> <!-- fin slimscroll-menu -->
</div>
<!-- /Barre latérale droite -->

<!-- Overlay de la barre droite -->
<div class="rightbar-overlay"></div>
