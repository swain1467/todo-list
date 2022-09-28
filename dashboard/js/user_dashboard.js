$(document).ready(function(){
    // Data table for task management
    var dtblTask = $('#dtblTask').DataTable({
        lengthMenu: [
            [10, 20, 50, 100, 500, -1],
            [10, 20, 50, 100, 500, "ALL"],
        ],
        pageLength: 10,
        bProcessing: false,
        bServerSide: false,
        bStateSave: false,
        bPaginate: true,
        bLengthChange: true,
        bFilter: true,
        bSort: false,
        bInfo: true,
        bAutoWidth: false,
        bDestroy: true,
        "sDom": "<'row'<'col-lg-4 col-md-4 col-sm-4'B><'col-lg-4 col-md-4 col-sm-4'l><'col-lg-4 col-md-4 col-sm-4'f>>" +
                "<'row'<'col-lg-12 col-md-12 col-sm-12'tr>>" +
                "<'row'<'col-lg-5 col-md-5 col-sm-5'i><'col-lg-5 col-md-5 col-sm-5'p>>",
        "aoColumns": [
            { "data": 'task', "name": "task","sWidth": "90%"},
        	{
                "sName": "action",
                "data": null,"sWidth": "10%",
                "className": "text-center",
             	"defaultContent": "<button class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></button>&nbsp;&nbsp;<button class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>"
            },
        ],
        buttons: [{
            text: '<button id="addTask" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;Add</button>',
            }
        ]
    });
    // Add button on click
    $("#addTask").click(function() {
        $("#modalTaskHeader").html('Add Task');
        $("#btnSaveTask").html('<i class="fa fa-save"></i>&nbsp;Save');
        $('#modalTask').modal('show');
      });

    $("#btnSaveTask").click(function() {
        $("#btnSaveTask").html('<i class="fa fa-gear fa-spin"></i>&nbsp;Please Wait...');
        // $("#BtnSaveCategory").attr('disabled', true);
    });
});

