$("#basic-datatable").DataTable({
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
        }
    },
    drawCallback: function() {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
    searching: true,  // Active la recherche globale
    // Vous pouvez également ajouter des filtres pour des colonnes spécifiques si nécessaire
});
