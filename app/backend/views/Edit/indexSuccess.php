<script type="text/javascript">	$(document).ready(function() {		var tabsConfig = {			id		: 'edit-left-menu',			class	: 'tabs',			target	: '#edit-workplace'		};		var args = { project: '<?php echo $project; ?>' };		var $leftMenu = new TabbedPanel(tabsConfig);		var $attrTab = $leftMenu.addTab('attributes', '/admin.php/edit/attributes', true, args);		var $questTab = $leftMenu.addTab('questions', '/admin.php/edit/questions', true, args);		var $orderTab = $leftMenu.addTab('order', '/admin.php/edit/order', true, args);		var $tokensTab = $leftMenu.addTab('tokens', '/admin.php/edit/tokens', true, args);				$leftMenu.selectTab( $attrTab );	});</script><div id="edit-questionnaire">	<div id="edit-left-menu"></div>	<div id="edit-workplace"></div></div>