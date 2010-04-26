<div style="border: 1px solid black; margin: 5px;width: 100%;">	questionnaire - name: <?php echo $questionnaire->Name; ?>; continuous: <?php echo ($questionnaire->Continuous) ? 'true' : 'false'; ?>	<?php foreach( $questionnaire->getQuestions()->toArray() as $question ) : ?>	<div style="border: 1px solid black; margin: 5px;width: 100%;">		question - name: <?php echo $question->Name; ?><br />		content: <?php echo $question->Content; ?>				<?php foreach( $question->getQuestionGroups()->toArray() as $questionGroup ): ?>			<div style="border: 1px solid black; margin: 5px; width: 100%;">				question-group - name: <?php echo $questionGroup->Name; ?>				<?php foreach( $questionGroup->getAnswerGroups()->toArray() as $answerGroup ): ?>					<div style="border: 1px solid black; margin: 5px; width: 100%;">						answer-group - name: <?php echo $answerGroup->Name; ?>; type: <?php echo $answerGroup->Type; ?>						<?php foreach( $answerGroup->getAnswers()->toArray() as $answer ): ?>							<div style="border: 1px solid black; margin: 5px; width: 100%;">								answer - name: <?php echo $answer->Name; ?>; text: <?php echo ($answer->Text) ? 'true' : 'false'; ?>; value: <?php echo $answer->Value; ?>; label: <?php echo $answer->Label; ?>							</div>						<?php endforeach; ?>					</div>				<?php endforeach; ?>			</div>		<?php endforeach; ?>				<div style="border: 1px solid black; margin: 5px; width: 100%;">			<b>validation</b><br />			<?php if( $question->hasValidation() ): ?>			<?php foreach( $question->getValidation()->getConstraints()->toArray() as $constraint ): ?>			<div style="border: 1px solid black; margin: 5px; width: 100%;">				<ul>					<li>type: <?php echo $constraint->Type; ?></li>					<li>Question: <?php echo $constraint->Question; ?></li>					<li>QuestionGroup: <?php echo $constraint->QuestionGroup; ?></li>					<li>AnswerGroup: <?php echo $constraint->AnswerGroup; ?></li>					<li>Answer: <?php echo $constraint->Answer; ?></li>					<li>Not: <?php echo ($constraint->Not) ? 'true' : 'false'; ?></li>					<li>						<b>conformers</b><br />						<?php foreach( $constraint->getConformers()->toArray() as $conformer ): ?>							<div style="border: 1px solid black; margin: 5px; width: 100%;">								QuestionGroup: <?php echo $conformer->QuestionGroup; ?><br />								AnswerGroup: <?php echo $conformer->AnswerGroup; ?><br />								Answer: <?php echo $conformer->Answer; ?><br />								Rule: <?php echo $conformer->Rule; ?><br />							</div>						<?php endforeach; ?>					</li>				</ul>			</div>			<?php endforeach; ?>			<?php endif; ?>		</div>				<div style="border: 1px solid black; margin: 5px; width: 100%;">			<b>filtering</b><br />			<?php if( $question->hasFiltering() ): ?>			<?php foreach( $question->getFiltering()->getFilters()->toArray() as $filter ): ?>			<div style="border: 1px solid black; margin: 5px; width: 100%;">				<?php foreach( $filter->getConstraints()->toArray() as $constraint ): ?>				<div style="border: 1px solid black; margin: 5px; width: 100%;">					<ul>						<li>type: <?php echo $constraint->Type; ?></li>						<li>Question: <?php echo $constraint->Question; ?></li>						<li>QuestionGroup: <?php echo $constraint->QuestionGroup; ?></li>						<li>AnswerGroup: <?php echo $constraint->AnswerGroup; ?></li>						<li>Answer: <?php echo $constraint->Answer; ?></li>						<li>Not: <?php echo ($constraint->Not) ? 'true' : 'false'; ?></li>					</ul>				</div>				<?php endforeach; ?>			</div>			<?php endforeach; ?>			<?php endif; ?>		</div>	</div>	<?php endforeach; ?></div>