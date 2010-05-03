<?php

/*
	Questionnaire Constraint Builder for Questionnaire model
	- creates constraints for:
		- Questionnaire order (questions)
		- Validation order (answers and constraints)
		- Filtering order (answers and constraints)
	*** due to limitations of Outlet ORM,
	*** Questionnaire model has to be commited to the database prior to
	*** creation of Validation and Filtering constraints and relations
*/

class QuestionnaireConstraintBuilder extends BaseComponent
{

}