<!doctype html>
<html lang="en">
	<head>
  		<meta charset="utf-8">
  		<title><?php echo $title;?></title>
		<style>
		/*
		 * 
		 *  CSS 'Constants' 
		 *
		 */

		.right {
		  float:right
		}


		.left {
		 float:left
		}


		.bump{
		  clear:both;
		  margin-top:20px
		}


		.pad{
		  margin-right: 15px
		}


		.clearer{
			width:100%;
			height:10px;
			clear:both
		}

		.clearfix:before, .clearfix:after {  content: "\0020"; display: block; height: 0; visibility: hidden;	 } 
		.clearfix:after { clear: both; }
		.clearfix { zoom: 1; }

	
		
		*{
		  outline: none
		}


		html {
		  overflow-y: scroll
		}


		body {
		  height: 100%;
		  font: 13px/20px "Helvetica Neue", Helvetica, Arial, sans-serif;
		  color: #666;
		  background-color: #eee;
		  margin: auto;
		  margin-top: 20px;
		  width: 576px
		}


		/*
		 * 
		 * Basic Typographic and image elements 
		 *
		 */

		a {
		  color: #999;
		  text-decoration:none
		}

		a:hover {
		  color: #cc0000
		}


		h3, h2, h1 {
		  font-weight: 400
		}


		h1, h1 a{
		  color: #000;
		  padding-top: 10px;
		  margin-bottom: 10px;
		  font-weight: bold
		}


		h3, h2, h3 a, h2 a, h1, h1 a {
		  text-shadow: 0 -1px 0 rgba(0,0,0,0.15),0 1px 0 rgba(255,255,255,0.8)
		}


		h3, h2, h3 a, h2 a {
		  border-bottom: 0px #eee solid;
		  margin-bottom: 5px;
		  color: #555
		}


		h3 {
		  margin-top: 10px;
		  margin-bottom: 20px
		}


		p {
		  color: #777;
		  line-height: 170%
		}

		p a {
		  border-bottom: 1px #999 dashed
		}


		img {
		  text-decoration: none;
		  border: 0
		}

		li, ul, ol {
		  margin: 0;
		  padding: 0;
		  list-style: none
		}

		textarea, input {
		  outline:none
		}

		label {
		  font-weight: bold
		}


		/*
		 * 
		 * Main page elements
		 *
		 */

		/* Page header */
		header {
		  margin-bottom:20px
		}



		/* Blog Post */
		.post {
		  clear:both;
		  margin:auto;
		  margin-bottom: 30px;
		  border: solid 1px #CCC;
		  background-color: white;  
		  border-radius: 5px;
		  padding: 20px;
		  padding-bottom:10px
		}

		.post h2 {
		  margin-top:0px;
		  padding:0;
		  padding-bottom: 5px;
		}


	

		footer {
		  clear: both;
		  padding-top:20px;
		  margin-bottom: 10px;
		  margin-top:20px
		}

		footer p{
		  margin-top:17px;
		}



		/* Text input boxes in the install panel */
		.install_left {
		    float: left;
		    padding-left: 0;
		    text-align: left;
		    width: 120px;
		}

		.install_right {
		    float: left;
		    padding-left: 10px;
		    text-align: left;
		    width: 220px;
		}
			
		.install_left label {
		    display: block;
		    font-size: 15px;
		    height: 45px;
		    margin-top: 2px;
		    width: 140px;
		}
								
		.install_box {
		  padding: 4px;
		  padding-top: 10px;
		  padding-bottom: 10px;
		  border: 0;
		  margin-right:10px;	
		  margin-bottom:10px;
		  border: solid 1px #CCC;
		  background-color: white;  
		  border-radius: 5px;
		  width: 178px
		}


		/* Submit button in the login panel*/
		.submit {
		  border-radius: 5px;
		  width: 138px;
		  padding: 4px;
		  padding-top: 10px;
		  padding-bottom: 10px;
		  background-color: #ded;
		  border: solid 1px #CCC;
		  font-weight: bold;
		  color: #555
		}

		.submit:hover{  
		  background-color: #008C00;
		  border: solid 1px #008C00;  
		  color: #fff
		}


		</style>
	</head>

	<body>
    	<header>
      		<h1>CodeIgniter Installer</h1>
    	</header>	
		<div id="main">
			<?php echo $content;?>
			<footer>
				<p><b>&copy;left 2012 - Misael Zapata</b></p>
			</footer>
		</div>
	</body>
</html>		
