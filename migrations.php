<?php

return [
	'table_storage' => [
		'table_name' => '_doctrine_migration_versions',
		'version_column_name' => 'version',
		'version_column_length' => 200,
		'executed_at_column_name' => 'executed_at',
		'execution_time_column_name' => 'execution_time',
	],

	'migrations_paths' => [
		'rinhaBackend\Migrations' => './migrations',
	],

	'all_or_nothing' => true,
	'check_database_platform' => true,
	'organize_migrations' => 'year_and_month',
];
