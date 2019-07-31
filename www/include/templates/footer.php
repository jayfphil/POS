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
    <script src="./js/jquery-idleTimeout-1.0.8/store.js-master/dist/store.everything.min.js" type="text/javascript"></script>
    
    <script src="./js/jquery-idleTimeout-1.0.8/jquery-idleTimeout.min.js" type="text/javascript"></script>
</body>

</html>

<script type="text/javascript" charset="utf-8">
      $(document).ready(function (){
        $(document).idleTimeout({
          idleTimeLimit: 1200,       // 'No activity' time limit in seconds. 1200 = 20 Minutes
          redirectUrl: './login.php',    // redirect to this url on timeout logout. Set to "redirectUrl: false" to disable redirect

          // optional custom callback to perform before logout
          customCallback: false,     // set to false for no customCallback
          // customCallback:    function () {    // define optional custom js function
              // perform custom action before logout
          // },

          // configure which activity events to detect
          // http://www.quirksmode.org/dom/events/
          // https://developer.mozilla.org/en-US/docs/Web/Reference/Events
          activityEvents: 'click keypress scroll wheel mousewheel mousemove', // separate each event with a space

          // warning dialog box configuration
          enableDialog: true,        // set to false for logout without warning dialog
          dialogDisplayLimit: 180,   // time to display the warning dialog before logout (and optional callback) in seconds. 180 = 3 Minutes
          dialogTitle: 'Session Expiration Warning',
          dialogText: 'Because you have been inactive, your session is about to expire.',

          // server-side session keep-alive timer
          sessionKeepAliveTimer: 600 // Ping the server at this interval in seconds. 600 = 10 Minutes
          // sessionKeepAliveTimer: false // Set to false to disable pings
        });
      });
        </script>