<?php
$menu="hunter_activity";
include_once 'header.php';
?>
    <script>
$( function() {
            $( "#start_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_hunter_activity();

                }
            });
            $( "#end_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_hunter_activity();
                }
            });
        } );
</script>
    <section class="section">
      <div class="container">
            <div class="row">
            <?php
                if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==5 )) { ?>
                    <input type="hidden" class="form-control" id="select_user" name="select_user" value="<?php echo $_SESSION['user']['id']; ?>">
                <?php 
                } else if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==2 )) {
                ?>
                <div class="col-md-3">
                    <select class="form-control" id="select_user" name="select_user" onchange="show_hunter_activity()">
                        <option value="">Alege user</option>
                        <?php
                            $users = get_users_list();
                            if (count($users))
                                foreach ($users as $key=>$value)
                                    echo '<option value="'.$value['id'].'">'.$value['fname'].' '.$value['lname'].' - '.$value['email'].'</option>';
                        ?>
                    </select>
                </div>
                <?php } ?>
            
                <div class="col-md-3">
                    <b>Start:</b>
                    <input type="text" id="start_date" class="datepicker">
                </div>
                <div class="col-md-3">
                    <b>End:</b>
                    <input type="text" id="end_date" class="datepicker">
                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label for="select_nume_mobil">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-check-input" id="select_nume_mobil" name="select_nume_mobil" onclick="show_hunter_activity();" checked="true"> Operator telecom
                        </label>
                    </div>
                </div>
            </div>
        </div>   <br>
        <div id="hunter_activity_div" ></div>
        <div class="loader"></div>
    </section>
    <script>
    $(document).ready(function() {
        show_hunter_activity();

    });

    function show_hunter_activity(){
        $('#hunter_activity_div').html();
        $('#hunter_activity_div').html('<div class="demo-html"></div><table id="hunter_activity_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Data</th><th>Firma</th><th>Field</th><th>Editat de</th></tr></thead></table>');
        hunter_activity();


    }

    function hunter_activity() {

        $('.loader').show();
        
        call_url="include/datatables/hunter_activity.php?";

        user_id= $('#select_user').val();
        if (user_id != '')
            call_url=call_url+'&user_id='+user_id;

        start_date= $('#start_date').val();
        if (start_date != '')
            call_url=call_url+'&start_date='+start_date;

        end_date= $('#end_date').val();
        if (end_date != '')
            call_url=call_url+'&end_date='+end_date;

        
        if ($('#select_nume_mobil').length){
            if ($('#select_nume_mobil').prop("checked"))
                call_url=call_url+'&selected_field=nume_mobil';
        }

        var dt=$('#hunter_activity_table').DataTable( {
                dom: "Bfrtip",
                ajax: {
                    url: call_url,
                    type: 'POST'
                },
                serverSide: true,
                columns: [
                    { data: "editare_firma.date" },
                    { data: "firma.nume" },
                    { data: null, searchable:false,
                        render: function ( data, type, row ) {
                            return data.editare_firma_details.field+': din "'+data.editare_firma_details.old+'" in "'+data.editare_firma_details.new+'"';
                        }
                    },
                    { data: "users.email" }
                    //{ data: "contactare_firma.date" }
                ],
                columnDefs: [
                    { "searchable": false, "targets": 2 },
                    { "orderable": false, "targets": 2 }
                ],
                order: [ 0, 'desc' ],

            } );

            var detailRows = [];

            $('#hunter_activity_table tbody').on( 'click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );
                var current_id = tr.attr('id').substr(4);
                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    row.child( detaliu_firma( current_id ) ).show();

                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#row_'+id).trigger( 'click' );
                } );
            } );

            dt.on( 'processing.dt', function ( e, settings, processing ) {
                $('.loader').css( 'display', processing ? 'block' : 'none' );
            } ).dataTable();


    }
    </script>
<?php
include_once 'footer.php';
?>

