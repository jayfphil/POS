   
  </body>


</html>


<script src="js/nojs/jquery-3.3.1.slim.min.js"></script>
<script src="js/nojs/popper-1.14.7.min.js"></script>
<script src="js/nojs/bootstrap-4.3.1.min.js"></script>

<script src="js/nojs/jquery-1.12.4.js"></script>
<script src="js/nojs/jquery-ui-1.12.1.js"></script>
<script src="js/nojs/jquery.dataTables-1.10.19.js"></script>
<script src="js/nojs/calc.js"></script>
<script src="js/nojs/sidebar.js"></script>

<script src="js/nojs/transact.js"></script>
    <script src="./js/jquery-idleTimeout-1.0.8/store.js-master/dist/store.everything.min.js" type="text/javascript"></script>
    <script src="./js/jquery-idleTimeout-1.0.8/jquery-idleTimeout.min.js" type="text/javascript"></script>

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