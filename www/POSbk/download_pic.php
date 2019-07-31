<html>
	<head>
		<style>
			#mydiv {
			    background-color: lightblue;
			    width: 200px;
			    height: 200px
			}
		</style>
	</head>
	<body>
		
		<div id="mydiv">
		    <img src="http://fiddle.jshell.net/img/logo-white.png" />
		    <p>text!</p>
		</div>
		<br>
		<br>
	    
		<div id="canvas">
	    	<p>Canvas:</p>
	    </div>
	    
	    <div id="image">
	        <p>Image:</p>
	    </div>


	    <script>
	    	html2canvas([document.getElementById('mydiv')], {
			    onrendered: function (canvas) {
			        document.getElementById('canvas').appendChild(canvas);
			        var data = canvas.toDataURL('image/png');
			        // AJAX call to send `data` to a PHP file that creates an image from the dataURI string and saves it to a directory on the server

			        var image = new Image();
			        image.src = data;
			        document.getElementById('image').appendChild(image);
			    }
			});

	    </script>

	</body>
</html>
