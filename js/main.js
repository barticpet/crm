$(function() {
   
    
    $('.numar').on( "keyup", function( event ) {        
        
        // When user select text in the document, also abort.
        var selection = window.getSelection().toString();
        if ( selection !== '' ) {
            return;
        }
        
        // When the arrow keys are pressed, abort.
        if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
            return;
        }
        
        
        var $this = $( this );
        
        // Get the value.
        var input = $this.val();
        
        var input = input.replace(/[\D\s\._\-]+/g, "");
                input = input ? parseInt( input, 10 ) : 0;

                $this.val( function() {
                    return ( input === 0 ) ? "" : input.toLocaleString( );
                } );
    } );
    

        $("#btn_save").click(function(){
            if ($(this).attr('data-id')=='form_rezultat_apel'){
                set_rezultat_apel();
            }
            else if ($(this).attr('data-id')=='set_status_contract'){
                set_status_contract();
            }
            else if ($(this).attr('data-id')=='editare_user'){
                set_editare_user();
            }
            else if ($(this).attr('data-id')=='add_user'){
                set_add_user();
            }
            else if ($(this).attr('data-id')=='sterge_user'){
                finalizare_stergere_user($('#user_de_sters').val());
            }
            else if ($(this).attr('data-id')=='sterge_status_contract'){
                finalizare_stergere_status_contract($('#status_contract').val());
            }
            else if ($(this).attr('data-id')=='sterge_apel'){
                finalizare_stergere_apel($('#firma_id').val(),$('#apel_de_sters').val());
            }
            else if ($(this).attr('data-id')=='editare_firma'){
                set_editare_firma();
            }
            else if ($(this).attr('data-id')=='sterge_firma'){
                finalizare_stergere_firma($('#firma_de_sters').val());
            }
    
        });
    
        $(document).on('click', '.panel-heading span.clickable', function(e){
        var $this = $(this);
        if(!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    })
    });
    
    function get_reminder_text(){

        $.ajax (
            {
                url : "ajax.php",

                data : { action: 'get_reminder_text'},

                complete : function (xhr, result){
                    var response = xhr.responseText;
                    $('#reminder_text').html(response);
                }
            });

    }
    
    function show_popup(el){
        var pageTitle = $(el).attr('pageTitle');
        var pageName = $(el).attr('pageName');
    
        $(".modal .modal-title").html(pageTitle);
        $(".modal .modal-body").html("Content loading please wait...");
        id=$(el).attr('data-id');
        if (pageTitle=='Rezultat apel'){
            show_rezultat_apel(id);
        }
        else if (pageTitle=='Status contract'){
            status_contract(id,pageName);
        }      
        else if (pageTitle=='Editare user'){
            editare_user(id,pageName);
        } 
        else if (pageTitle=='Add user'){
            add_user(id,pageName);
        } 
        else if (pageTitle=='Sterge user'){
            name=$(el).attr('data-name');
            sterge_user(id,name,pageName);
        } 
        else if (pageTitle=='Editare firma'){
            editare_firma(id,pageName);
        } 
        else if (pageTitle=='Sterge firma'){
            name=$(el).attr('data-name');
            sterge_firma(id,name,pageName);
        }
    
    }
    
    function manage_rezultat_apel(v,selected_options){//alert(JSON.stringify(selected_options));
        $('.hidden_element').hide();
        if (v=='0') 
            $("#btn_save").hide();
            else {
                $("#btn_save").show();                
                $(".close_option").show();
            }
        switch (v){
            case '3':
                $('#3_options').show();
                $("#observatie").val('');
                $('#observatie_option').show();
                break;
            case '7':
                $('#7_options').show();
                $("#observatie").val('');
                break;
            case '8':
                    $.ajax (
                    {
                        url : "ajax.php",
        
                        data : { action: 'get_mail_content'},
        
                        complete : function (xhr, result){
                            var response = xhr.responseText;
                            $('#tip_email').html(response);
                            if (selected_options != null){
                                for (i in selected_options){
                                    $('#tip_email'+selected_options[i]).prop("checked", "true");
                                }
                            }                        
                            $('#8_options').show();
                            $("#observatie").val('');
                        }
                    });
                break;
            
            case '9':
                    $.ajax (
                    {
                        url : "ajax.php",
        
                        data : { action: 'get_vrea_contract_content'},
        
                        complete : function (xhr, result){
                            var response = $.parseJSON (xhr.responseText);
                            $('#9_options').html(response.contract_option);
                              
                            if (response.status_contract){
                                $('#status_contract_div').html('<hr>'+response.status_contract);
                                $( "#data_activare" ).datepicker({
                                    dateFormat: "yy-mm-dd",
                                    onSelect: function (dateText, inst) {
                                        $(this).datepicker('hide');
                                    }
                                });
                                $( "#data_livrare" ).datepicker({
                                    dateFormat: "yy-mm-dd",
                                    onSelect: function (dateText, inst) {
                                        $(this).datepicker('hide');
                                    }
                                });
                                status=0;
                                if (selected_options != null && selected_options.hasOwnProperty('status')) status=selected_options.status;
                                get_status_contract_option(status);
                                $('#status_contract_div').show();
                            }

                            if (selected_options != null) { 
                                $( ".9_option_field" ).each(function() {
                                    if (selected_options[$(this).attr('id')] !== undefined)
                                        $(this).val(selected_options[$(this).attr('id')]);
                                });         
                            }



                            $('#9_options').show();
                            $("#observatie").val('');

                            
                        }
                    });
                break;
            default:
                $('#observatie_option').show();
        }
    }
    
    function set_rezultat_apel_by_id (firma_email,firma_id,contactare_id,rezultat_id,data_apel){
        $(".modal .modal-title").html('Apel din '+data_apel );
        $(".modal .modal-body").html("Content loading please wait...");
        $(".modal").modal("show");
        $.get( 'include/popup/rezultat_apel.html', function( data ) {
            $(".modal .modal-body").html(data);
            $('#form_rezultat_apel')[0].reset();
            $( "#data_estimata" ).datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate:"+0d",
                    onSelect: function (dateText, inst) {
                        $(this).datepicker('hide');
                    }
                });
            //var dateNow = new Date();
            var defaultDay = moment().subtract(1, 'day');
            var tomorrow = moment().add(1, 'days').hour('09').minute('00');
            $( "#data_revenire_pick" ).datetimepicker({
                minDate:defaultDay,
                defaultDate:tomorrow,
                format: "YYYY-MM-DD HH:mm",
                ignoreReadonly: true,
                allowInputToggle: true,
                widgetPositioning: {vertical:'bottom'}
            }).on('dp.hide', function (e) { newdate=e.date; $("#data_revenire").val(newdate.format("YYYY-MM-DD HH:mm"));});
            //$('.tip_email').prop("checked", false);
            $('#send_to_email').val(firma_email);
            $('#rezultat_id').val(rezultat_id);
            $("#btn_save").attr('data-id','form_rezultat_apel');
            $('#form_rezultat_apel').prepend('<input type="hidden" id="firma_id"  name="firma_id" class="form-control" value="'+firma_id+'"><input type="hidden" id="contactare_id" name="contactare_id" class="form-control" value="'+contactare_id+'">');
            if (rezultat_id != '0'){
                $("#btn_save").show();
                $(".close_option").show();
                $.ajax (
                {
                    url : "ajax.php",
    
                    data : { action: 'get_rezultat_apel_by_id',id:rezultat_id},
    
                    complete : function (xhr, result){
                        var response = xhr.responseText;
                        if (response != ''){
                            res =$.parseJSON (response);// alert(res);
                            $("#rezultat").val(res.rezultat);
                            $('#observatie_option').hide();
                            if (res.rezultat == 9){
                                manage_rezultat_apel('9',res);
                            }
                            else if (res.rezultat == 8){
                                if (res.mail != ''){
                                    manage_rezultat_apel('8',res.mail_arr);
                                }
                                $("#send_to_email").val(res.send_to_email);
                            } else if (res.rezultat == 3){
                                manage_rezultat_apel('3',res);
                                $("#data_revenire").val(res.data_revenire);
                                $("#data_revenire_pick input").val(res.data_revenire);
                                $("#observatie").val(res.observatie);
                            }else if (res.rezultat == 7){
                                $("#valoare_propusa").val(res.valoare_propusa);
                                 //$("#categorie_produs").val(res.categorie_produs);
                                 $("#detalii_propunere").val(res.detalii_propunere);
                                 $("#data_estimata").val(res.data_estimata);
                                 //$('#7_options').show();
                            } else {
                                $('#observatie_option').show();
                                $("#observatie").val(res.observatie);
                            }
                            
                        }
                    }
                });
            } else {
                $("#btn_save").hide();
                $(".close_option").hide();
            }
        });
    
    }
    
    function set_editare_firma(){
    
        $("#btn_save").fadeOut();
        var firma= $(".modal_form").serialize();
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'save_firma',firma:firma},
    
                complete : function (xhr, result){
    
                    var response = xhr.responseText; //alert(response);
                    if (response != 0 && response !='cui'){
                            id=$(".modal_form #firma_id").val();
                            nume=$(".modal_form #nume").val();
                            localitate=$(".modal_form #localitate").val();
                            nume_contact=$(".modal_form #nume_contact").val();
                            $('#row_'+id+' td:eq(0)').text(nume);
                            $('#row_'+id+' td:eq(1)').text(localitate);
                            $('#row_'+id+' td:eq(2)').text(nume_contact);
                            $(".modal .modal-body").html($('#success_operation').html());
                            detaliu_firma(response);
                        } else {
                            if (response =='cui')
                                $(".modal .modal-body").html($('#fail_operation').html()+'<br>Mai exista o firma cu aceleasi CUI!');
                            else $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
    
                    }
                });
    
    }

    function get_status_contract_option(sel){
        $.ajax (
            {
                url : "ajax.php",

                data : { action: 'get_status_contract_option'},

                complete : function (xhr, result){
                    var response = xhr.responseText;
                    $('#status').html(response);
                    $('#status').val(sel);
                }
            });   
    }

    function status_contract(firma_id){
        $.get( 'include/popup/status_contract.html', function( data ) {
            $(".modal .modal-body").html(data);
            $('.firma_id').val(firma_id);
            get_status_contract_option(0);
            $("#btn_save").attr('data-id','set_status_contract');
            $("#btn_save").show();
            $( "#data_activare, #data_livrare, #data_portare" ).datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                }
            });
            $(".modal").modal("show");
        });
    }
    
    function set_status_contract(){
        
            ret=1;
            statusul=$(".modal_form #status");
            if (statusul.val() == 0){
                statusul.addClass('field_error');
                ret=0;
            } else statusul.removeClass('field_error');
            
            if(ret==1){
                $("#btn_save").fadeOut();
                var frm= $("#form_status_contract").serialize();
                $.ajax (
                    {
                    url : "ajax.php",
        
                    data : { action: 'save_status_contract',form_status_contract:frm},
        
                    complete : function (xhr, result){
        
                        var response = xhr.responseText;
                        if (response != 0){
                                
                                $(".modal .modal-body").html($('#success_operation').html());
                                detaliu_firma(response);
                            } else {
                                $(".modal .modal-body").html($('#fail_operation').html());
        
                            }
        
                        }
                    });
            }
        
        }
    
    function set_rezultat_apel(){
    
        rezultat=$('#rezultat').val();
        if (rezultat==8){
            send_to_email=$('#send_to_email').val();
            if (!validateEmail(send_to_email)){
                $("#send_to_email").addClass('field_error');
                return ;
            } else $("#send_to_email").removeClass('field_error');    
        } else if (rezultat==7){
            ret=1;
            check_fields=['valoare_propusa','detalii_propunere','data_estimata'];
            for (i in check_fields){
                field=$('#'+check_fields[i]);
                if ($.trim(field.val())==''){
                    field.addClass('field_error');
                    ret=0;
                } else field.removeClass('field_error');
            }
            if (ret==0)
                return;
        }
    
        form_rezultat_apel=$("#form_rezultat_apel").serialize();

        $("#btn_save").fadeOut();
        observatie=$('#observatie').val();
        firma_id=$('#firma_id').val();
        rezultat_id=$('#rezultat_id').val();

        form_status_contract=$("#form_status_contract").serialize()+ "&firma_id="+firma_id;

        if (firma_id != null && ( rezultat != '0' || (rezultat=='0' && observatie != '') )){
            $.ajax (
                {
                    url : "ajax.php",
    
                   data : { action: 'set_rezultat_apel',form_rezultat_apel:form_rezultat_apel,form_status_contract:form_status_contract},
    
                    complete : function (xhr, result){
                        var response = xhr.responseText; //alert(response);
                        if (response != 0){
                            detaliu_firma(firma_id);
                            var loc= window.location.href;
                            if (loc.includes("firme.php") && (!($('#select_raspuns').length) || $('#select_raspuns').val()==0)){
                                $('#warning_content').html('Fiind in zona de firme neconectate firma aceasta o veti gasi in cele contactate!');
                                $(".modal .modal-body").html($('#success_operation').html()+$('#warning_operation').html());
                                setTimeout(function(){ $('#row_'+firma_id).click();$('#row_'+firma_id).hide();}, 1500);
                            } else $(".modal .modal-body").html($('#success_operation').html());
                        } else {
                            $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
                        get_reminder_text();
    
                    }
                });
    
        } else $(".modal .modal-body").html($('#fail_operation').html());
    }
    
    function set_editare_user(){
    
        ret=1;
        email=$(".modal_form #email").val();
        fname=$(".modal_form #fname").val();
        lname=$(".modal_form #lname").val();
        password=$(".modal_form #password").val();
    
        var fields_required=['email','fname','lname','password'];
        for (i=0;i<fields_required.length;i++){
            the_field=$('#'+fields_required[i]);
            if (the_field.val().length<2){
                the_field.addClass('field_error');
                ret=0;
            } else the_field.removeClass('field_error');
        }
    
        if (!validateEmail(email)){
            $(".modal_form #email").addClass('field_error');
            ret=0;
        } else $(".modal_form #email").removeClass('field_error');
    
        if(ret==1){
            $("#btn_save").fadeOut();
            var user= $(".modal_form").serialize();
            $.ajax (
                {
                url : "ajax.php",
    
                data : { action: 'save_user',user:user},
    
                complete : function (xhr, result){
    
                    var response = xhr.responseText; //alert(response);
                    if (response != 0){
                            id=$(".modal_form #id").val();
                            fname=$(".modal_form #fname").val();
                            lname=$(".modal_form #lname").val();
                            email=$(".modal_form #email").val();
                            $('#row_'+id+' td:eq(0)').text(email);
                            $('#row_'+id+' td:eq(1)').text(fname);
                            $('#row_'+id+' td:eq(2)').text(lname);
                            $(".modal .modal-body").html($('#success_operation').html());
                            detaliu_user(response);
                        } else {
                            $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
    
                    }
                });
        }
    
    }
    
    function set_add_user(){
        ret=1;
        email=$(".modal_form #email").val();
        fname=$(".modal_form #fname").val();
        lname=$(".modal_form #lname").val();
        password=$(".modal_form #password").val();
    
        var fields_required=['email','fname','lname','password'];
        for (i=0;i<fields_required.length;i++){
            the_field=$('#'+fields_required[i]);
            if (the_field.val().length<2){
                the_field.addClass('field_error');
                ret=0;
            } else the_field.removeClass('field_error');
        }
    
        if (!validateEmail(email)){
            $(".modal_form #email").addClass('field_error');
            ret=0;
        } else $(".modal_form #email").removeClass('field_error');
    
        if(ret==1){
            $("#btn_save").fadeOut();
            var user= $(".modal_form").serialize();
            $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'save_user',user:user},
    
                complete : function (xhr, result){
    
                    var response = xhr.responseText; //alert(response);
                    if (response != 0){
                            id=$(".modal_form #id").val();
                            show_users();
                            $(".modal .modal-body").html($('#success_operation').html());
                            detaliu_user(response);
                        } else {
                            $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
    
                    }
                });
        }
    }
    
    
    function sterge_status_contract(id,nume_status) {
        $("#btn_save").show();
        $(".modal .modal-title").html('Stergere Status Contract');
        $(".modal .modal-body").html("<input type='hidden' id='status_contract' value='"+id+"'>Esti sigur ca vrei sa stergi statusul <b>"+nume_status+"</b>?");
        $("#btn_save").attr('data-id','sterge_status_contract');
        $(".modal").modal("show");
    }

    function sterge_apel(firma_id,id,data){
        $(".modal .modal-title").html('Sterge apel');
        $(".modal .modal-body").html("Content loading please wait...");
        $(".modal").modal("show");
        $("#btn_save").show();
        $(".modal .modal-body").html("<input type='hidden' id='firma_id' value='"+firma_id+"'><input type='hidden' id='apel_de_sters' value='"+id+"'>Esti sigur ca vrei sa stergi apelul din: <b>"+data+"</b>?");
        $("#btn_save").attr('data-id','sterge_apel');
        $(".modal").modal("show");
    }
    
    function sterge_user(id,name,pageName) {
        $("#btn_save").show();
        $(".modal .modal-body").html("<input type='hidden' id='user_de_sters' value='"+id+"'>Esti sigur ca vrei sa stergi userul: <b>"+name+"</b>?");
        $("#btn_save").attr('data-id','sterge_user');
        $(".modal").modal("show");
    }
    
    function add_user(id,pageName) {
        $(".modal").modal("show");
        $.get( pageName, function( data ) {
            $(".modal .modal-body").html(data);
            $("#btn_save").show();
            $("#btn_save").attr('data-id','add_user');
        });
    }
    
    
    function editare_user(id,pageName) {
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'editare_user',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText;
                    if (response != ''){
                        user =$.parseJSON (response);
                        $(".modal").modal("show");
                        $.get( pageName, function( data ) {
                            $(".modal .modal-body").html(data);
                            $("#btn_save").show();
                            $("#btn_save").attr('data-id','editare_user');
                            for (i in user){
                                if ($('#'+i).length)
                                    $('#'+i).val(user[i]);
                            }
                        });
                    }
    
                }
            });
    }
    
    function finalizare_stergere_status_contract(id){
        $("#btn_save").fadeOut();
        $.ajax (
           {
               url : "ajax.php",
   
               data : { action: 'delete_status_contract',id:id},
   
               complete : function (xhr, result){
                   var response = xhr.responseText; //alert(response);
                   if (response != 0){
                           detaliu_firma(response);
                           $(".modal .modal-body").html($('#success_operation').html());
                       } else {
                           $(".modal .modal-body").html($('#fail_operation').html());
   
                       }
   
               }
           });
   }
   
   function finalizare_stergere_apel(firma_id,id){
    $("#btn_save").fadeOut();
    $.ajax (
       {
           url : "ajax.php",

           data : { action: 'delete_apel',id:id},

           complete : function (xhr, result){
               var response = xhr.responseText; //alert(response);
               if (response == 1){
                       detaliu_firma(firma_id);
                       $(".modal .modal-body").html($('#success_operation').html());
                   } else {
                       $(".modal .modal-body").html($('#fail_operation').html());

                   }

           }
       });
    }

    
    function finalizare_stergere_user(id){
         $("#btn_save").fadeOut();
         $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'delete_user',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText; //alert(response);
                    if (response == 1){
                            $('#row_'+id).click();
                            $('#row_'+id).hide();
                            $(".modal .modal-body").html($('#success_operation').html());
                        } else {
                            $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
    
                }
            });
    }
    
    function finalizare_stergere_firma(id){
         $("#btn_save").fadeOut();
         $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'delete_firma',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText; //alert(response);
                    if (response == 1){
                            $('#row_'+id).click();
                            $('#row_'+id).hide();
                            $(".modal .modal-body").html($('#success_operation').html());
                        } else {
                            $(".modal .modal-body").html($('#fail_operation').html());
    
                        }
    
                }
            });
    }
    
    function sterge_firma(id,name,pageName) {
        $("#btn_save").show();
        $(".modal .modal-body").html("<input type='hidden' id='firma_de_sters' value='"+id+"'>Esti sigur ca vrei sa stergi firma <b>"+name+"</b>?");
        $("#btn_save").attr('data-id','sterge_firma');
        $(".modal").modal("show");
       /* $.get( pageName, function( data ) {
            $(".modal .modal-body").html(data);
            $("#btn_save").show();
            $("#btn_save").attr('data-id','sterge_firma');
        });*/
    }
    
    function editare_firma(id,pageName) {
        $(".modal").modal("show");
        if (id != 0){
            $.ajax (
                {
                    url : "ajax.php",
        
                    data : { action: 'editare_firma',id:id},
        
                    complete : function (xhr, result){
                        var response = xhr.responseText; //alert(response);
                        if (response != ''){
                            firma =$.parseJSON (response);
                            $.get( pageName, function( data ) {
                                $(".modal .modal-body").html(data);
                                $("#btn_save").show();
                                $("#btn_save").attr('data-id','editare_firma');
                                for (i in firma){
                                    if ($('#'+i).length)
                                        $('#'+i).val(firma[i]);
                                }
                            });
                        }
        
                    }
                });
        } else {
            $(".modal .modal-title").html('Adauga firma');
            $.get( pageName, function( data ) {
                $(".modal .modal-body").html(data);
                $("#btn_save").show();
                $("#btn_save").attr('data-id','editare_firma');
                $('#firma_id').val(0);
            });
        }
        
    }
    
    function show_rezultat_apel(firma_id,pageName) {
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'lista_apeluri_firma',id:firma_id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText; //alert(response);
                    if (response != ''){
                        $(".modal").modal("show");
                        $.get( pageName, function( data ) {
                            $(".modal .modal-body").html(data);
                            $("#btn_save").show();
                            $("#btn_save").attr('data-id','form_rezultat_apel');
                            $('#form_rezultat_apel').prepend(response);
                        });
                    }
    
                }
            });
    }
    
    function show_users(){
    
        $('#users_list_div').html();
        $('#users_list_div').html('<div class="demo-html"></div><table id="users_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Email</th><th>Nume</th><th>Prenume</th></tr></thead></table>');
    
        call_url="include/datatables/lista_users.php";
    
        var dt=$('#users_table').DataTable( {
            dom: "Bfrtip",
            ajax: {
                url: call_url,
                type: 'POST'
            },
            serverSide: true,
            columns: [
                { data: "users.email" },
                { data: "users.fname" },
                { data: "users.lname" }
                //{ data: "contactare_firma.date" }
            ],
            order: [ 0, 'asc' ],
            select: true
        } )
    
    
        var detailRows = [];
    
        $('#users_table tbody').on( 'click', 'tr', function () {
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
                row.child( detaliu_user( current_id ) ).show();
    
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
    
    }
    
    function detaliu_user ( id ) {
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'detaliu_user',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText;
                    $('#user_detaliu_'+id).html(response);
    
                }
            });
        return '<div id="user_detaliu_'+id+'"></div>';
    }
    
    
    
    function contactare_firma(id,telefon){
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'check_contactare_firma',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText;
                    if (response == 0){
                        window.location='tel:'+telefon;
                        $.ajax (
                            {
                                url : "ajax.php",
                    
                                data : { action: 'contactare_firma',id:id},
                    
                                complete : function (xhr, result){
                                    var response = xhr.responseText;
                                    detaliu_firma(id,response);                    
                                    
                    
                                }
                        });
                    } else {
                        $('#warning_content').html('Firma contactata de alt operator!');
                        $(".modal .modal-title").html('Apel oprit');
                        $(".modal .modal-body").html($('#warning_operation').html());
                        $("#btn_save").hide();
                        $(".modal").modal("show");
                        $('#row_'+id).click().hide();
                    }
                    
    
                }
            });
        
    }
    
    function detaliu_firma ( id,contactare_id ) {
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'detaliu_firma',id:id},
    
                complete : function (xhr, result){
                    var response = xhr.responseText;
                    $('#detalii_'+id).html(response);
                    if (contactare_id !== 'undefined')
                        $('#btn_rezultat_apel_'+contactare_id).click();
                    //if ($( "#lista_contactari_"+id ).length)
                      //  $('#firma_contact_div_'+id).append('<button data-id="'+id+'" class="btn btn-primary btn-sm pop" pageTitle="Rezultat apel" pageName="include/popup/rezultat_apel.html" onclick="show_popup(this);" >Rezultat apel</button>');
    
                }
            });
        return '<div id="detalii_'+id+'"></div>';
    }
    
     /*
    function show_firme_by_localitate(){
        $('#firme_list_div').html();
        $('#firme_list_div').html('<div class="demo-html"></div><table id="firme_table" class="display" cellspacing="0" width="100%"><thead><tr><th>Nume</th><th>Localitate</th><th>Persoana Contact</th></tr></thead></table>');
        firme_list();
    
    
    }  */
    
    function show_firme_by_judet(judet){
        $.ajax (
            {
                url : "ajax.php",
    
                data : { action: 'get_localitati_by_judet',judet:judet},
    
                complete : function (xhr, result){
                    var response = xhr.responseText;
                    $('#div_localitate').html(response);
                    $('#div_localitate').show();
                    firme_list();
                }
            });
    
    
    }
       
    
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }


    function getRndInteger(min, max, dif) {
        if (dif===undefined)
            return Math.floor(Math.random() * (max - min + 1) ) + min;

        else {
            ret=Math.floor(Math.random() * (max - min + 1) ) + min;
            if (dif != ret)
                return ret;
                else return 0;
        }
    }

    function getRndItemFromArr (arr){
        return arr[Math.floor(Math.random() * arr.length)]
    }

    function scrollToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    function ascrollto(id) {
        var etop = $('#' + id).offset().top;
        $('html, body').animate({
          scrollTop: etop
        }, 750);
    }

    function show_status_details(status){
        $( "#status_details_div_"+status).toggle( "slow", function() {
            if ($('#glyphicon-chevron_'+status).hasClass('glyphicon-chevron-up')){
                $('#glyphicon-chevron_'+status).removeClass('glyphicon-chevron-up');
                $('#glyphicon-chevron_'+status).addClass('glyphicon-chevron-down');
            } else {            
                $('#glyphicon-chevron_'+status).removeClass('glyphicon-chevron-down');
                $('#glyphicon-chevron_'+status).addClass('glyphicon-chevron-up');
            }
          });
        
    }