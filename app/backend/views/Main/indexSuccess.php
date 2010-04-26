<script type="text/javascript">
	//<![CDATA[
	$(document).ready( function(){
		$("#left-menu").load( '/admin.php/main/getProjects' );
	});
	//]]>
</script>

<div id="left-menu" style="border: 1px solid black; width: 150px; font-size: 0.9em;"></div>

<form enctype="multipart/form-data" action="" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
	Choose a file to upload: <input name="uploadedfile" type="file" /><br />
	<input type="submit" value="Upload File" />
</form>
<br />
<?php
	//if( isset( $questionnaire ) )
	//	print_r( $questionnaire );
?>

