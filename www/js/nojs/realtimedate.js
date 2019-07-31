// Begin : Realtime Date
      function date_time(id)
      {
              date = new Date;
              year = date.getFullYear();
              month = date.getMonth();
              months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
              d = date.getDate();
              day = date.getDay();
              days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
              h = date.getHours();
              if(h<10)
              {
                      h = "0"+h;
              }

              var am_pm = h >= 12 ? "PM" : "AM";
              h = h % 12;
              h = h ? h : 12; // the hour '0' should be '12'
              
              m = date.getMinutes();
              if(m<10)
              {
                      m = "0"+m;
              }
              s = date.getSeconds();
              if(s<10)
              {
                      s = "0"+s;
              }
              result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s+ " " + am_pm;
              document.getElementById(id).innerHTML = '<div class="alert alert-dark" role="alert">'+result+'</div>';
              setTimeout('date_time("'+id+'");','1000');
              return true;
      }
      // End : Realtime Date