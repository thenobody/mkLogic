<script type="text/javascript">
	//<![CDATA[
	var Builder = Base.extend({
		config:			null,
		constructor:	function(_config) {
			this.config = _config;
		},
		getConfig:		function() {
			return this.config;
		}
	});
	
	
	/*
		MenuElement builder
		- expectes JSON object as argument of the constructor
		- JSON model
		{
			label:			(string) text to be displayed as a label of this element,
			templateUrl:	(string) URL where to look for HTML template (with exactyly one 'a' element)
			targetUrl:		(string) URL for retrieving data on onclick event
			targetElement:	(string) id attribute of element where to load retrieved data from targetUrl
		}
	*/
	var MenuElement = Builder.extend({
		label:			null,	// displayed text of element
		html:			null,	// jQuery html element
		action:			null,	// function to be executed on onclick event
		templateUrl:	null,	// html template address
		targetElement:	null,	// id of element content of which should be updated
		targetUrl:		null,	// URL used for retrieving data which should be used to update target element
		
		setHtml:		function( html ) {
			this.html = $('<div>' + html + '</div>');
		},
		generateAction:	function() {
			var url = this.targetUrl;
			var projectName = this.label;
			var $target = $( '#' + this.targetElement );
			var callback = function() {
				$target.load( url, { project : projectName } );
			};
			return callback;
		},
		prepare:		function() {
			var config = this.getConfig();
			this.label = config.label;
			this.templateUrl = config.templateUrl;
			
			// set target data
			this.targetElement = config.targetElement;
			this.targetUrl = config.targetUrl;
			
			// get template via AJAX call
			$.ajaxSetup({ async : false });
			var xhr = $.get(this.templateUrl);
			this.setHtml( xhr.responseText );
			// configure 'a' element
			var a = this.html.find( 'a' );
			a.html( this.label );
			// set onclick event
			a.click( this.generateAction() );
			
			return this.html;
		}
	});
	$(document).ready( function(){
		$.getJSON( '/admin.php/main/getProjects', function(data){
			var list = $("<ul>");
			$.each( data, function(i, value){
				var builder = new MenuElement( value );
				var elem = builder.prepare();
				var project = $('<li>').append( elem );
				list.append( project );
			});
			$('#left-menu').append( list );
		});
	});
	//]]>
</script>

<div id="page" class="page">
	<div id="navig" class="navig">
		<ul id="asd">
			<li><a href="#">Home</a></li>
			<li><a href="#">Logout</a></li>
		</ul>
	</div>

	<div id="left-menu" class="left-menu">
		
	</div>

	<div id="main" class="main">
		sfgdf
	</div>
</div>



