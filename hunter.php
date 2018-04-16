<?php
$menu='firme';
include_once 'header.php';
?>
<script>
$( function() {
   /* $( "#vrea_contract_start_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    firme_list();
                }
            });
    $( "#vrea_contract_end_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    firme_list();
                }
            });
     */   
    $( "#contract_end_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+6m",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });   
    $( "#contract_start_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+6m",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });
} );
</script>
    <section class="section">
    <div class="container">
        
        <?php if ($_SESSION['user']['user_role']!=4) { ?>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" id="select_judet" name="select_judet" onchange="show_firme_by_judet(this.value);">
                        <option value="">Alege judet</option>
                        <?php
                            foreach ($config['judet'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select>
                    <div id="div_localitate" style="display:none;"></div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="select_raspuns" name="select_raspuns" onchange='$( "#select_contactare" ).val(0);firme_list();'>
                        <option value='0'>Rezultat apel</option>
                        <?php
                            foreach ($config['rezultat_apel'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select>
                </div>
                
                <div class="col-md-4" >
                    <select class="form-control" id="select_activitate" name="select_activitate" onchange="firme_list();">
                        <option value=''>Selecteaza o activitate</option>
                        <?php
                            include_once 'include/caen.php';   echo($caen);
                        ?>
                    </select>
                </div>
            </div>
            <?php } else echo '<input type="hidden"  id="select_raspuns" name="select_raspuns" value="9">'; ?>

            <?php if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==3 || $_SESSION['user']['user_role']==4)) { ?>
            <br>
            <div class="row">
                <?php if ($_SESSION['user']['user_role']==2) { ?>
                <div class="col-md-4">
                    <select class="form-control" id="select_contactare" name="select_contactare" onchange="firme_list();">
                        <option value='0'>Toate</option>
                        <option value='1'>Necontactate</option>
                        <?php if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2) { ?>
                        <option value='2'>Contactate</option>
                        <?php } ?>
                        <option value='3'>Contactate doar de mine</option>
                    </select>
                </div>
                <?php } ?>

                <?php if ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==4) { ?>
                <div class="col-md-4">
                    <select class="form-control" id="select_status_contract" name="select_status_contract" onchange="firme_list();">
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
                
                <!--<div class="col-md-3">
                    <b>Start:</b>
                    <input type="text" id="vrea_contract_start_date" class="datepicker">
                </div>
                <div class="col-md-3">
                    <b>End:</b>
                    <input type="text" id="vrea_contract_end_date" class="datepicker">
                </div>-->

                <?php } ?>
                <?php if ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==3) { ?>
                 <div class="col-md-4">
                    <b>Inceput contract :</b>
                    <input type="text" id="contract_start_date" class="datepicker"><br><br>
                    <b>Final contract :</b>
                    <input type="text" id="contract_end_date" class="datepicker">
                </div>
                <?php } ?>
            </div>
             <?php } ?>
        </div>
        <br>
        <div id="firme_list_div" ></div>
        <div class="loader"></div>
        <button data-id="0" class="btn btn-primary btn-sm pop" pageTitle="Editare firma" onclick="show_popup(this);" pageName="include/popup/editare_firma.html">Adauga firma</button>
    </section>
    <script>

    $(document).ready(function() {
            //show_firme_by_judet('');
            firme_list();
        } );

        function firme_list(){
        $('.loader').show();
        $('#firme_list_div').html();
        $('#firme_list_div').html('<div class="demo-html"></div><table id="firme_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Nume</th><th>Localitate</th><th>Persoana Contact</th><th>Mobil</th></tr></thead></table>');
    
        call_url="include/datatables/lista_firme.php?";
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
        if ($('#select_activitate').length)
            call_url=call_url+'&activitate='+$('#select_activitate').val();
        
        if ($('#select_contactare').length)
            contactare= $('#select_contactare').val();
            else contactare= '';
    
        if ($('#select_status_contract').length)
            select_status_contract= $('#select_status_contract').val();
            else select_status_contract= '0';
        call_url=call_url+'&status_contract='+select_status_contract;    
        
        if ($('#select_raspuns').length)
            raspuns= $('#select_raspuns').val();
            else raspuns=0;

        if (contactare!=1 && raspuns != '0')
                call_url=call_url+'&raspuns='+raspuns;
        
        if (contactare != ''){
                call_url=call_url+'&contactare='+contactare;
                if (contactare==1 && $('#select_raspuns').length)
                    $( "#select_raspuns" ).val("0");
        }
    
        
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
/*
        if ($('#vrea_contract_start_date').length){
            start_date= $('#vrea_contract_start_date').val();
            if (start_date != '')
                call_url=call_url+'&vrea_contract_start_date='+start_date;
        }

        if ($('#vrea_contract_end_date').length){
            end_date= $('#vrea_contract_end_date').val();
            if (end_date != '')
                call_url=call_url+'&vrea_contract_end_date='+end_date;
        }
*/    
        var dt=$('#firme_table').DataTable( {
            dom: "Bfrtip",
            ajax: {
                url: call_url,
                type: 'POST'
            },
            serverSide: true,
            columns: [
                { data: "firma.nume" },
                { data: "firma.localitate" },
                { data: null, searchable:false,
                    render: function ( data, type, row ) {
                        return data.firma.nume_contact+' '+data.firma.mobil+' '+data.firma.telefon;
                    }
                },
                { data: "firma.nume_contact", visible:false },
                { data: "firma.mobil", visible:false },
                { data: "firma.telefon", visible:false }
            ],
            order: [ 0, 'asc' ],
            select: true
        } );
    
        var detailRows = [];
    
        $('#firme_table tbody').on( 'click', 'tr', function () {
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

