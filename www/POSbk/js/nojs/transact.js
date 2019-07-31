(function ($) {
 "use strict";
  
  $(document).ready(function() {

    var datatable = $('#data-table-transact').DataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
        "bAutoWidth": false,
        "columns": [
          {
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": ''
          },
          { "name": "name" },
          { "name": "code" },
          { "name": "price" },
          { "name": "discount" },
          { "name": "action" },
          { "name": "addons" }
        ],
        "columnDefs": [
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            }
        ],
    } );

    datatable.on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row( tr );
     
        if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        }
        else {

          row.child( format(row.data()) ).show();
          tr.addClass('shown');
  
        }
      } );

    var datatable_pending = $('#data-table-pending').DataTable( {
        "pageLength": 5,
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    } );

    $('#data-table-inventory, #data-table-category, #data-table-customers').DataTable( {
        "pageLength": 5,
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    } );

    $('.addtolist').on('click', function () {
       
        auto_clone($(this),datatable);
 
    });

    $('#disc_rate').on('blur', function () {
  
        if ($(this).val() > 100){
          $(this).val('100');
        }

        if ($(this).val() < 0){
          $(this).val('0');
        }
 
    });

    $('#apply_discount').on('click', function () {

        var drate = $("#disc_rate").val();
        var drow = $("#disc_rate").attr("disc-rowno");
        var totalamt = parseFloat($("#totalamt").text());
        var amountdue = parseFloat($("#amountdue").text());

        if($("#disc_rate").val()==null || $("#disc_rate").val()==="") {
            var drate =0;
        }

        if(drow=="all") {

          if(!totalamt) {
              alert("Please select an order!");
              return false;
          }

          var eq_amt = amountdue-totalamt;
          var discounted = disc_calc(drate,totalamt);
          var set_discountall = $("#discount_allval").text(drate+"%");

        } else {

          var origprice = parseFloat($('#hiddenclear').find("input[name='products["+drow+"][price]']").val());
          var eq_amt = amountdue-origprice;

          $("a[data-discrow='"+drow+"']").html("<b>"+drate+"%</b>");
          
          var discounted = disc_calc(drate,origprice);

          $('#hiddenclear').find("input[name='products["+drow+"][discount]']").val(drate).attr("data-discount",discounted['mult']);
          $("#discount_"+drow).text(customFixed(discounted['amount']));

        }

        if(eq_amt<0) {
          $("#amountdue").text("0");
        } else {
          $("#amountdue").text(customFixed(eq_amt));
        }

        var amountdue = parseFloat($("#amountdue").text()); // gets the latest then added with discount
        $("#amountdue").text(customFixed(amountdue+discounted['amount']));
        
        $("#discounts_modal").find("button[data-dismiss='modal']").click();
 
    });

    $('#form_transaction').on('submit', function (e) {

      e.preventDefault();

      var message = 'You want to submit this sale? This cannot be undone.';
      var title = 'Confirm Payment';
      var cashtend = parseFloat($("#cashtend").text());
      var totalamt = parseFloat($("#totalamt").text());
      var amountdue = parseFloat($("#amountdue").text());
      var discount_all = parseFloat($("#discount_allval").text());

      if(!totalamt) {

        alert("Please select an order!");
        return false;

      }

      if(cashtend && cashtend >= amountdue) {
            
            var r = confirm(message);
            if (r == true) {

              var yesthis = $(this);

              $.ajax({
                  type: 'post',
                  url: 'api.php',
                  data: $('#form_transaction').serialize()+ "&cashtend="+cashtend+"&totalamt="+totalamt+"&amountdue="+amountdue+"&discount_all="+discount_all,
                  dataType: 'json',
                  success: function (result) {

                    if(result.success=1) {

                      yesthis.remove();
                      alert("Transaction Complete!");
                      
                      window.open('sticker_print.php?receiptno='+result.last_id, '_blank');
                      window.location = 'pos.php';

                    }
                   
                  }
              });

            } 

      } else {

        alert("Insufficient Cash!");
        return false;

      }

    });

    $('#printsample').on('click', function () {

        $('#printme').show();
        var printme = document.getElementById('printme');
        
        var wme = window.open("","","width=900,height=700");
        wme.document.write(printme.outerHTML);
        wme.document.close();
        wme.focus();
        wme.print();
        wme.close();
        $('#printme').hide();

    });

    $('.modals').on('click', function () {

      // swal("Maintenance :","Not available at the moment!", "info");
      $('#other_modal').modal({show:true});

    });

    $(document).on('click', '.done_order, .void_order, .user_logout',function (e) {

      e.preventDefault();
      var $target = $(this).closest("tr");
      $("#confirm_modal").find("#confirmsubmit").attr("disabled",false);

      if($(this).hasClass("done_order")) {
        
        var message = 'Are you sure it is completed?';
        var title = 'Confirm Order';
        var modal_type = 'done';

      }

      if($(this).hasClass("void_order")) {

        if($(this).attr("data-user")==3) {
         
            $("#confirm_modal").find("#confirmsubmit").attr("disabled",true);

            var message = 'Enter Password : <input type="password" id="incharge_pass"> <button id="incharge_key" class="btn btn-info">Ok</button>';
            var title = 'Confirm to Team Leader or Admin';
            var modal_type = 'void';
        } else {
            var message = 'Are you sure you want to void this transaction?';
            var title = 'Confirm Void';
            var modal_type = 'void';
        }

      }

      if($(this).hasClass("user_logout")) {
        
        var message = 'Are you sure you want to log out?';
        var title = 'Logging Out';
        var modal_type = 'logout';

      }

        $("#confirm_modal h5").text(title);
        $("#confirm_modal .modal-body").html(message);
        $("#confirmsubmit").attr("data-type",modal_type);
        $("#confirmsubmit").attr("data-id",$(this).attr("data-id"));
        $target.addClass("datatable_mark_"+$(this).attr("data-id"));
       
    });

    $(document).on('click', '#confirmsubmit',function (e) {

        var datatype = $(this).attr("data-type");

        if(datatype=="logout") {

          window.location = 'login.php?logout=1';

        } else {

            if(datatype=="done") {
              var databoolean = "done="+$(this).attr("data-id");
              var datamsg = "Ordered has successfully complete!";
              datatable_pending.row($(".datatable_mark_"+$(this).attr("data-id")).closest("tr")).remove().draw( false ); 
              window.open('sticker_print.php?productno='+$(this).attr("data-id"), '_blank');
            }

            if(datatype=="void") {
              var databoolean = "void="+$(this).attr("data-id");
              var datamsg = "Transaction has successfully voided!";
              datatable_pending.row($(".datatable_mark_"+$(this).attr("data-id")).closest("tr")).remove().draw( false ); 
            }
            
            $.ajax({
                  type: 'post',
                  url: 'api.php',
                  data: databoolean,
                  dataType: 'json',
                  success: function (result) {

                    if(result.success=1) {

                      // alert(datamsg);
                      $("#pending_count").text(parseFloat($("#pending_count").text())-1);
                      

                    }
                   
                  }
              });

        }

    });

    $(document).on('click', '.discitem',function (e) {

      e.preventDefault();
      $("#disc_rate").val("");

      if($(this).hasClass("calc-button")) {

        $('#discounts_modal').find("label[for='disc_rate']").text("Discount All Items");
        $('#discounts_modal').find("#disc_rate").attr("disc-rowno","all");

      } else {

        $('#discounts_modal').find("label[for='disc_rate']").text("Discount Item No. "+(parseFloat($(this).attr("data-discrow"))+1)+" : "+$(this).attr("data-name"));
        $('#discounts_modal').find("#disc_rate").attr("disc-rowno",$(this).attr("data-discrow"));

      }
       
    }); 

    $(document).on('click', '.dropitem',function (e) {

      e.preventDefault();
      remove_clone($(this),datatable);
       
    }); 

    $(document).on('change', '.onchange_free',function (e) {

      var totalamt = parseFloat($("#totalamt").text());
      var amountdue = parseFloat($("#amountdue").text());

      var split_val = $(this).val().split("-");
      var stock_free = $(this).attr("data-freestock");
      var split_free = $(this).attr("data-freecompare").split("-");
      var addons_set = $(".addonsqty-"+split_val[0]).first();
      var addons_cnt = $(".addonsqty-"+split_val[0]);

      // if(parseFloat(addons_set.text())==0) {
      //     alert("Out of Stock!");

      //     $(this).val($(this).attr("data-freecompare"));
      //     $(this).change();

      //     return false;
      // }

      // Bug
      if($(this).attr("data-freecompare")!=$(this).val()) {
          addons_cnt.text(parseFloat(addons_set.text())-1);
      }

      if(stock_free) {
          var split_stock = stock_free.split("-");
          var addons_setfree = $(".addonsqty-"+split_stock[0]).first();
          var addons_cntfree = $(".addonsqty-"+split_stock[0]);
          addons_cntfree.text(parseFloat(addons_setfree.text())+1);
      }

      if(split_val[1]>split_free[1]) {

          var freevalue=parseFloat(split_val[1])-parseFloat(split_free[1]);

          if(stock_free) {
            totalamt = free_addons(totalamt,stock_free);
            amountdue = free_addons(amountdue,stock_free);
          }

          $(this).attr("data-freestock",split_val[0]+"-"+freevalue);
          $("."+$(this).attr("id")).val(split_val[0]+"-"+freevalue);
          $("span."+$(this).attr("id")).css("display","inline").text("+"+freevalue);

          totalamt = totalamt + freevalue;
          amountdue = amountdue + freevalue;

      } else {
        if(stock_free) {
            totalamt = free_addons(totalamt,stock_free);
            amountdue = free_addons(amountdue,stock_free);
            $(this).attr("data-freestock",null);
            $("."+$(this).attr("id")).val(split_val[0]+"-0");
            $("span."+$(this).attr("id")).css("display","none").text("+0");
        }
      }

      $("#totalamt").text(customFixed(totalamt));
      $("#amountdue").text(customFixed(amountdue));
       
    });

    $(document).on('click', '#incharge_key',function (e) {

        $.ajax({
            type: 'get',
            url: 'api.php',
            data: "password="+$("#incharge_pass").val(),
            dataType: 'json',
            success: function (result) {
           
              if(result.success==1) {

                $("#confirm_modal").find("#confirmsubmit").attr("disabled",false);
                $("#confirm_modal h5").text("Confirm Void");
                $("#confirm_modal .modal-body").html("Are you sure you want to void this transaction?");

              } else {
                $("#confirm_modal .modal-body").html('Enter Password : <input type="password" id="incharge_pass"> <button id="incharge_key" class="btn btn-info">Ok</button> <span style="display:inline;" class="badge badge-warning">Invalid Password!</span>');
              }
             
            }
        });
        
         
      });

    $(document).on('click', '.addons_exec',function (e) {

      var totalamt = parseFloat($("#totalamt").text());
      var amountdue = parseFloat($("#amountdue").text());

      var addsons_id = $(this).attr("data-id");
      var addons_set = $(".addonsqty-"+addsons_id).first();
      var addons_cnt = $(".addonsqty-"+addsons_id);
      var addson_srp = parseFloat($("#addonsprice-"+addsons_id).text());

      if($(this).prop("checked")) {

        // if(parseFloat(addons_set.text())==0) {
        //   alert("Out of Stock!");
        //   return false;
        // }
      
        addons_cnt.text(parseFloat(addons_set.text())-1);
        totalamt = totalamt + addson_srp;
        amountdue = amountdue + addson_srp;
      } else {
        addons_cnt.text(parseFloat(addons_set.text())+1);
        totalamt = totalamt - addson_srp;
        amountdue = amountdue - addson_srp;
      }
      
      $("#totalamt").text(customFixed(totalamt));
      $("#amountdue").text(customFixed(amountdue));
       
    });

  });

    // function cuteHide(el) {
    //   el.animate({opacity: '0'}, 150, function(){
    //     el.animate({height: '0px'}, 150, function(){
    //       el.remove();
    //     });
    //   });
    // }

    function free_addons(totalamt,freestock){

        var split_stock = freestock.split("-");
        totalamt = totalamt - parseFloat(split_stock[1]);
        return totalamt;

    }

    function remove_clone(content,datatable){

      var cnt = parseFloat($('#hiddenclear').find("#"+content.attr("data-id")).val())-1;
      var oprice = remove_discounted = parseFloat($('#hiddenclear').find("input[name='products["+content.attr("data-row")+"][price]']").val());

      if($("input[name*='products["+content.attr("data-row")+"][discount]']").attr("data-discount")) {
          var remove_discounted = remove_discounted - parseFloat($("input[name*='products["+content.attr("data-row")+"][discount]']").attr("data-discount"));
      }

      counts(content.attr("data-id"),cnt);
      var totalamt = parseFloat($("#totalamt").text()) - oprice;
      var amountdue = parseFloat($("#amountdue").text()) - remove_discounted;

      if($("#free"+content.attr("data-row")+"-"+content.attr("data-id")).attr("data-freestock")) {
          var split_deduct = $("#free"+content.attr("data-row")+"-"+content.attr("data-id")).attr("data-freestock").split("-");
          totalamt = totalamt-split_deduct[1];
          amountdue = amountdue-split_deduct[1];
      }

      if($("input[name*='addons["+content.attr("data-row")+"]["+content.attr("data-id")+"]']:checked").length > 0) {
          $( "input[name*='addons["+content.attr("data-row")+"]["+content.attr("data-id")+"]']:checked" ).each(function( index ) {
            var addson_srp = parseFloat($("#addonsprice-"+$(this).attr("data-id")).text());
            totalamt = totalamt-addson_srp;
            amountdue = amountdue-addson_srp;
          });
      }

      var $target = content.closest("tr");
      $('#hiddenclear').find(".hidden_"+content.attr("data-row")+"_"+content.attr("data-id")).remove();
      datatable.row($target).remove().draw( false ); 

      $("#totalamt").text(customFixed(totalamt));
      $("#amountdue").text(customFixed(amountdue));
      $("#"+content.attr("data-count")).text(parseFloat($("#"+content.attr("data-count")).text())+1);

    }

    function auto_clone(content,datatable){

        var $clone = content.clone();

        $.ajax({
            type: 'get',
            url: 'api.php',
            data: "product_id="+$clone.attr("data-id"),
            dataType: 'json',
            success: function (result) {

              if($('#tablerow').length > 0) {
                var tb_row = parseFloat($('#tablerow').val())+1;
                $('#tablerow').val(tb_row);
              }
              else {
                var tb_row = 0;
                $('#hiddenclear').append("<input type='hidden' id='tablerow' value='0'>");
              }

              if($('#hiddenclear').find("#"+$clone.attr("data-id")).length > 0) {
                var cnt = parseFloat($('#hiddenclear').find("#"+$clone.attr("data-id")).val())+1;
                counts($clone.attr("data-id"),cnt);
              } else {
                var cnt = 1;
                $('#hiddenclear').append("<input type='hidden' id='"+$clone.attr("data-id")+"' class='hidden_"+tb_row+"_"+$clone.attr("data-id")+"' value='"+cnt+"'>");
              }

              var qty = parseFloat(result.products.quantity);
              var flag = null;

              $('#hiddenclear').append("<input type='hidden' name='products["+tb_row+"][price]' class='hidden_"+tb_row+"_"+$clone.attr("data-id")+"' value='"+$clone.attr("data-price")+"'><input type='hidden' name='products["+tb_row+"][identifier]' class='hidden_"+tb_row+"_"+$clone.attr("data-id")+"' value='"+$clone.attr("data-id")+"'><input type='hidden' class='hidden_"+tb_row+"_"+$clone.attr("data-id")+"' name='products["+tb_row+"][discount]' value='0'>");
              var total_p = parseFloat($clone.attr("data-price")); 
              var totalamt = parseFloat($("#totalamt").text()) + total_p;

              // if(cnt > qty) {
              //   alert("Out of Stock!");
              //   return false;
              // }

              if(result.addons) {
                flag=result.addons;
              }

              // Create dom element
              datatable.row.add( [
                    null,
                    $clone.attr("data-name"),
                    $clone.attr("data-code"),
                    "<span id='discount_"+tb_row+"'>"+customFixed($clone.attr("data-price"))+"</span>",
                    "<a href='#' class='discitem' data-toggle='modal' data-target='#discounts_modal' data-discrow='"+tb_row+"' data-name='"+$clone.attr("data-name")+"'><b>"+0+"%</b></a>",
                    "<a href='#' class='dropitem' data-free='"+result.products.addons_flag+"' data-row='"+tb_row+"' data-count='"+$clone.attr("data-count")+"' data-id='"+$clone.attr("data-id")+"'><img src='images/cross.png' title='Remove' class='img-responsive'></a>",
                    flag
              ] ).draw( false );

              $("#amountdue").text(customFixed(totalamt));
              $("#totalamt").text(customFixed(totalamt));
              
              $("#"+$clone.attr("data-count")).text(parseFloat($("#"+$clone.attr("data-count")).text())-1);
             
            }
        });

    }

    function counts(ids,count) {

        $('#hiddenclear').find("#"+ids).val(count);
        $('#data-table-transact > tbody:last-child').find("#cnt_"+ids).parent().html("<input type='hidden' id='cnt_"+ids+"'>"+count);
        // $('#data-table-transact > tbody:last-child').find("#totalp_"+ids).parent().html("<input type='hidden' id='totalp_"+ids+"' value='"+total+"'>"+total.toFixed(2));
    
    }

    function disc_calc(disc,amount) {
        
      var disc_details = new Object();
      var dec = (parseFloat(disc) / 100); //its convert 10 into 0.10
      var mult = amount * dec; // gives the value for subtract from main 
        disc_details['amount'] = amount-mult;
        disc_details['mult'] = mult;

      return disc_details;
    }

    var format = function( d ) {
      var product_id = $($.parseHTML(d[5])).filter('a').attr('data-id');
      var row_number = $($.parseHTML(d[5])).filter('a').attr('data-row');
      var free = $($.parseHTML(d[5])).filter('a').attr('data-free');

      var here = "";
      var stack_free=0;
      var stack_flag=false;

      if(d[6].length>0) {

        var addons = d[6];

        for(var n in addons) { 

            if(addons[n].id==free) {
              stack_free=free+"-"+addons[n].price;
              stack_flag=true;
            }

        }

        here += '<div class="col-sm-6"><div class="row align-items-center p-1">' +
                      '<div class="col-sm-4">Sugar Level</div>'+
                      '<div class="col-sm-8"><select name="products['+row_number+'][sugar_level]"><option>No Sugar</option><option>25% Sugar</option><option>50% Sugar</option><option>75% Sugar</option><option>100% Sugar</option></select></div>'+
                    '</div></div>';

        if(stack_flag) {

            here += '<div class="col-sm-6"><div class="row align-items-center p-1">' +
                        '<div class="col-sm-4">Free Addons</div>'+
                        '<div class="col-sm-8"><select id="free'+row_number+'-'+product_id+'" data-freecompare="'+stack_free+'" class="onchange_free">';
                
            for(var c in addons) { 

                if(addons[c].id==free) { 
                    here += '<option selected value="'+addons[c].id+'-'+addons[c].price+'">'+addons[c].addons_name+'</option>';
                } else {
                    here += '<option value="'+addons[c].id+'-'+addons[c].price+'">'+addons[c].addons_name+'</option>';
                }

            }
            
            here += '</select> <span style="display:none;" class="badge badge-warning free'+row_number+'-'+product_id+' "></span><input type="hidden" class="free'+row_number+'-'+product_id+'" name="addons['+row_number+']['+product_id+'][free]" value="'+addons[c].id+'-0">'; 
            here += '</div></div></div>';

        } else {
            here += '<div class="col-sm-6">&nbsp;</div>';
        }

        for(var i in addons) { 

            if($(".addonsqty-"+addons[i].id).length > 0) {
                addons[i].quantity = $(".addonsqty-"+addons[i].id).first().text();
            }

            here += '<div class="col-sm-4"><div class="row align-items-center p-1">' +
                      '<div class="col-sm-3"><label class="switch"><input type="checkbox" class="addons_exec" data-id="'+addons[i].id+'" name="addons['+row_number+']['+product_id+']['+addons[i].id+']" value="'+addons[i].price+'"><span class="slider round"></span></label></div>'+
                      '<div class="col-sm-2"><span id="addonsprice-'+addons[i].id+'">' + customFixed(addons[i].price) + '</span> </div>'+
                      '<div class="col-sm-7">' + addons[i].addons_name + '</div>'+
                    '</div></div>';

            // <span style="display:inline !important;" class="badge badge-warning addonsqty-'+addons[i].id+'">'+addons[i].quantity+'</span>
        }

        here = '<div class="addons-fonts container"><div class="row">'+here+'</div></div>';
      }
      
      return here;
    };

    $('#amountdue').bind("MutationObserver",function(){
      
      var discount_allval = parseFloat($("#discount_allval").text());
      // var amount_val = $(this).val();

      // if(discount_allval>0) {

        var recalc_amount = 0;
        for (var i = 0; i <= $("#tablerow").val(); i++) {

          if($("input[name*='products["+i+"][price]'").length > 0) {

              var pricey = parseFloat($("input[name*='products["+i+"][price]'").val());
              if($("input[name*='products["+i+"][discount]'").val() > 0) {
                  var discitem = $("input[name*='products["+i+"][discount]'").val();
                  var get_discounted = disc_calc(discitem,$("input[name*='products["+i+"][price]'").val());
                  var remove_discounted = $("input[name*='products["+i+"][price]'").val() - get_discounted['mult'];
                  var pricey = remove_discounted;
              }
              if($("input[name*='addons["+i+"]']:checked").length > 0) {
                  $( "input[name*='addons["+i+"]']:checked" ).each(function( index ) {
                    var addson_srp = parseFloat($("#addonsprice-"+$(this).attr("data-id")).text());
                    pricey = pricey+addson_srp;
                  });
              }
              if($("select[id*='free"+i+"']").attr("data-freestock")) {
                  var split_deduct = $("select[id*='free"+i+"']").attr("data-freestock").split("-");
                  pricey = pricey+parseFloat(split_deduct[1]);
              }

              recalc_amount+=parseFloat(pricey);
              
          }

        }

        var get_discountedall = disc_calc(discount_allval,recalc_amount);
        
        var old_val = parseFloat($("#amountdue").text());
        var new_val = parseFloat(get_discountedall['amount']);
        
        if(old_val) {

          if(customFixed(old_val)!=customFixed(new_val)) {
            $("#amountdue").text(customFixed(get_discountedall['amount']));
          }

        }
        
      // }

    });

    function customFixed(num) {

        if(num % 1 != 0 && num) {
            var with2Decimals = num.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0];
        } else {
            var with2Decimals = parseFloat(num).toFixed(2);
        }
        
        return with2Decimals;
    }
 
})(jQuery); 