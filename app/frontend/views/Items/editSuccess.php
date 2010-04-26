<?php

echo json_encode(
	array(
		'status' => 'ok',
		'item' => $item->serializeProperties(),
	)
);