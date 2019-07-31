(function ($) {
 "use strict";
	
	$(document).ready(function() {
		var datatable = $('#data-table-basic1').DataTable( {
		  	"bPaginate": false,
		  	"bLengthChange": false,
		    "bFilter": true,
		    "bInfo": false,
		    "bAutoWidth": false 
		} );
		$('#data-table-basic2').DataTable( {
		  	"pageLength": 5,
		  	// "bPaginate": false,
		  	"bLengthChange": false,
		    "bFilter": true,
		    "bInfo": false,
		    "bAutoWidth": false 
		} );

		$('.tick_delete, .tick_addons').on('click', function () {
			 
			// addtolist auto_clone($(this),datatable);

			var nFrom = $(this).attr('data-from');
			var nAlign = $(this).attr('data-align');
			var nIcons = $(this).attr('data-icon');
			var nType = $(this).attr('data-prompt');
			var nAnimIn = $(this).attr('data-animation-in');
			var nAnimOut = $(this).attr('data-animation-out');

			$.ajax({
	            type: 'post',
	            url: 'api.php',
	            data: "process_id="+$(this).attr("data-id")+"&process_type="+$(this).attr("data-type")+"&process_value="+$(this).prop("checked"),
	            dataType: 'json',
	            success: function (result) {

	            	if(result.success=1) {
						notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut, 'Toggled item successfully!');
	            		// swal("Success","Transaction Complete! (Redirecting you to receipt page in 5 seconds...)", "success");
	            		// setTimeout(function(){window.location = 'receipt.php?trans_no='+result.last_id}, 5000);
	            	}
	             
	            }
			});
			 
		});

		$('#paysub').on('click', function () {

			var cashtend = parseFloat($("#cashtend").val());
			var totalamt = parseFloat($("#totalamt").val());

			var nFrom = $(this).attr('data-from');
			var nAlign = $(this).attr('data-align');
			var nIcons = $(this).attr('data-icon');
			var nType = $(this).attr('data-type');
			var nAnimIn = $(this).attr('data-animation-in');
			var nAnimOut = $(this).attr('data-animation-out');

			if(cashtend && cashtend >= totalamt) {

				$('form').on('submit', function (e) {

				  e.preventDefault();

				  	swal({   
						title: "Are you sure?",   
						text: "You want to submit this sale? This cannot be undone.",   
						type: "warning",   
						showCancelButton: true,   
						confirmButtonText: "Ok",
					}).then(function(){
						
						$.ajax({
				            type: 'post',
				            url: 'api.php',
				            data: $('form').serialize(),
				            dataType: 'json',
				            success: function (result) {

				            	if(result.success=1) {
									// notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut, 'Transaction Complete! (Reloading page in 5 seconds...)');
				            		swal("Success","Transaction Complete! (Redirecting you to receipt page in 5 seconds...)", "success");
				            		setTimeout(function(){window.location = 'receipt.php?trans_no='+result.last_id}, 5000);
				            	}
				             
				            }
				        });
						
					});

		        });
				
			} else {
				var nType = "danger";
				notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut, 'Insufficient Cash!');
				return false;
			}
			
		});

		$('#printsample').on('click', function () {

				$('#printme').show();
				var printme = document.getElementById('printme');
				
				// $('#data-table-basic1 th:last-child').remove();
				// $('#data-table-basic1 td:last-child').remove();
				// $("<tfoot><tr><td colspan='2'>"+days[d.getDay()]+' '+months[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear()+' '+hours+':'+minutes+ampm
				// 	+"</td><td colspan='2'>Cash Tendered: PHP "+cashtend+"</td><td colspan='2'>Total Amount: PHP "+totalamt+"</td></tr></tfoot>").insertAfter("#data-table-basic1 > tbody");
				
				// var printstorage = $('#data-table-basic1').clone();
				// printme.find('#data-table-basic1 th:last-child').remove();
				// printme.find('#data-table-basic1 td:last-child').remove();
				// var wrapper = document.createElement('html');
    			// wrapper.append(printstorage.html());

				var wme = window.open("","","width=900,height=700");
				wme.document.write(printme.outerHTML);
				wme.document.close();
				wme.focus();
				wme.print();
				wme.close();
				$('#printme').hide();

			
		});

		$('#printreceipt').on('click', function () {

			swal("Maintenance :","Not available at the moment!", "info");

		});

		$(document).on('click', '.dropmilk',function (e) {

			if($('#hiddenclear').find("#"+$(this).attr("data-id")).val() > 1) {
				var cnt = parseFloat($('#hiddenclear').find("#"+$(this).attr("data-id")).val())-1;
				var total_p = parseFloat($(this).closest("tr").find("#totalp_"+$(this).attr("data-id")).val()) -  parseFloat($(this).closest("tr").find("td:eq(3)").html());
				var totalamt = parseFloat($("#totalamt").val()) - parseFloat($(this).closest("tr").find("td:eq(3)").html());
				counts($(this).attr("data-id"),cnt,total_p);
			} else {
				var totalamt = parseFloat($("#totalamt").val()) - parseFloat($(this).closest("tr").find("#totalp_"+$(this).attr("data-id")).val());	
				var $target = $(this).closest("tr");
				$('#hiddenclear').find("#"+$(this).attr("data-id")).remove();

				// $target.fadeOut(300, function(){ $target.remove();});
				$target.hide('slow', function(){ $target.remove(); });
				// cuteHide($target);
			}

			$("#totalamt").val(totalamt);
			 
		});

		$(document).on('click', '.editaddonsrow, .addaddonsrow', function (e) {

			$("form h2").text($(this).attr("title"));
			$("form").find("input[name='addons_id']").val(0);
         	$("form").find("input[name='addons_name']").val("");
         	$("form").find("input[name='addons_codetemp']").val("");
         	$("form input:checkbox:checked").prop("checked", "").removeClass("checked");
         	$("form").find("input[name='quantity']").val(0);
         	$("form").find("input[name='price']").val(0);

			if($(this).hasClass("editaddonsrow")) {
				$.ajax({
		            type: 'get',
		            url: 'api.php',
		            data: "addons_id="+$(this).attr("data-id"),
		            dataType: 'json',
		            success: function (result) {
		            	$("form").find("input[name='addons_id']").val(result.id);
		             	$("form").find("input[name='addons_name']").val(result.addons_name);
		             	$("form").find("input[name='addons_codetemp']").val(result.addons_codetemp);

		             	if (result.category_text) {

		             		if(result.category_text.includes(', ')) {
		             			var valNew=result.category_text.split(', ');
				             	if(valNew.length>0) {

				             		for(var i in valNew){
								        $("form").find("input[value='"+valNew[i]+"']").prop("checked", "checked").addClass("checked");
								    }
				             		
				             	}
				             } else {
				             	$("form").find("input[value='"+result.category_text+"']").prop("checked", "checked").addClass("checked");
				             }
							
						}
		             	
		             	$("form").find("input[name='quantity']").val(result.quantity);
		             	$("form").find("input[name='price']").val(result.price);
		            }
		        });
			} 
			 
		});

		$(document).on('click', '.edititemrow, .additemrow', function (e) {

			$("form h2").text($(this).attr("title"));
			$("form").find("input[name='product_id']").val(0);
         	$("form").find("input[name='product_name']").val("");
         	$("form").find("input[name='product_codetemp']").val("");
         	$("form").find("select[name='category_id']").val(0);
         	$("form").find("select[name='addons_id']").val(0);
         	$("form").find("select[name='report_category']").val(0);
         	$("form").find("input[name='quantity']").val(0);
         	$("form").find("input[name='price']").val(0);
			$("form").find("select[name='category_id']").change();
			$("form").find("select[name='addons_id']").change();
			$("form").find("select[name='report_category']").change();

			if($(this).hasClass("edititemrow")) {
				$.ajax({
		            type: 'get',
		            url: 'api.php',
		            data: "product_id="+$(this).attr("data-id"),
		            dataType: 'json',
		            success: function (result) {
		            	// console.log(result);
		            	$("form").find("input[name='product_id']").val(result.products.id);
		             	$("form").find("input[name='product_name']").val(result.products.product_name);
		             	$("form").find("input[name='product_codetemp']").val(result.products.product_codetemp);

		             	if(result.products.category_id!=0) {
		             		$(".modal_ptype").show();
		             		$("form").find("select[name='category_id']").val(result.products.category_id);
		             		$("form").find("select[name='category_id']").change();
		             		$("form").find("select[name='addons_id']").val(result.products.addons_flag);
							$("form").find("select[name='addons_id']").change();
							$("form").find("select[name='report_category']").val(result.products.report_category);
							$("form").find("select[name='report_category']").change();
		             		$("form").find("input[name='product_name']").prop("readonly",false);
		             		$("form").find("input[name='price']").prop("readonly",false);
		             	} else {
		             		$(".modal_ptype").hide();
		             		$("form").find("input[name='product_name']").prop("readonly",true);
		             		$("form").find("input[name='price']").prop("readonly",true);
		             	}

		             	$("form").find("input[name='quantity']").val(result.products.quantity);
		             	$("form").find("input[name='price']").val(result.products.price);
		             	
		            }
		        });
			} 
			 
		});

		$(document).on('click', '.editcatrow, .addcatrow', function (e) {

			$("form h2").text($(this).attr("title"));
			$("form").find("input[name='category_id']").val(0);
         	$("form").find("input[name='category_name']").val("");

			if($(this).hasClass("editcatrow")) {
				$.ajax({
		            type: 'get',
		            url: 'api.php',
		            data: "category_id="+$(this).attr("data-id"),
		            dataType: 'json',
		            success: function (result) {
		            	$("form").find("input[name='category_id']").val(result.id);
		             	$("form").find("input[name='category_name']").val(result.category_name);
		            }
		        });
			} 
			 
		});

		$(document).on('click', '.edituserrow', function (e) {

			$("form h2").text($(this).attr("title"));
			$("form").find("input[name='user_id']").val(0);
         	$("form").find("input[name='username']").val("");
         	$("form").find("input[name='password']").val("");
         	$("form").find("input[name='fullname']").val("");

			if($(this).hasClass("edituserrow")) {
				$.ajax({
		            type: 'get',
		            url: 'api.php',
		            data: "user_id="+$(this).attr("data-id"),
		            dataType: 'json',
		            success: function (result) {
		            	$("form").find("input[name='user_id']").val(result.id);
		             	$("form").find("input[name='username']").val(result.username);
		             	$("form").find("input[name='password']").val(result.password);
		             	$("form").find("input[name='fullname']").val(result.fullname);
		            }
		        });
			} 
			 
		});

		$(document).on('click', '.editintrow, .addintrow', function (e) {

			$("form h2").text($(this).attr("title"));
			$("form").find("input[name='ingredients_id']").val(0);
         	$("form").find("input[name='ingredients_name']").val("");
         	$("form").find("input[name='cups_serving']").val(0);
         	$("form").find("input[name='quantity']").val(0);
         	$("form").find("select[name='measurement_type']").val(0);
         	$("form").find("select[name='measurement_type']").change();

			if($(this).hasClass("editintrow")) {
				$.ajax({
		            type: 'get',
		            url: 'api.php',
		            data: "ingredients_id="+$(this).attr("data-id"),
		            dataType: 'json',
		            success: function (result) {
		            	$("form").find("input[name='ingredients_id']").val(result.id);
		             	$("form").find("input[name='ingredients_name']").val(result.ingredients_name);
		             	$("form").find("input[name='cups_serving']").val(result.cups_serving);
		             	$("form").find("input[name='quantity']").val(result.quantity);
			         	$("form").find("select[name='measurement_type']").val(result.measurement_type);
			         	$("form").find("select[name='measurement_type']").change();
		            }
		        });
			} 
			 
		});
			

	});

	/*--------------------------
		 Temporary transaction
		---------------------------- */	

		function cuteHide(el) {
		  el.animate({opacity: '0'}, 150, function(){
		    el.animate({height: '0px'}, 150, function(){
		      el.remove();
		    });
		  });
		}

		function auto_clone(content,datatable){
		    var $clone = content.clone();
		    var cnt = 1;
		    if($('#hiddenclear').find("#"+$clone.attr("data-id")).length > 0) {

		    	var cnt = parseFloat($('#hiddenclear').find("#"+$clone.attr("data-id")).val())+1;
		    	var total_p = parseFloat($clone.attr("data-price")) * cnt;
		    	var totalamt = parseFloat($("#totalamt").val()) + parseFloat($clone.attr("data-price"));
		    	counts($clone.attr("data-id"),cnt,total_p);
		    	
		    } else {
		    	$('#hiddenclear').append("<input type='hidden' name='products["+$clone.attr("data-id")+"][count]' id='"+$clone.attr("data-id")+"' value='1'><input type='hidden' name='products["+$clone.attr("data-id")+"][price]' value='"+$clone.attr("data-price")+"'>");
		    	var total_p = parseFloat($clone.attr("data-price"));
		    	var totalamt = parseFloat($("#totalamt").val()) + total_p;

		    	// Create dom element
		    	// $('#data-table-basic1 > tbody:last-child').append("<tr><td>"+$clone.text()+"</td><td>"+$clone.attr("data-category")+"</td><td><input type='hidden' id='cnt_"+$clone.attr("data-id")+"'>"+cnt+"</td><td>"+parseFloat($clone.attr("data-price"))+"</td><td><input type='hidden' id='totalp_"+$clone.attr("data-id")+"' value='"+total_p+"'>"+total_p+"</td><td><button type='button' class='btn btn-danger danger-icon-notika btn-reco-mg btn-button-mg dropmilk' data-id='"+$clone.attr("data-id")+"'><i class='notika-icon notika-close'></i></button></td></tr>").focusin();
		    	datatable.row.add( [
		            $clone.text(),
		            $clone.attr("data-category"),
		            "<input type='hidden' id='cnt_"+$clone.attr("data-id")+"'>"+cnt,
		            parseFloat($clone.attr("data-price")),
		            "<input type='hidden' id='totalp_"+$clone.attr("data-id")+"' value='"+total_p+"'>"+total_p.toFixed(2),
		            "<button type='button' class='btn btn-danger danger-icon-notika btn-reco-mg btn-button-mg dropmilk' data-id='"+$clone.attr("data-id")+"'><i class='notika-icon notika-close'></i></button>"
		        ] ).draw( false );
		    }
			
			$("#totalamt").val(totalamt.toFixed(2));
		}

		function counts(ids,count,total) {
			$('#hiddenclear').find("#"+ids).val(count);
		    $('#data-table-basic1 > tbody:last-child').find("#cnt_"+ids).parent().html("<input type='hidden' id='cnt_"+ids+"'>"+count);
		    $('#data-table-basic1 > tbody:last-child').find("#totalp_"+ids).parent().html("<input type='hidden' id='totalp_"+ids+"' value='"+total+"'>"+total);
		}

		/*
		 * Notifications
		 */
		function notify(from, align, icon, type, animIn, animOut, msg){
			$.growl({
				icon: icon,
				title: ' Message: ',
				message: msg,
				url: ''
			},{
					element: 'body',
					type: type,
					allow_dismiss: true,
					placement: {
							from: from,
							align: align
					},
					offset: {
						x: 20,
						y: 85
					},
					spacing: 10,
					z_index: 1031,
					delay: 2500,
					timer: 3000,
					url_target: '_blank',
					mouse_over: false,
					animate: {
							enter: animIn,
							exit: animOut
					},
					icon_type: 'class',
					template: '<div data-growl="container" class="alert" role="alert">' +
									'<button type="button" class="close" data-growl="dismiss">' +
										'<span aria-hidden="true">&times;</span>' +
										'<span class="sr-only">Close</span>' +
									'</button>' +
									'<span data-growl="icon"></span>' +
									'<span data-growl="title"></span>' +
									'<span data-growl="message"></span>' +
									'<a href="#" data-growl="url"></a>' +
								'</div>'
			});
		};
 
})(jQuery); 