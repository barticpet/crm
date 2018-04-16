<?php
$menu="apeluri-revenit";
include_once 'header.php';
?>
    <section class="section">
      <div id="top_filters" class="container top_filters">
            <div class="row">
            <?php
                if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==1 || $_SESSION['user']['user_role']==6 )) { ?>
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
                <?php if ($_SESSION['user']['user_role']!=1 && $_SESSION['user']['user_role']!=6) { ?>
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
        </div>   <br>
        <div id="apeluri_list_div" class="table_small_font"></div>
        <?php if ($_SESSION['user']['user_role']!=1 || $_SESSION['user']['user_role']!=6) { ?>
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
        $('#apeluri_list_div').html('<div class="demo-html"></div><table id="apeluri_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Data revenirii</th><th>Firma</th><th>Rezultat</th><th>Contact</th><th>Telefon</th></tr></thead></table>');
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

        call_url="include/datatables/lista_apeluri_de_revenit.php?";
        
        call_url=call_url+'&data_revenire='+moment().format("YYYY-MM-DD 23:59")+'&raspuns=3';

        user_id= $('#select_user').val();
        if (user_id != '')
            call_url=call_url+'&user_id='+user_id;
        
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
                createdRow: function( row, data, dataIndex ) {
                    if ( moment(data.rezultat_apel_de_revenit.data_revenire) < moment() ) {
                        $(row).addClass( 'apel_uitat' );
                    }
                },
                columns: [
                    { data: "rezultat_apel_de_revenit.data_revenire" },
                    { data: "firma.nume" },
                    { data: null, render: function ( data, type, row ) {
                            ret='';
                            //var ky=data.rezultat_apel.rezultat;
                            //if (raspuns_arr[ky])
                              //  ret=ret+raspuns_arr[ky];
                            //if (data.rezultat_apel_de_revenit.data_revenire !=null)
                             //   ret+=': '+data.rezultat_apel_de_revenit.data_revenire;
                            if (data.rezultat_apel.observatie != '')
                                ret=ret+'<br>'+data.rezultat_apel.observatie;
                            /*if (ky==7){
                                ret=ret+'<br>';
                                if (data.rezultat_apel_interesat.valoare_propusa != null & data.rezultat_apel_interesat.valoare_propusa !='')
                                    ret=ret+'<br> Valoare propusa: '+data.rezultat_apel_interesat.valoare_propusa;
                                if (data.rezultat_apel_interesat.categorie_produs != null & data.rezultat_apel_interesat.categorie_produs != '')
                                    ret=ret+'<br> Categorie produs: '+data.rezultat_apel_interesat.categorie_produs;
                                if (data.rezultat_apel_interesat.detalii_propunere != null & data.rezultat_apel_interesat.detalii_propunere != '')
                                    ret=ret+'<br> Detalii propunere: '+data.rezultat_apel_interesat.detalii_propunere;
                                if (data.rezultat_apel_interesat.data_estimata != null & data.rezultat_apel_interesat.data_estimata != '')
                                    ret=ret+'<br> Data estimata: '+data.rezultat_apel_interesat.data_estimata;
                            }*/
                            // Combine the first and last names into a single table field
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

