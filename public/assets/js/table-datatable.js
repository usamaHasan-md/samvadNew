$(function() {
	"use strict";

    // $(document).ready(function() {
    //     $('#example').DataTable();
    //   } );


      $(document).ready(function() {
        var table = $('#example2').DataTable( {
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'pdf', 'print']
        } );
     
        table.buttons().container()
            .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
    } );

    $(document).ready(function() {
        $('#example').DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 10, // Display 10 records initially
            "drawCallback": function(settings) {
                var api = this.api();
                var start = api.context[0]._iDisplayStart;
                var recordsTotal = api.page.info().recordsTotal;
                var currentPage = api.page.info().page;
                var rows = api.rows({ page: 'current' }).nodes();

                var serialNumber = start + 1;
                $(rows).each(function(index, row) {
                    $('td:eq(0)', row).html(serialNumber++);
                });
            }
        });
    });

});