<?php

function part1 (string $input) {
	return true;
}

function part2 (string $input) {
	return true;
}

return [
	'description' => 'Advent of Code: Day 00',
	'args' => [],
	'command' => static function ($cli): void {
		$input = @require_once(__DIR__ . '/../inputs/' . basename(__FILE__));
		$demoinput = <<<INPUT
		1
		INPUT;

		// PART 1
		$cli->out('1) Result of demo: ' . part1($demoinput));
		$cli->out('1) Result of real input: ' . part1($input));
		$cli->out('–––');
		// PART 2
		$cli->out('2) Result of demo: ' . part2($demoinput));
		$cli->out('2) Result of real input: ' . part2($input));

		$cli->success('Ran both tasks!');
	}
];
