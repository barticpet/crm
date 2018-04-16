<?php
$menu='users';
include_once 'header.php';
    if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2) {
?>

    <section class="section">
        <button data-id="0" class="btn btn-primary btn-md pop" pageTitle="Add user" onclick="show_popup(this);" pageName="include/popup/add_user.html">Adauga User</button>
        <div id="users_list_div" ></div>
    </section>
    <script>
        $(document).ready(function() {
            show_users();
        } );
    </script>

<?php
}
include_once 'footer.php';
?>

