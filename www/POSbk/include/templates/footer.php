<!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2019. All rights reserved. Jabba Programming.</p>
                        <?php
                            $bmtime = microtime();
                            $bmtime = explode(' ', $bmtime);
                            $bmtime = $bmtime[1] + $bmtime[0];
                            $bmfinish = $bmtime;
                            global $bmstart;
                            $total_time = round(($bmfinish - $bmstart), 4);
                            echo "<p><small>Page generated in $total_time seconds.</small></p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer area-->
    <!-- jquery
		============================================ -->
    <!-- <script src="./js/vendor/jquery-1.12.4.min.js"></script> -->

    <script src="./js/jquery-1.12.4.js"></script>
    <script src="./js/jquery-ui.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="./js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="./js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="./js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="./js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="./js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="./js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="./js/counterup/jquery.counterup.min.js"></script>
    <script src="./js/counterup/waypoints.min.js"></script>
    <!-- <script src="./js/counterup/counterup-active.js"></script> -->
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="./js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="./js/sparkline/jquery.sparkline.min.js"></script>
    <script src="./js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="./js/flot/jquery.flot.js"></script>
    <script src="./js/flot/jquery.flot.resize.js"></script>
    <script src="./js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="./js/knob/jquery.knob.js"></script>
    <script src="./js/knob/jquery.appear.js"></script>
    <script src="./js/knob/knob-active.js"></script>
        <!-- icheck JS
        ============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!--  Chat JS
		============================================ -->
    <script src="./js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="./js/todo/jquery.todo.js"></script>
	<!--  wave JS
		============================================ -->
    <script src="./js/wave/waves.min.js"></script>
    <script src="./js/wave/wave-active.js"></script>
    <!--  notification JS
        ============================================ -->
    <script src="js/notification/bootstrap-growl.min.js"></script>
    <!--  Chat JS
        ============================================ -->
    <script src="js/dialog/sweetalert2.min.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="./js/plugins.js"></script>
    <!-- Data Table JS
		============================================ -->
    <script src="./js/data-table/jquery.dataTables.min.js"></script>
    <script src="./js/data-table/data-table-act.js"></script>
    <!-- main JS
		============================================ -->
    <script src="./js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <!-- <script src="js/tawk-chat.js"></script> -->
</body>

</html>