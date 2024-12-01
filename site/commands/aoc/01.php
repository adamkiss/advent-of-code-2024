<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

function part1(string $input) {
	$left = [];
	$right = [];
	$diff = [];
	foreach (Str::split($input, "\n") as $line) {
		[$l, $r] = A::map(preg_split('/\s+/', $line), fn ($n) => intval($n));
		$left [] = $l;
		$right [] = $r;
	}
	sort($left);
	sort($right);
	foreach ($left as $i => $l) {
		$diff [] = abs($l - $right[$i]);
	}

	return array_sum($diff);
}

function part2(string $input) {
	$left = [];
	$right = [];
	$sum = [];
	foreach (Str::split($input, "\n") as $line) {
		[$l, $r] = A::map(preg_split('/\s+/', $line), fn ($n) => intval($n));
		$left [] = $l;
		if (array_key_exists($r, $right)) {
			$right[$r]++;
		} else {
			$right[$r] = 1;
		}
	}
	foreach ($left as $i => $l) {
		$sum [] = array_key_exists($l, $right) ? $l * $right[$l] : 0;
	}

	return array_sum($sum);
}

return [
	'description' => 'Advent of Code: Day 00',
	'args' => [],
	'command' => static function ($cli): void {
		$input = @require_once(__DIR__ . '/../inputs/' . basename(__FILE__));
		$demoinput = <<<INPUT
		3   4
		4   3
		2   5
		1   3
		3   9
		3   3
		INPUT;

		// PART 1
		$cli->out('1) Result of demo: ' . part1($demoinput));
		$cli->out('1) Result of real input: ' . part1($input));
		$cli->out('–––');
		// PART 2
		$cli->out('2) Result of demo: ' . part2($demoinput));
		$cli->out('2) Result of real input: ' . part2($input));

		$cli->success('Ran both!');
	}
];
