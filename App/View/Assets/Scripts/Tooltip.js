$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

    if($('select').length > 0 ){
        $('select').selectpicker();
    }

    if($('.datepicker').length > 0 ) {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
    }

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
                _: "%d lignes s&eacute;l&eacute;ctionn&eacute;es",
                0: "Aucune ligne s&eacute;l&eacute;ctionn&eacute;e",
                1: "1 ligne s&eacute;l&eacute;ctionn&eacute;e"
            }
        }
    };

    if($('#item-table').length > 0 ) {
        if ($.fn.dataTable.isDataTable('#item-table')) {
            table = $('#item-table').DataTable();
        } else {
            table = $('#item-table').DataTable({

                //"dom": 'Bfrtip',
                "pagingType": "full_numbers",
                //"scrollX": true ,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                "stateSave": true,

                "aoColumnDefs": [
                    {bSortable: false, aTargets: [-1]} // Disable sorting on columns marked as so
                ],
                "buttons": [
                    'pageLength'
                ],

                "language": datatable_lng,
                "initComplete": function( settings, json ) {
                    //$('div.loading').remove();
                    $('select[name="item-table_length"]').addClass( "dtbl-itm-length" );
                }
            });
        }

        $.fn.dataTable.ext.classes.sLengthSelect = 'dtbl-itm-length';
    }

    if($('#item-list').length > 0 ) {
        if ($.fn.dataTable.isDataTable('#item-list')) {
            table = $('#item-list').DataTable();
        } else {
            table = $('#item-list').DataTable({

                //"dom": 'Bfrtip',
                "pagingType": "full_numbers",
                //"scrollX": true ,
                "lengthMenu": [
                    [5, 10],
                    [5, 10]
                ],
                "ordering": false,
                "stateSave": true,

                "aoColumnDefs": [
                    {bSortable: false, aTargets: [-1]} // Disable sorting on columns marked as so
                ],
                "buttons": [
                    'pageLength'
                ],

                "language": datatable_lng,
                "initComplete": function( settings, json ) {
                    //$('div.loading').remove();
                    $('select[name="item-list_length"]').addClass( "dtbl-itm-length" );
                }
            });
        }
        $.fn.dataTable.ext.classes.sLengthSelect = 'dtbl-itm-length';
    }

    if($('form.item').length > 0 ) {

        $('form.item').contextmenu(function () {

            // Avoid the real one
            event.preventDefault();

            // Show contextmenu
            $(this).next(".right-click-menu")
                .addClass("active")
                // .finish()
                .toggle(100)
                .css({

                    top: $(this).pageY + "px",
                    left: $(this).pageX + "px"

                }) // In the right position (the mouse)
            ;

            return false;
        });

        /** If the document is clicked somewhere **/
        $(document).bind("mousedown", function (e) {

            // If the clicked element is not the menu
            if (!$(e.target).parents(".right-click-menu.active").length > 0) {

                // Hide it
                $(".right-click-menu.active").removeClass( "active" ).hide(100);
            }
        });

        // If the menu element is clicked
        $(".right-click-menu li").click(function () {

            suppr = confirm("êtes vous sûr ?");

            if(suppr) {
                // This is the triggered action name

                window.location.href = "?p=test."+$(this).data("action")+"&id="+ $(this).data("cible");
                return;

                /**switch ($(this).data("action")) {

                    // A case for each action. Your actions here
                    case "remove":
                        //alert("jeter " + $(this).data("cible"));
                        break;
                    case "second":
                        alert("second " + $(this).data("cible"));
                        break;
                    case "third":
                        alert("third " + $(this).data("cible"));
                        break;
                }**/
            }
            // Hide it AFTER the action was triggered
             $(this).hide(100);
        });
/**
        // Show menu when #myDiv is clicked
        $("form.item").contextMenu({
                menu: 'myMenu'
            },
            function(action, el, pos) {
                alert(
                    'Action: ' + action + '\n\n' +
                    'Element ID: ' + $(el).attr('id') + '\n\n' +
                    'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
                    'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
                );
            });
**/
    }
});