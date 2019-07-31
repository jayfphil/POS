<?php
	session_start(); 
    require './include/config_pos.php';
    
?>
<html>
	<head>
		<script type="text/javascript" src="js\html2canvas.min.js"></script>
	</head>
	<body>

		<div id="capture" style="padding: 10px; background: #f5da55">
		    <h4 style="color: #000; ">Hello world!</h4>
		</div>

		<?php 

	        $result_items = PDO_FetchAll("SELECT * FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`ti_voided` IS NULL AND a.`transact_id` = 4");
	        if(count($result_items) > 0) {
	            
	                        foreach ($result_items as $row_items) {

	                            ?>

	                                <tr class="service">
	                                    <th class="tableitem"><p class="itemtext"><?php echo $row_items['product_codetemp']; ?></p></th>
	                                    <th class="tableitem"><p class="itemtext"><?php echo number_format($row_items['price'], 2); ?></p></th>
	                                    <th class="tableitem"><p class="itemtext"><?php echo $row_items['quantity']; ?></p></th>
	                                    <th class="tableitem"><p class="itemtext"><?php echo number_format(($row_items['price']*$row_items['quantity']), 2); ?></p></th>
	                                </tr>

	                            <?php

	                        }
	                 
	        }

	    ?>

	    <script>
	    	html2canvas(document.querySelector("#capture")).then(canvas => {
			    document.body.appendChild(canvas)
			    download(canvas, 'myimage.png');
			});

			function download(canvas, filename) {
				  /// create an "off-screen" anchor tag
				  var lnk = document.createElement('a'), e;

				  /// the key here is to set the download attribute of the a tag
				  lnk.download = filename;

				  /// convert canvas content to data-uri for link. When download
				  /// attribute is set the content pointed to by link will be
				  /// pushed as "download" in HTML5 capable browsers
				  lnk.href = canvas.toDataURL("image/png;base64");

				  /// create a "fake" click-event to trigger the download
				  if (document.createEvent) {
				    e = document.createEvent("MouseEvents");
				    e.initMouseEvent("click", true, true, window,
				                     0, 0, 0, 0, 0, false, false, false,
				                     false, 0, null);

				    lnk.dispatchEvent(e);
				  } else if (lnk.fireEvent) {
				    lnk.fireEvent("onclick");
				  }
				}
	    </script>

	</body>
</html>
