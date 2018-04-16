<script>
$( function() {
            $( "#start_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_user_statistics_contract();

                }
            });
            $( "#end_date" ).datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:"+0d",
                onSelect: function (dateText, inst) {
                    $(this).datepicker('hide');
                    show_user_statistics_contract();
                }
            });
        } );
</script>
<section class="section">
<ul class="nav nav-pills nav-justified">
  <li ><a href="statistics.php">General</a></li>
  <li class="active"><a href="#">Contracte</a></li>
</ul>
<br>
    <div class="row">
        <div class="col-md-4">
            <select class="form-control" id="select_user" name="select_user" onchange="show_user_statistics_contract()">
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
                <th>Stadiu cerere</th>
                <th>Nr Free</th>
                <th>Val Free</th>
                <th>Val Telefon</th>
                <th>Bo</th>
                <th>S Fixe</th>
                <th>Rate</th>
                <th>Cloud</th>                
            </tr>
            </thead>
            <tbody id="user_statistics_tbody">
            
            </tbody>
        </table>
    </div>
</section>
    <script>
        $(document).ready(function() {
            show_user_statistics_contract();

        });
        function show_user_statistics_contract(){
            $('#user_statistics_tbody').html('<tr><td colspan="6"><div class="loader"></div></td></tr>');
            user_id=$('#select_user').val();
            start_date=$('#start_date').val();
            end_date=$('#end_date').val();
            $.ajax (
                    {
                        url : "ajax.php",
        
                        data : { action: 'get_user_statistics_contract',user_id:user_id,start_date:start_date,end_date:end_date},
        
                        complete : function (xhr, result){
                            var response = xhr.responseText;
                            $('#user_statistics_tbody').html(response);
                        }
                    });
        }
    </script>
