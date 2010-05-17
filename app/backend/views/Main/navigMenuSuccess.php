<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function(){
		$('#navig-home').click(function() {
			$('#main').load('/admin.php/home');
		});
		$('#navig-edit').click(function() {
			$('#main').load('/admin.php/edit');
		});
		$('#navig-stats').click(function() {
			$('#main').load('/admin.php/statistics');
		});
		$('#navig-logout').click(function() {
			$('#main').load('/admin.php/main/logout');
		});
	});
	//]]>
</script>

<ul>
	<li><a id="navig-home" href="#">Home</a></li>
	<li><a id="navig-edit" href="#">Edit</a></li>
	<li><a id="navig-stats" href="#">Statistics</a></li>
	<li><a id="navig-logout" href="#">Logout</a></li>
</ul>