<script type="text/javascript">
	// document init
	$(document).ready(function() {
		$('#projects .tab a').click(function() {
			var $tabs = document.tabs;
			var tabLabel = $(this).parents('.tab').attr('id').replace( /^row/, '' );
			var $tab = $tabs.addTab( tabLabel, '/admin.php/edit', false, { project : tabLabel } );
			$tabs.selectTab($tab);
		});
	});
</script>

<div id="projects">
	<ul>
	<?php foreach( $projects as $project ): ?>
		<li class="table-row">
			<ul class="tab" id="row<?php echo $project->Name; ?>">
				<li class="table-div">
					<a href="#"><?php echo $project->Name; ?></a>
				</li>
				<li class="table-div">
					<a href="#"><?php echo $project->getQuestionnaireStatus()->Value; ?></a>
				</li>
				<li class="table-div">
					<a href="#"><?php echo count( $project->getTokens()); ?></a>
				</li>
			</ul>
		</li>
	<?php endforeach; ?>
	</ul>
</div>