<script type="text/javascript">
	$(document).ready(function() {
		$('div.edit-question div.header').toggle( function() {
			$('div.content', $(this).parent()).css('display', 'block');
		}, function() {
			$('div.content', $(this).parent()).css('display', 'none');
		});
		$('div.edit-question div.preview a').toggle(function(){
			$.ajaxSetup({ async: false });
			var $content = $('div.preview-content', $(this).parent());
			$content.
				css('display', 'none').
				load( $(this).attr('href') );
			$content.find('button[type=submit]').click( function() { return false; });
			$content.css('display', 'block');
			$(this).html('hide preview');
			return false;
		}, function() {
			var $content = $('div.preview-content', $(this).parent());
			$content.css('display', 'none');j
			$(this).html('show preview');
			return false;
		});
	});
</script>

<div id="edit-questions">
	<ul>
		<?php foreach( $questions as $question ): ?>
		<li>
			<div class="edit-question">
				<div class="header">
					<?php echo $question->Name . ' (' . $question->Template . ')'; ?>
				</div>
				<div class="content" style="display: none;">
					<form method="post" action="">
						<div class="buttons">
							<input type="button" name="save" value="save" />
							<input type="button" name="delete" value="delete" />
						</div>

						<textarea type="text" name="test" cols="60" rows="3"><?php echo $question->QuestionText; ?></textarea>
						<?php foreach( $question->getQuestionGroups() as $questionGroup ): ?>
							<div class="edit-question-group">
								<?php echo $questionGroup->Name . '<br />'; ?>
								<?php foreach( $questionGroup->getAnswerGroups() as $answerGroup ): ?>
									<div class="edit-answer-group">
										<?php echo $answerGroup->Name . '<br />'; ?>
										<select name="answer-type">
											<?php foreach( $answerTypes as $answerType ): ?>
												<?php
													$format = '<option value="%s" %s>%s</option>';
													$value = $answerType->Id;
													$checked = ( $answerType->Id == $answerGroup->getAnswerType()->Id ) ? 'selected="selected"' : '';
													$label = $answerType->Name;
													printf( $format, $value, $checked, $label );
												?>
											<?php endforeach; ?>
										</select>
										<div class="edit-answers">
											<ul>
												<li>
													<ul class="headings">
														<li class="col-id">id</li>
														<li class="col-name">name</li>
														<li class="col-label">label</li>
														<li class="col-value">value</li>
														<li class="col-text">is text</li>
													</ul>
												</li>
											<?php foreach( $answerGroup->getAnswers() as $answer ): ?>
												<li>
													<ul>
														<li class="col-id"><?php echo $answer->Id; ?></li>
														<li class="col-name"><input class="name" type="text" name="name" value="<?php echo $answer->Name; ?>" /></li>
														<li class="col-label"><input class="label" type="text" name="text" value="<?php echo $answer->Label; ?>" /></li>
														<li class="col-value"><input class="value" type="text" name="text" value="<?php echo $answer->Value; ?>" /></li>
														<li class="col-text"><input type="checkbox" name="text" value="text" <?php if( $answer->Text ) echo 'checked="checked"'; ?> /></li>
													</ul>
												</li>
											<?php endforeach; // end answers ?>
											</ul>
										</div>
									</div>
								<?php endforeach; // end answerGroups ?>
							</div>
						<?php endforeach; // end questionGroups ?>
					</form>
					
					<div class="preview">
						<a href="/preview?qid=<?php echo $question->Id; ?>" target="_blank">show preview</a>
						<div class="preview-content"></div>
					</div>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>