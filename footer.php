<!-- Footer -->
        <footer class="text-center ">
            <div class="footer-above">
                <div class="container">
                    <div class="row">
                        <div class="footer-col col-md-4">
                            <h3>Locatia</h3>
                            <p>Strada mea, Piatra-Neamt
                                <br>Jud Neamt, Romania</p>
                        </div>
                        <div class="footer-col col-md-4">
                            <h3>Around the Web</h3>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="btn-social btn-outline" href="#">
                                        <i class="fa fa-fw fa-facebook"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn-social btn-outline" href="#">
                                        <i class="fa fa-fw fa-google-plus"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn-social btn-outline" href="#">
                                        <i class="fa fa-fw fa-twitter"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn-social btn-outline" href="#">
                                        <i class="fa fa-fw fa-linkedin"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn-social btn-outline" href="#">
                                        <i class="fa fa-fw fa-dribbble"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="footer-col col-md-4">
                            <h3>About NextCall</h3>
                            <p>Nextcall is a free to use, call center created by
                                <a href="http://nextcall.ro">Marher</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-below">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            Copyright &copy; Nextcall.ro 2017
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    <div class="modal draggable fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="width:100%;"></h4>
                <button type="button" class="close close_option" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-save" data-id="" id="btn_save">OK</button>
                <button type="button" class="btn btn-default close_option" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div  id="success_operation"  style="display:none">
        <div class="alert alert-success" role="alert">
            <strong>Finalizat!</strong> Ai incheiat cu succes operatiunea.
        </div>
</div>
<div id="fail_operation" style="display:none">
        <div class="alert alert-danger" role="alert">
            <strong>Problema!</strong> Ceva s-a intamplat in proces. Incearca din nou
        </div>
</div>
<div  id="warning_operation"  style="display:none">
        <div class="alert alert-warning" role="alert" id="warning_content">

        </div>
</div>


<script type="text/javascript" language="javascript" src="<?php echo LIB_PATH; ?>datatables/editor/js/jquery.dataTables.min.js"></script>
        <!--<script type="text/javascript" language="javascript" src="<?php echo LIB_PATH; ?>datatables/editor/js/dataTables.buttons.min.js"></script>-->
        <script type="text/javascript" language="javascript" src="<?php echo LIB_PATH; ?>datatables/editor/js/dataTables.select.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo LIB_PATH; ?>datatables/editor/js/dataTables.editor.min.js"></script>
        <!--<script type="text/javascript" language="javascript" src="<?php echo LIB_PATH; ?>datatables/editor/resources/syntax/shCore.js"></script>-->


<script src="js/jquery-ui.min.js"></script>
<!--<script src="js/jquery-ui-timepicker-addon.js"></script>
<script src="js/jquery-ui-sliderAccess.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script type="text/javascript" src="lib/datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script type="text/javascript">
    get_reminder_text();
    $(document).ready(function(){
    var $nav = $('#mainNav');//Caching element
    // hide .navbar first - you can also do this in css .nav{display:none;}
    //$nav.hide();
      // fade in .navbar
        $(window).scroll(function () {
            // set distance user needs to scroll before we start fadeIn
            if ($(this).scrollTop() > 100) { //For dynamic effect use $nav.height() instead of '100'
                $nav.fadeOut();
            } else {
                $nav.fadeIn();
            }
        });
    });

    $('.modal.draggable>.modal-dialog').draggable({
        cursor: 'move',
        handle: '.modal-header'
    });
    $('.modal.draggable>.modal-dialog>.modal-content>.modal-header').css('cursor', 'move');

   // if (navigator.serviceWorker.controller) {
   // console.log('[PWA Builder] active service worker found, no need to register')
    //} else {
    //Register the ServiceWorker
    //navigator.serviceWorker.register('pwabuilder-sw.js', {
      //  scope: './'
    //}).then(function(reg) {
        //console.log('Service worker has been registered for scope:'+ reg.scope);
    //});
    //}

</script>
    </body>

</html>
