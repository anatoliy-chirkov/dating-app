<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Users</div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover table-fw-widget" id="table">
                        <thead>
                        <tr>
                            <th style="width:40%;">User</th>
                            <th style="width:32%;">Activity</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/lib/datatables/datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="/assets/lib/datatables/datatables.net-bs4/js/dataTables.bootstrap4.js" type="text/javascript"></script>
<script src="/assets/lib/datatables/datatables.net-buttons/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="/assets/lib/datatables/jszip/jszip.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.fn.DataTable.ext.pager.numbers_length = 4;
        $.extend( true, $.fn.dataTable.defaults, {
            dom:
                "<'row be-datatable-body'<'col-sm-12'tr>>" +
                "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
        } );

        $("#table").dataTable({
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "ajax": "/users/search",
            "drawCallback": function(settings, json) {
                $('#table tbody td:nth-child(1)').addClass('user-avatar cell-detail user-info');
                $('#table tbody td:nth-child(2)').addClass('cell-detail');
                $('#table tbody td:nth-child(3)').addClass('text-right');
            }
        });
    });
</script>
