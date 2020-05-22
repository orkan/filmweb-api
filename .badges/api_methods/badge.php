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

$data['message'] = sprintf( $data['message'], $done, $done + $pending );

file_put_contents('badge.json', json_encode( $data ));
