<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

require_once __DIR__ . '/vendor/autoload.php';

function calculate_back(int|null $split_result, int $result, ...$nums) : int {
	$running = is_null($split_result)
		? $result
		: $split_result;
	$split_state = null;

	while (count($nums) > 1) {
		$next = array_pop($nums);

		if (($running % $next === 0) && is_null($split_result)) {
			if (is_null($split_state)) {
				$split_state = [$running, [...$nums, $next]];
			}

			$running = (int)floor($running / $next);
		} else {
			$running -= $next;
			$split_result = null;
		}

		if ($running < 0) {
			return 0;
		}
	}
	if ($running !== $nums[0]) {
		return !is_null($split_state)
			? calculate_back($split_state[0], $result, ...$split_state[1])
			: 0;
	}
	return $result;
}

function generate_ops (int $how_many) {
	$ops = ['*', '+'];
	for ($i=1; $i < $how_many-1; $i++) {
		$ops = A::merge(
			A::map($ops, fn($o) => $o.'*'),
			A::map($ops, fn($o) => $o.'+'),
		);
	}
	return $ops;
}

function generate_ops_join (int $how_many) {
	$ops = ['*', '+', '|'];
	for ($i=1; $i < $how_many-1; $i++) {
		$ops = A::merge(
			A::map($ops, fn($o) => $o.'*'),
			A::map($ops, fn($o) => $o.'+'),
			A::map($ops, fn($o) => $o.'|'),
		);
	}
	return $ops;
}

function calculate(array $ops, int $result, ...$nums) {
	foreach ($ops as $operations) {
		$running = $nums[0];
		for ($i=1; $i < count($nums); $i++) {
			$running = match($operations[$i-1]) {
				'*' => $running * $nums[$i],
				'+' => $running + $nums[$i],
				'|' => $running * pow(10, strlen((string)$nums[$i])) + $nums[$i]
			};
			if ($running > $result) {
				continue 2;
			}
		}
		if ($running === $result) {
			return $result;
		}
	}

	return 0;
}

function part1 (string $input) {
	$calcs = A::map(
		Str::split($input, "\n"),
		fn($l) => A::map(preg_split('/:? /', $l), fn($nr) => intval($nr))
	);

	$result = A::reduce($calcs, fn ($agr, $it) => $agr + calculate(generate_ops(count($it)-1), ...$it), 0);

	return $result;
}

function part2 (string $input) {
	$calcs = A::map(
		Str::split($input, "\n"),
		fn($l) => A::map(preg_split('/:? /', $l), fn($nr) => intval($nr))
	);

	$result = A::reduce($calcs, fn ($agr, $it) => $agr + calculate(generate_ops_join(count($it)-1), ...$it), 0);

	return $result;
}

$input = @require_once(__DIR__ . '/inputs/' . basename(__FILE__));
$demoinput = <<<INPUT
190: 10 19
3267: 81 40 27
83: 17 5
156: 15 6
7290: 6 8 6 15
161011: 16 10 13
192: 17 8 14
21037: 9 7 18 13
292: 11 6 16 20
INPUT;

// PART 1
ray()->clearScreen();
ray()->measure();
println('1) Result of demo: ' . part1($demoinput));
ray()->measure();
println('1) Result of real input: ' . part1($input));
ray()->measure();
println('–––');
// PART 2
println('2) Result of demo: ' . part2($demoinput));
ray()->measure();
println('2) Result of real input: ' . part2($input));
ray()->measure();
