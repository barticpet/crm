<?php
$menu='stoc';
include_once 'header.php';
?>

    <section class="section">
        <div id="stoc_div" ></div>
    </section>
    <script>
        $(document).ready(function() {
            show_stoc();
        } );
        function show_stoc(){
        $('#stoc_div').html();
        $('#stoc_div').html('<div class="demo-html"></div><table id="stoc_table" class="display" cellspacing="0" width="100%"><thead><tr><th width="70%" style="width:70%;">Denumire</th><th>Pret FMC (&euro;)</th><th>Stoc TKR</th><th>Stoc TKRM</th><th width="30">Realim</th></tr></thead></table>');
    
        call_url="include/datatables/lista_stoc.php";
    
        var dt=$('#stoc_table').DataTable( {
            dom: "Bfrtip",
            pageLength:20,
            ajax: {
                url: call_url,
                type: 'POST'
            },
            serverSide: true,
            columns: [
                { data: "stoc.denumire" },
                { data: "stoc.pret_fmc" },
                { data: "stoc.stoc_tkr" },
                { data: "stoc.stoc_tkrm" },
                { data: "stoc.realimentare" }
            ],
            "search": {
                "smart": false
            },
            order: [ 0, 'asc' ],
            select: true
        } )
    
    }
    </script>

<?php
if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2) {
    echo '<a href="admin/stoc/upload.php"><h6>Upload fisier stoc</h6></a><br>';
}
include_once 'footer.php';
?>

