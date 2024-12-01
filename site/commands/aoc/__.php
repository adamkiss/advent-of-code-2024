<?php

return [
	'description' => 'Advent of Code: Day 00',
	'args' => [],
	'command' => static function ($cli): void {
		$input = @require_once(__DIR__ . '/../inputs/' . basename(__FILE__));
		$demoinput = <<<INPUT
		1
		INPUT;

		// PART 1

		$cli->out('Result');

		// PART 2

		$cli->out('Result');

		$cli->success('Ran both tasks!');
	}
];
