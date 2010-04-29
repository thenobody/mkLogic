<?php 

// store application directory
$appDir = dirname( dirname( __FILE__ ) );
// include models, components, controllers directories in global execution
Core::includeDir( $appDir . '/models/', true );
Core::includeDir( $appDir . '/components/' );
Core::includeDir( $appDir . '/controllers/' );

error_reporting( E_ALL );

return array(
	'appDir'	=> $appDir,

	'questionnaireSchemasDir'	=> dirname( dirname( $appDir ) ) . '/schemas',

	// internal components configuration
	'Request'	=> array(
		'defaults' 	=> array(
			'controller'	=> 'Main',
			'action'		=> 'index',
		),
		'errors' => array(
			'404' => array( 'HttpStatus', '404' ),
		),
	),
	'Dispatcher'=> array(
		'defaultTemplate' => 'main.php',
	),
	'Session'	=> array(
		'name'		=> 'equetsionaire_presenter_admin',
	),

	// databse configuration
	'DataBase'	=> array(
		// connection config
		'connection' => array(
			'dsn'		=> 'mysql:host=localhost;dbname=equestionnaires',
			'username'	=> 'root',
			'password'	=> '',
			'dialect'	=> 'mysql'
		),
		// classes, which Outlet populates with Proxies
		'classes' => array(
			'Supervisor'	=>	array(
				'table'	=>	'supervisors',
				'props'	=>	array(
					'Id'		=>	array( 'id', 'int', array('pk'=>true, 'autoIncrement'=>true) ),
					'Username'	=>	array( 'username', 'varchar' ),
					'Password'	=>	array( 'password', 'varchar' ),
					'EMail'		=>	array( 'email', 'varchar'),
					'Active'	=>	array( 'active', 'bool'),
				),
			),
			'Token' => array(
				'table' => 'tokens',
				'props' => array(
					'Id'				=>	array( 'id', 'int', array('pk'=>true, 'autoIncrement'=>true) ),
					'Token'				=>	array( 'token', 'varchar' ),
					'QuestionnaireId'	=>	array( 'questionnaire_id', 'int' ),
					'StatusId'			=>	array( 'status_id', 'tinyint' ),
				),
				'associations' => array(
					array( 'one-to-many', 'Answer', array( 'key' => 'TokenId' ) ),
					array( 'one-to-one', 'Questionnaire', array( 'key' => 'QuestionnaireId' ) ),
					array( 'one-to-one', 'TokenStatus', array( 'key' => 'StatusId' ) ),
				),
			),
			'TokenStatus' => array(
				'table' => 'token_status',
				'props'	=>	array(
					'Id'	=>	array( 'id', 'int', array('pk'=>true, 'autoIncrement'=>true) ),
					'Value'	=>	array( 'value', 'varchar' ),
				),
			),
			'QuestionnaireStatus' => array(
				'table'	=>	'questionnaire_status',
				'props'	=>	array(
					'Id'	=>	array( 'id', 'int', array('pk'=>true, 'autoIncrement'=>true) ),
					'Value'	=>	array( 'value', 'varchar' ),
				),
			),
			'Questionnaire' => array(
				'table' => 'questionnaires',
				'props' => array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
					'File'			=>	array( 'file', 'varchar' ),
					'Continuous'	=>	array( 'continuous', 'bool' ),
					'StatusId'		=>	array( 'status_id', 'int' ),
				),
				'associations' => array(
					array( 'one-to-one', 'QuestionnaireStatus', array( 'key' => 'StatusId' ) ),
					array( 'one-to-many', 'Question', array( 'key' => 'QuestionnaireId' ) ),
				),
			),
			'Question'	=> array(
				'table'	=> 'questions',
				'props'	=> array(
					'Id'				=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'				=>	array( 'name', 'varchar' ),
					'QuestionText'		=>	array( 'question_text', 'text' ),
					'Template'			=>	array( 'template', 'varchar' ),
					'QuestionnaireId'	=>	array( 'questionnaire_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Questionnaire', array( 'key' => 'QuestionnaireId' ) ),
					array( 'one-to-many', 'QuestionGroup', array( 'key' => 'QuestionId' ) ),
					array( 'many-to-many', 'Question', array(
						'table'				=>	'questionnaire_order',
						'tableKeyLocal'		=>	'question_id',
						'tableKeyForeign'	=>	'next_question_id',
					) ),
					array( 'one-to-many', 'ValidationOrder', array( 'key' => 'QuestionId' ) ),
					array( 'one-to-many', 'FilteringOrder', array( 'key' => 'QuestionId' ) ),
				),
			),
			'QuestionGroup'	=> array(
				'table'	=> 'question_groups',
				'props'	=> array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
					'Label'			=>	array( 'label', 'varchar' ),
					'QuestionId'	=>	array( 'question_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Question', array( 'key' => 'QuestionId' ) ),
					array( 'one-to-many', 'AnswerGroup', array( 'key' => 'QuestionGroupId' ) ),
				),
			),
			'AnswerGroup'	=> array(
				'table'	=> 'answer_groups',
				'props'	=> array(
					'Id'				=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'				=>	array( 'name', 'varchar' ),
					'Label'				=>	array( 'label', 'varchar' ),
					'TypeId'			=>	array( 'type_id', 'int' ),
					'QuestionGroupId'	=>	array( 'question_group_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'AnswerType', array( 'key' => 'TypeId' ) ),
					array( 'many-to-one', 'QuestionGroup', array( 'key' => 'QuestionGroupId' ) ),
					array( 'one-to-many', 'Answer', array( 'key' => 'AnswerGroupId' ) ),
				),
			),
			'Answer' => array(
				'table' => 'answers',
				'props' => array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
					'Text'			=>	array( 'text', 'bool' ),
					'Value'			=>	array( 'value', 'varchar' ),
					'Label'			=>	array( 'label', 'varchar' ),
					'Limit'			=>	array( 'answer_limit', 'int' ),
					'AnswerGroupId'	=>	array( 'answer_group_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'AnswerGroup', array( 'key' => 'AnswerGroupId' ) ),
					array( 'one-to-many', 'Constraint', array( 'key' => 'AnswerId' ) ),
				),
			),
			'AnswerType' => array(
				'table' => 'answer_types',
				'props' => array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
				),
			),
			'UserAnswer' => array(
				'table' => 'user_answers',
				'props' => array(
					'Id'		=>	array( 'id', 'int', array( 'pk' => true, 'autoIncrement' => true ) ),
					'AnswerId'	=>	array( 'answer_id', 'int' ),
					'TokenId'	=>	array( 'token_id', 'int' ),
					'TextValue'	=>	array( 'text_value', 'text' ),
				),
				'associations'	=>	array(
					array( 'one-to-one', 'Answer', array( 'key' => 'AnswerId' ) ),
					array( 'one-to-one', 'Token', array( 'key' => 'TokenId' ) ),
				),
			),
			'Constraint' => array(
				'table' => 'constraints',
				'props' => array(
					'Id'		=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'AnswerId'	=>	array( 'answer_id', 'int' ),
					'RuleId'	=>	array( 'rule_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Answer', array( 'key' => 'AnswerId' ) ),
					array( 'many-to-one', 'ConstraintRule', array( 'key' => 'RuleId' ) ),
				),
			),
			'ConstraintRule' => array(
				'table' => 'constraint_rules',
				'props' => array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
				),
			),
			'FilteringOrder' => array(
				'table' => 'filtering_order',
				'props' => array(
					'Id'				=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'QuestionId'		=>	array( 'question_id', 'int' ),
					'ConstraintId'		=>	array( 'constraint_id', 'int' ),
					'NextConstraintId'	=>	array( 'next_constraint_id', 'int' ),
					'LogicalOperatorId'	=>	array( 'logical_operator_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Question', array( 'key' => 'QuestionId' ) ),
					array( 'many-to-one', 'Constraint', array( 'key' => 'ConstraintId' ) ),
					array( 'many-to-one', 'Constraint', array( 'key' => 'NextConstraintId', 'name' => 'NextConstraint' ) ),
					array( 'many-to-one', 'LogicalOperator', array( 'key' => 'LogicalOperatorId' ) ),
				),
			),
			'ValidationOrder' => array(
				'table' => 'validation_order',
				'props' => array(
					'Id'				=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'QuestionId'		=>	array( 'question_id', 'int' ),
					'ConstraintId'		=>	array( 'constraint_id', 'int' ),
					'NextConstraintId'	=>	array( 'next_constraint_id', 'int' ),
					'LogicalOperatorId'	=>	array( 'logical_operator_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Question', array( 'key' => 'QuestionId' ) ),
					array( 'many-to-one', 'Constraint', array( 'key' => 'ConstraintId' ) ),
					array( 'many-to-one', 'Constraint', array( 'key' => 'NextConstraintId', 'name' => 'NextConstraint' ) ),
					array( 'many-to-one', 'LogicalOperator', array( 'key' => 'LogicalOperatorId' ) ),
				),
			),
			'LogicalOperator' => array(
				'table' => 'logical_operators',
				'props' => array(
					'Id'			=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'Name'			=>	array( 'name', 'varchar' ),
				),
			),
			'QuestionnaireOrder' => array(
				'table' => 'questionnaire_order',
				'props' => array(
					'Id'				=>	array( 'id', 'int', array( 'pk'=>true, 'autoIncrement'=>true ) ),
					'QuestionId'		=>	array( 'question_id', 'int' ),
					'NextQuestionId'	=>	array( 'next_question_id', 'int' ),
				),
				'associations' => array(
					array( 'many-to-one', 'Question', array( 'key' => 'QuestionId' ) ),
					array( 'many-to-one', 'Question', array( 'key' => 'NextQuestionId', 'name' => 'NextQuestion' ) ),
				),
			),
		),
		'useGettersAndSetters' => false,
	),
);