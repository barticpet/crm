<script>
$( function() {
            $( "#start_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_user_statistics();

                }
            });
            $( "#end_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_user_statistics();
                }
            });
        } );
</script>
<section class="section">
<ul class="nav nav-pills nav-justified">
  <li class="active"><a href="#">General</a></li>
  <li><a href="statistics.php?what=contract">Contracte</a></li>
</ul>
<br>
    <div class="row">
        <div class="col-md-4">
            <select class="form-control" id="select_user" name="select_user" onchange="show_user_statistics()">
                <option value="">Alege user</option>
                <?php
                    $users = get_users_list(1);
                    if (count($users))
                        foreach ($users as $key=>$value)
                            echo '<option value="'.$value['id'].'">'.$value['fname'].' '.$value['lname'].' - '.$value['email'].'</option>';
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <b>Start:</b>
            <input type="text" id="start_date" class="datepicker">
        </div>
        <div class="col-md-4">
            <b>End:</b>
            <input type="text" id="end_date" class="datepicker">
        </div>
    </div>
    <br>
    <div class="container">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th></th>
                <th><a id="click_on_firme_sunate" class="click_on_sortby" href="#" onclick="$('#sortby').val('firme_sunate');show_user_statistics();"> Nr firme sunate</a></th>
                <th><a id="click_on_discutii" class="click_on_sortby" href="#" onclick="$('#sortby').val('discutii');show_user_statistics();">Discutii avute</a></th>
                <!--<th><a id="click_on_interesate" class="click_on_sortby" href="#" onclick="$('#sortby').val('interesate');show_user_statistics();">Firme interesate</a></th>-->
                <th><a id="click_on_vrea_contract" class="click_on_sortby" href="#" onclick="$('#sortby').val('vrea_contract');show_user_statistics();">Vor contract</a></th>
                <th>Medie discutii/zi</th>
            </tr>
            </thead>
            <tbody id="user_statistics_tbody">
            
            </tbody>
        </table>
        <input type="hidden" id="sortby" value="firme_sunate">
        <input type="hidden" id="sortdir" value="desc">
    </div>
</section>
    <script>
        $(document).ready(function() {
            show_user_statistics();

        });
        function show_user_statistics(){
            $('#user_statistics_tbody').html('<tr><td colspan="6"><div class="loader"></div></td></tr>');
            $('.click_on_sortby').removeClass('sortby_selected');
            sortby=$('#sortby').val();
            if (sortby != ''){
                $('#click_on_'+sortby).addClass('sortby_selected');
                $('#click_on_'+sortby).off();
            }
            sortdir=$('#sortdir').val();
            user_id=$('#select_user').val();
            start_date=$('#start_date').val();
            end_date=$('#end_date').val();
            $.ajax (
                    {
                        url : "ajax.php",
        
                        data : { action: 'get_user_statistics',user_id:user_id,start_date:start_date,end_date:end_date,sortby:sortby,sortdir:sortdir},
        
                        complete : function (xhr, result){
                            var response = xhr.responseText;
                            $('#user_statistics_tbody').html(response);
                        }
                    });
        }
    </script>
