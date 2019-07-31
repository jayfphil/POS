  $(".sideclick").click(function(e) {
    e.preventDefault();
    
    if ($('#wrapper').hasClass('toggled') || $($(this).attr("href")).hasClass("active")) {
      $("#wrapper").toggleClass("toggled");
      $(".sb_label").toggleClass("sidebar-brand");
    }
    
  });

  $(".sideclose").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
    $(".sb_label").toggleClass("sidebar-brand");
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = this.href.split('#');
      $('.nav a').filter('a[href="#'+target[1]+'"]').tab('show');
  })