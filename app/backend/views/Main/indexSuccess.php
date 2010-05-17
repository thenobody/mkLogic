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
		Panel builder
		- precursor for different panels and windows
		- expects JSON config as argument for constructor
		- JSON model
		{
			id:		(string) id of panel element
			class:	(string) class of panel element
		}
	*/
	var Panel = Base.extend({
		config:			null,
		element:		null,
		id:				null,
		class:			null,
		
		constructor:	function(_config)
		{
			this.config = _config;
			this.id = this.config.id;
			this.class = this.config.class;
			this.element = $('#' + this.id).addClass('panel ' + this.class);
		},
		getElement:		function()
		{
			return this.element;
		}
	});
	
	/*
		TabbedPanel builder
		- tabbed panel element
		- expects JSON config as argument for constructor
		- JSON model
		{
			id:		(string) id of main panel element
			class:	(string) class of panel element
			target:	(string) id of targer element where contents of current tab are shown
		}
	*/
	var TabbedPanel = Panel.extend({
		tabs:			null,
		contentTarget:	null,
		currentTab:		null,
		previousTab:	null,
		defaultTab:		null,
		constructor:	function(_config)
		{
			this.base(_config);
			this.contentTarget = $(this.config.target);
			this.tabs = $('<ul />');
			this.element.html(this.tabs);
		},
		addTab:			function(_label, _url, _persistent, _args)
		{
			var config = this.config;
			var tabId = config.class + '-tab' + $('li.tab', this.tabs).length;
			var config = {
				label		:	_label,
				url			:	_url,
				persistent	:	_persistent,
				id			:	tabId,
				args		:	_args
			};
			var $tabs = this.tabs;
			var $tabBuilder = new TabBuilder( config, this );
			$tabs.append( $tabBuilder.create() );
			if( this.defaultTab == null )
				this.defaultTab = $tabBuilder;
			return $tabBuilder;
		},
		selectTab:		function(tab)
		{
			this.currentTab = tab;
			this.contentTarget.load( tab.config.url, tab.config.args );
		},
		removeTab:		function(tab)
		{
			tab.tab.remove();
			if( this.currentTab == tab )
				this.selectTab(this.defaultTab);
		},
		setDefaultTab:	function(tab)
		{
			this.defaultTab = tab;
		}
	});
	
	/*
		TabBuilder
		- builds new tab (as LI element with onclick action)
		- expectes JSON object as argument of the constructor
		- JSON model
		{
			label:			(string) text to be displayed as a label of this element,
			url:			(string) URL from which content should be rerieved
			persistent:		(boolean) whether this tab should be allowed to be closed - default FALSE
			id:				(string) id of tab LI element
			args:			(object) pairs (name: value) of arguments to be sent with request - OPTIONAL
		}
	*/
	var TabBuilder = Builder.extend({
		closeButton:	null,
		tab:			null,
		container:		null,
		constructor:	function(_config, _container) {
			this.base(_config);
			this.container = _container;
		},
		create:	function() {
			var config = this.getConfig();

			var container = this.container;
			var $this = this;
			this.tab = $('<li />').attr({
				class	:	'tab',
				id		:	config.id
			}).click(function() {
				container.selectTab($this);
			});
			var $a = $('<a />').attr({
				href	: "#"
			}).html(config.label);

			this.tab.html( $a );
			if( !config.persistent )
			{
				var $close = $('<a />').attr({
					class	:	'close-tab',
					href	:	'#'
				}).click(function(){
					container.removeTab($this);
				}).html('X');
				this.tab.append($close);
			}
			
			return this.tab;
		}
	});
	
	// document init
	$(document).ready( function(){
		// create home tab
		var tabsConfig = {
			id		: 'main-tabs',
			class	: 'tabs',
			target	: '#main-content'
		};
		var $tabs = new TabbedPanel(tabsConfig);
		document.tabs = $tabs;
		var $homeTab = $tabs.addTab('home', '/admin.php/home', true, null);
		$tabs.selectTab($homeTab);
		
		$('#navig').load( '/admin.php/main/navigMenu' );
	});
	//]]>
</script>

<div id="page" class="page">
	<div id="navig" class="navig"></div>
	<div id="main">
		<div id="main-tabs" class="test"></div>
	
		<div id="main-content">
		</div>
	</div>
</div>



