<?php
$menu='firme';
include_once 'header.php';
?>
<script>
$( function() {
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
    $( "#contract_status_end_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+0d",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });   
    $( "#contract_status_start_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+0d",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    }); 
    $( "#firma_editat_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+0d",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });
    $( "#apel_start_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+0d",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });
    $( "#apel_end_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate:"+0d",
        onSelect: function (dateText, inst) {
            $(this).datepicker('hide');
            firme_list();
        }
    });
} );
</script>
    <section class="section">
        <div id="top_filters" class="container top_filters">
        
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
                <?php if ($_SESSION['user']['user_role']==5 || $_SESSION['user']['user_role']==2  || $_SESSION['user']['user_role']==1) { ?>
                    <div class="col-md-4">
                        Cifra afaceri > <input type="text" maxlength="20" class="numar small_input" id="select_cifra_afaceri" name="select_cifra_afaceri" onkeyup="firme_list();" value="<?php if ($_SESSION['user']['user_role']==5) echo 100000; else echo 0; ?>">
                    </div>
                    <?php if ( $_SESSION['user']['user_role']==1) { ?>
                        <div class="col-md-4" >
                            <input type="text" class="form-control" id="select_activitate" name="select_activitate" placeholder="Activitate" onkeyup="firme_list();">
                        </div>
                    <?php } ?>
                    
                <?php } else { ?>
                    <div class="col-md-4" >
                         <input type="text" class="form-control" id="select_activitate" name="select_activitate" placeholder="Activitate" onkeyup="firme_list();">
                    </div>
                <?php } ?>
                                
                <?php if ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==3 || $_SESSION['user']['user_role']==5) { ?>
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
                <?php } ?>
            </div>
        <?php } else echo '<input type="hidden"  id="select_raspuns" name="select_raspuns" value="9">'; ?>
        
        
        <?php if ($_SESSION['user']['user_role']==5) { ?>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-check-label">                        
                            <input type="checkbox" class="form-check-input" id="operator_necompletat" name="operator_necompletat" onclick="firme_list();" checked="true">&nbsp;<span class="align-middle">Fara operator
                        </span>
                    </label>
                </div>
                <div class="col-md-8">
                    <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="firma_needitata" name="firma_needitata" onclick="firme_list();" checked="true">&nbsp;Firma needitata
                     
                    dupa <input type="text" id="firma_editat_date" class="datepicker small_date_input "  placeholder=" data de"  readonly="readonly">
                    </label> 
                </div>
            </div>
        <?php } ?>


        <?php if (isset($_SESSION['user']['user_role'])) { ?>
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
                <?php } else if ($_SESSION['user']['user_role']==1) { ?>
                    <input type="hidden" id="select_contactare" name="select_contactare" value="1">
                <?php } ?>
                <?php if ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==4) { ?>
                    <div class="col-md-4">
                        <select class="form-control" id="select_status_contract" name="select_status_contract" onchange="firme_list();">
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
                        Factura > <input type="text" maxlength="20" class="numar small_input" id="valoare_factura" name="valoare_factura" onkeyup="firme_list();" value="">
                    </div>
                <?php } ?>
            </div>
            <?php } ?>  
            <?php if ($_SESSION['user']['user_role']==4 || $_SESSION['user']['user_role']==2 ) { ?> 
            <br>
                <div class="row">
                        <div class="col-md-4">
                        Status ctr:<br> 
                            <input type="text" id="contract_status_start_date" class="datepicker small_date_input" readonly="readonly"  placeholder="Start">
                        
                            <input type="text" id="contract_status_end_date" class="datepicker small_date_input" readonly="readonly" placeholder="End">
                        </div>
                </div>
            <?php } ?>
        </div>
        <br>
        <div id="firme_list_div" ></div>
        <?php if ($_SESSION['user']['user_role']!=1) { ?>
           Rezultate pe pagina: &nbsp;<select  id="no_per_page" name="no_per_page" onchange="firme_list();">
                        <?php
                            foreach ($config['no_per_page'] as $key=>$value)
                                echo '<option value="'.$key.'">'.$value.'</option>';
                        ?>
                    </select><br>
            <?php } ?>
        <div class="loader"></div>
        <button data-id="0" class="btn btn-primary btn-sm pop" pageTitle="Editare firma" onclick="show_popup(this);" pageName="include/popup/editare_firma.html">Adauga firma</button>
    </section>
    <script>

    $(document).ready(function() {
            //show_firme_by_judet('');
            if ($('.numar').length){
                $( ".numar" ).trigger( "keyup" );                
            }
            firme_list();
        } );

        function firme_list(){
        $('.loader').show();
        $('#firme_list_div').html();
        $('#firme_list_div').html('<div class="demo-html"></div><table id="firme_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Nume</th><th>Localitate</th><th>Contact</th><th>Telefon</th></tr></thead></table>');
    
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
            
        if ($('#select_cifra_afaceri').length)
            call_url=call_url+'&cifra_afaceri='+$('#select_cifra_afaceri').val().replace(new RegExp(/[^0-9]/g, 'g'),'');
        
        if ($('#valoare_factura').length)
            call_url=call_url+'&valoare_factura='+$('#valoare_factura').val().replace(new RegExp(/[^0-9]/g, 'g'),'');
        
        if ($('#operator_necompletat').length){
            if ($('#operator_necompletat').prop("checked"))
                call_url=call_url+'&operator_necompletat=1';
        }

        
        if ($('#firma_needitata').length){
            if ($('#firma_needitata').prop("checked"))
                call_url=call_url+'&firma_needitata=1';
            firma_editat_date= $('#firma_editat_date').val();
            if (firma_editat_date != '')
                call_url=call_url+'&firma_editat_date='+firma_editat_date;
        

        }
        
        if ($('#select_contactare').length)
            contactare= $('#select_contactare').val();
            else contactare= '';
    
        if ($('#select_status_contract').length){
            select_status_contract= $('#select_status_contract').val();
            //else select_status_contract= '0';

            call_url=call_url+'&status_contract='+select_status_contract;    
        }
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
   
        if ($('#contract_status_start_date').length){
            contract_status_start_date= $('#contract_status_start_date').val();
                if (contract_status_start_date != '')
                    call_url=call_url+'&contract_status_start_date='+contract_status_start_date;
        }

        if ($('#contract_status_end_date').length){
            contract_status_end_date= $('#contract_status_end_date').val();
                if (contract_status_end_date != '')
                    call_url=call_url+'&contract_status_end_date='+contract_status_end_date;
        }
   
        if ($('#apel_start_date').length){
            apel_start_date= $('#apel_start_date').val();
                if (apel_start_date != '')
                    call_url=call_url+'&apel_start_date='+apel_start_date;
        }

        if ($('#apel_end_date').length){
            apel_end_date= $('#apel_end_date').val();
                if (apel_end_date != '')
                    call_url=call_url+'&apel_end_date='+apel_end_date;
        }
        orderBy = getRndInteger(0,5,3);
        orderDirArr = ['asc','desc'];
        orderDir=getRndItemFromArr(orderDirArr); //alert(orderBy+' '+orderDir);
        var dt=$('#firme_table').DataTable( {
            dom: "Bfrtip",
            pageLength: $('#no_per_page').val(),
            ajax: {
                url: call_url,
                type: 'POST'
            },
            serverSide: true,
            columns: [
                { data: "firma.nume" },
                { data: "firma.localitate" },
                { data: "firma.nume_contact" },
                { data: null, searchable:false,
                    render: function ( data, type, row ) {
                        return data.firma.mobil+' '+data.firma.telefon;
                    }
                },
                { data: "firma.mobil", visible:false },
                { data: "firma.telefon", visible:false }
            ],  
            columnDefs: [
                    { "searchable": false, "targets": 3 },
                    { "orderable": false, "targets": 3 }
                ],          
            order: [ orderBy, orderDir ],
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
                row.child( $("#content_row_"+current_id ).html() ).show();
    
                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows.push( tr.attr('id') );
                }
            }
        } );
    
        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {

            $("tr[id^='row_']").each(function(){
                firma_id=$(this).attr('id').substr(4);
                $('#firme_list_div').after('<div id="content_row_'+firma_id+'" style="display:none;">'+detaliu_firma( firma_id )+'</div>');
                
            
            });

            ascrollto('top_filters');

            $.each( detailRows, function ( i, id ) {
                $('#row_'+id).trigger( 'click' );
            } );
        } );
    
        dt.on( 'processing.dt', function ( e, settings, processing ) {
                    $('.loader').css( 'display', processing ? 'block' : 'none' );
                } );
    
    
    
    }

    </script>
<?php
include_once 'footer.php';
?>

