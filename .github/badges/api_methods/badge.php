<?php
$data = array(
	'schemaVersion' => 1,
	'label'         => 'API Methods',
	'message'       => '%u/%u',
	'namedLogo'     => 'Semaphore CI',
	'logoColor'     => 'white',
	'color'         => 'f25822',
);

$done    = count( glob( 'done/*.*' ) );
$pending = count( glob( 'pending/*.*' ) );
$unknown = count( glob( 'unknown/*.*' ) );

$data['message'] = sprintf( $data['message'], $done, $done + $pending + $unknown );

echo "{$data['label']}: {$data['message']}";

file_put_contents( basename( __FILE__, 'php' ) . 'json', json_encode( $data ) );
