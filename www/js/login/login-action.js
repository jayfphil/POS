(function ($) {
 "use strict";

	$("body").on("click", "[data-ma-action]", function(e) {
        e.preventDefault();
        var $this = $(this),
            action = $(this).data("ma-action");
        switch (action) {
            case "nk-login-switch":
                var loginblock = $this.data("ma-block"),
                    loginParent = $this.closest(".nk-block");
                loginParent.removeClass("toggled"), setTimeout(function() {
                    $(loginblock).addClass("toggled");
                });
            break;
			case "logged":
                // window.print();
                window.location = 'index.php'
            break;
        }
    });
 
})(jQuery); 