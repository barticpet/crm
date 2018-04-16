<?php
$menu="apeluri";
include_once 'header.php';
?>
    <script>
$( function() {
    $( "#contract_end_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+6m",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            show_apeluri_by_raspuns();
        }
    });   
    $( "#contract_start_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+6m",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            show_apeluri_by_raspuns();
        }
    }); 
    $( "#apel_start_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_apeluri_by_raspuns();

                }
            });
    $( "#apel_end_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_apeluri_by_raspuns();
                }
            });
        } );
</script>
    <section class="section">
      <div id="top_filters" class="container top_filters">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" id="select_raspuns" nme="select_raspuns" onchange="show_apeluri_by_raspuns();">
                        <option value='0'>Rezultat apel</option>
                        <?php
                            foreach ($config['rezultat_apel'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-md-4 ">
                    <div class="row text-center">
                        <div class="col-xs-2 text-right">&nbsp;&nbsp;&nbsp;&nbsp;Apeluri:&nbsp;&nbsp;&nbsp;</div>
                        <div class="col-xs-2 text-center">
                            <input type="text" id="apel_start_date" value="<?php echo date("Y-m-d", strtotime("last day")); ?>" class="datepicker small_date_input" readonly="readonly"  placeholder="Inceput">
                        </div>
                        <div class="col-xs-2 text-center">
                            <input type="text" id="apel_end_date" class="datepicker small_date_input" readonly="readonly" placeholder="Sfarsit">
                        </div>
                    </div>
                </div>
                <?php //if ($_SESSION['user']['user_role']==2) { ?>
                    <div class="col-md-4 ">
                       <div class="row text-center">
                           <div class="col-xs-2 text-right">&nbsp;&nbsp;&nbsp;&nbsp;Final ctr:&nbsp;</div>
                           <div class="col-xs-2 text-center">
                               <input type="text" id="contract_start_date" class="datepicker small_date_input" readonly="readonly"  placeholder="Inceput">
                           </div>
                           <div class="col-xs-2 text-center">
                               <input type="text" id="contract_end_date" class="datepicker small_date_input" readonly="readonly" placeholder="Sfarsit">
                           </div>
                       </div>
                   </div>
                <?php //} ?>
            </div>
            <br>
            <div class="row">
            <?php
                if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==1 || $_SESSION['user']['user_role']==6)) { ?>
                    <input type="hidden" class="form-control" id="select_user" name="select_user" value="<?php echo $_SESSION['user']['id']; ?>">
                <?php 
                } else {
                ?>
                <div class="col-md-4">
                    <select class="form-control" id="select_user" name="select_user" onchange="show_apeluri_by_raspuns()">
                        <option value="">Alege user</option>
                        <?php
                            $users = get_users_list(1);
                            if (count($users))
                                foreach ($users as $key=>$value)
                                    echo '<option value="'.$value['id'].'">'.$value['fname'].' '.$value['lname'].' - '.$value['email'].'</option>';
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if ($_SESSION['user']['user_role']!=1  && $_SESSION['user']['user_role']==6) { ?>
                <div class="col-md-4">
                    <select class="form-control" id="select_judet" name="select_judet" onchange="show_orase_by_judet(this.value);show_apeluri_by_raspuns()">
                        <option value="">Alege judet</option>
                        <?php
                            foreach ($config['judet'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-md-4" id="div_localitate"></div>
                <?php } ?>
            </div>
            <br>
                <div class="row">
                    <?php if ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==4) { ?>
                        <div class="col-md-4">
                            <select class="form-control" id="select_status_contract" name="select_status_contract" onchange="show_apeluri_by_raspuns();">
                                <option value='x'>Alege status</option>
                                <option value='0'>Status necompletat</option>
                                <?php
                                    if (count($config['status_contract'])){
                                        foreach($config['status_contract'] as $key=>$value){
                                            echo '<option value="'.$key.'">'.$value.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div> 
                    <?php } ?>
                    <?php if ($_SESSION['user']['user_role']==5 || $_SESSION['user']['user_role']==2 ) { ?>
                        <div class="col-md-4">
                            Factura > <input type="text" maxlength="20" class="numar small_input" id="valoare_factura" name="valoare_factura" onkeyup="show_apeluri_by_raspuns();" value="">
                        </div>    
                    <?php } ?>  
                </div>
        </div>   <br>
        <div id="apeluri_list_div" class="table_small_font"></div>
        <?php if ($_SESSION['user']['user_role']!=1 && $_SESSION['user']['user_role']==6) { ?>
           Rezultate pe pagina: &nbsp;<select  id="no_per_page" name="no_per_page" onchange="show_apeluri_by_raspuns()">
                        <?php
                            foreach ($config['no_per_page'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select>
            <?php } ?>
        <div class="loader"></div>
    </section>
    <script>
    $(document).ready(function() {
        show_apeluri_by_raspuns();
        //lista_apeluri();

    });

    function show_apeluri_by_raspuns(){
        $('#apeluri_list_div').html();
        $('#apeluri_list_div').html('<div class="demo-html"></div><table id="apeluri_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Data</th><th>Firma</th><th>Rezultat</th><th>Contact</th><th>Telefon</th></tr></thead></table>');
        lista_apeluri();


    }

    function show_orase_by_judet(judet){
    $('#div_localitate').html('');
     $.ajax (
        {
        	url : "ajax.php",

        	data : { action: 'get_apeluri_localitati_by_judet',judet:judet},

        	complete : function (xhr, result){
                var response = xhr.responseText;
                $('#div_localitate').html(response);
        	}
        });
    }

    function lista_apeluri() {

        $('.loader').show();
        raspuns_arr = JSON.parse('<?php echo JSON_encode($config['rezultat_apel']);?>');

        call_url="include/datatables/lista_apeluri.php?";

        raspuns= $('#select_raspuns').val();
        if (raspuns != '0')
            call_url=call_url+'&raspuns='+raspuns;

        user_id= $('#select_user').val();
        if (user_id != '')
            call_url=call_url+'&user_id='+user_id;

        if ($('#select_status_contract').length){
            select_status_contract= $('#select_status_contract').val();
            //if (select_status_contract != '0')
                call_url=call_url+'&status_contract='+select_status_contract;  
        }

        start_date= $('#apel_start_date').val();
        if (start_date != '')
            call_url=call_url+'&start_date='+start_date;

        end_date= $('#apel_end_date').val();
        if (end_date != '')
            call_url=call_url+'&end_date='+end_date;

        if ($('#contract_start_date').length){
            contract_start_date= $('#contract_start_date').val();
                if (contract_start_date != '')
                    call_url=call_url+'&contract_start_date='+contract_start_date;
        }

        if ($('#contract_end_date').length){
            contract_end_date= $('#contract_end_date').val();
                if (contract_end_date != '')
                    call_url=call_url+'&contract_end_date='+contract_end_date;
        }
        
        if ($('#valoare_factura').length)
            call_url=call_url+'&valoare_factura='+$('#valoare_factura').val().replace(new RegExp(/[^0-9]/g, 'g'),'');
        
   
        
        if ($('#select_judet').length){
            judet= $('#select_judet').val();
            if (judet != ''){
                call_url=call_url+"&judet="+judet;
                if ($('#select_localitate').length){
                    localitate= $('#select_localitate').val();
                    if (localitate != '')
                        call_url=call_url+'&localitate='+localitate;
                }
            }
        }



        var dt=$('#apeluri_table').DataTable( {
                dom: "Bfrtip",
                pageLength: $('#no_per_page').val(),
                ajax: {
                    url: call_url,
                    type: 'POST'
                },
                serverSide: true,
                columns: [
                    { data: "rezultat_apel.date" },
                    { data: "firma.nume" },
                    { data: null, render: function ( data, type, row ) {
                            ret='';
                            var ky=data.rezultat_apel.rezultat;
                            if (raspuns_arr[ky])
                                ret=ret+raspuns_arr[ky];
                            if (data.rezultat_apel.observatie != '')
                                ret=ret+'<br>'+data.rezultat_apel.observatie;
                            ret=ret+'<input type="hidden"  name= value="'+data.firma_id+'">';
                            return ret;
                        }
                    },
                    //{ data: "users.email" },
                    { data: null, searchable:false,
                        render: function ( data, type, row ) {
                            return data.firma.nume_contact+' '+data.firma.mobil+' '+data.firma.telefon;
                        }
                    },
                    { data: "firma.nume_contact", visible:false },
                    { data: "firma.mobil", visible:false },
                    { data: "firma.telefon", visible:false }
                ],  
                columnDefs: [
                    { "searchable": false, "targets": 2 },
                    { "orderable": false, "targets": 2 }
                ], 
                order: [ 0, 'desc' ],
                paging: true
            } );

            var detailRows = [];

            $('#apeluri_table tbody').on( 'click', 'tr', function () {
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

                ascrollto('top_filters');

            } );

            dt.on( 'processing.dt', function ( e, settings, processing ) {
                $('.loader').css( 'display', processing ? 'block' : 'none' );
            } ).dataTable();


    }
    </script>
<?php
include_once 'footer.php';
?>

