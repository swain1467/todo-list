$(document).ready(function(){
    // Data table for task management
    let dtblTask = $('#dtblTask').DataTable({
        lengthMenu: [
            [5, 20, 50, 100, 500, -1],
            [5, 20, 50, 100, 500, "ALL"],
        ],
        ajax: {
            "url": "api/task.php?action=getTaskDetails",
            "type": "GET",
            "data": {}
          },
        pageLength: 5,
        bProcessing: true,//server side pagination
        bServerSide: true,//server side pagination
        bStateSave: false,
        bPaginate: true,
        bLengthChange: true,
        bFilter: false,
        bSort: false,
        bInfo: true,
        bAutoWidth: false,
        bDestroy: true,
        "sDom": "<'row'<'col-lg-9 col-md-9 col-sm-9'B><'col-lg-3 col-md-3 col-sm-3'l><'col-lg-4 col-md-4 col-sm-4'f>>" +
                "<'row'<'col-lg-12 col-md-12 col-sm-12'tr>>" +
                "<'row'<'col-lg-9 col-md-9 col-sm-9'i><'col-lg-3 col-md-3 col-sm-3'p>>",
        "aoColumns": [
            { "data": 'task', "name": "task","sWidth": "90%"},
        	{
                "sName": "action",
                "data": null,"sWidth": "10%",
                "className": "text-center",
             	"defaultContent": "<button class='btn btn-warning btn-sm action-btn' onclick='updateTask(event)'><i class='fa fa-edit'></i></button>&nbsp;&nbsp;<button class='btn btn-danger btn-sm action-btn' onclick='deleteTask(event)'><i class='fa fa-trash'></i></button>"
            },
        ],
        buttons: [{
            text: '<button id="addTask" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;Add</button>',
            }
        ]
    });
    // Add button on click
    $("#addTask").click(function() {
        $("#modalTaskHeader").html('Add Task');
        $("#txtTaskId").val('');
        $("#txtHeader").val('');
        $("#txtContent").val('');
        $("#btnSaveTask").html('<i class="fa fa-save"></i>&nbsp;Save');
        $('#modalTask').modal('show');
      });

    $("#btnSaveTask").click(function() {
        $("#btnSaveTask").html('<i class="fa fa-gear fa-spin"></i>&nbsp;Please Wait...');
        $("#btnSaveTask").attr('disabled', true);

        let task_id = $("#txtTaskId").val();
        let task_header = $("#txtHeader").val();
        let task_content = $("#txtContent").val();

        let action = '';
        if(task_id){
            action = 'updateTask';
        }else{
            action = 'saveTask';
        }
        $.ajax({
            url: "api/task.php?action="+action,
            type: "POST",
            data: { task_id:task_id, task_header:task_header, task_content:task_content },
            success: function(response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 'Success'){
                    $("#btnSaveTask").html('<i class="fa fa-save"></i>&nbsp;Save');
                    $("#btnSaveTask").removeAttr('disabled');
                    $('#modalTask').modal('hide');
                    dtblTask.ajax.reload();
                    toastr.success(res.message);
                } else if(res.status == 'Error'){
                    $("#btnSaveTask").html('<i class="fa fa-save"></i>&nbsp;Save');
                    $("#btnSaveTask").removeAttr('disabled');
                    toastr.warning(res.message);
                } else{
                    $("#btnSaveTask").html('<i class="fa fa-save"></i>&nbsp;Save');
                    $("#btnSaveTask").removeAttr('disabled');
                    toastr.error(res.message);
                }
            },
            error: function(response) {
                toastr.error('Sorry! Something Went Wrong!!!');
            }
        }); 
    });
});
// Task update function
function updateTask(event) {
	var dtblTask = $('#dtblTask').dataTable();
	$(dtblTask.fnSettings().aoData).each(function () {
		$(this.nTr).removeClass('success');
	});
	var row;
	if (event.target.tagName == "BUTTON" || event.target.tagName == "A")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
	
    $("#modalTaskHeader").html('Edit Task');
    $("#btnSaveTask").html('<i class="fa fa-edit"></i>&nbsp;Update');
    $("#btnSaveTask").removeAttr('disabled');

    $("#txtTaskId").val(dtblTask.fnGetData(row)['id']);
    $("#txtHeader").val(dtblTask.fnGetData(row)['header']);
    $("#txtContent").val(dtblTask.fnGetData(row)['content']);
    $('#modalTask').modal('show');
}
// Task delete function
function deleteTask(event) {
	var dtblTask = $('#dtblTask').dataTable();
	$(dtblTask.fnSettings().aoData).each(function () {
		$(this.nTr).removeClass('success');
	});
	var row;
	if (event.target.tagName == "BUTTON" || event.target.tagName == "A")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
    swal({
		title: 'Are you sure to delete ?',
		text: "",
		type: 'info',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Yes',
		animation: true
	}).then(function() {
		$.ajax({
			url: "api/task.php?action=deleteTask",
			type: "POST",
			data: { id:dtblTask.fnGetData(row)['id'] },
			success: function(response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 'Success'){
                    let dtblTask = $("#dtblTask").DataTable();
                    dtblTask.ajax.reload();
                    toastr.success(res.message);
                } else{
                    toastr.error(res.message);
                }
			},
			error: function() {
				toastr.error('Unable to process Submit Operation');
			}
		});
	}, 
	function(dismiss) {}).done();
}

