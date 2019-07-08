$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

    $('select').selectpicker();

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy'
    });

    var datatable_lng = {
        "sProcessing":     "Traitement en cours...",
        "search": "search",
        "searchPlaceholder": "Rechercher...",
        "sSearch":         "<i class='fa fa-search'></i>&nbsp;:",
        "sLengthMenu":     "_MENU_ &eacute;l&eacute;ments",
        "sInfo":           "de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_",
        "sInfoEmpty":      "de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
            "sFirst":      "<<",
            "sPrevious":   "<",
            "sNext":       ">",
            "sLast":       ">>"
        },
        "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        },
        "select": {
            "rows": {
                _: "%d lignes séléctionnées",
                0: "Aucune ligne séléctionnée",
                1: "1 ligne séléctionnée"
            }
        }
    };

    if ( $.fn.dataTable.isDataTable( '#item-table' ) )
    {
        table = $('#item-table').DataTable();
    }
    else
    {
        table = $('#item-table').DataTable({

            //"dom": 'Bfrtip',
            "pagingType": "full_numbers",
            //"scrollX": true ,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            "stateSave": true,

            "aoColumnDefs": [
                {bSortable: false, aTargets: [-1]} // Disable sorting on columns marked as so
            ],
            "buttons": [
                'pageLength'
            ],

            "language": datatable_lng
        });
    }

    if ( $.fn.dataTable.isDataTable( '#item-list' ) )
    {
        table = $('#item-list').DataTable();
    }
    else
    {
        table = $('#item-list').DataTable({

            //"dom": 'Bfrtip',
            "pagingType": "full_numbers",
            //"scrollX": true ,
            "lengthMenu": [[5,10], [5,10]],
            "ordering": false,
            "stateSave": true,

            "aoColumnDefs": [
                {bSortable: false, aTargets: [-1]} // Disable sorting on columns marked as so
            ],
            "buttons": [
                'pageLength'
            ],

            "language": datatable_lng
        });
    }

});