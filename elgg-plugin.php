<?php

return [
	'actions' => [
		'post/autosave' => [
			'controller' => \hypeJunction\Draft\AutosaveAction::class,
		],
		'post/archive' => [
			'controller' => \hypeJunction\Draft\ArchiveAction::class,
		],
		'post/unarchive' => [
			'controller' => \hypeJunction\Draft\UnarchiveAction::class,
		],
		'post/publish' => [
			'controller' => \hypeJunction\Draft\PublishAction::class,
		],
	],
];
