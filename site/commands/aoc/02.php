<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

function part1 (array $input): int {
	$count = A::reduce(
		$input,
		function($correct, $line,) {
			$reports = A::map(Str::split($line, ' '), intval(...));
			$incrementing = $reports[1] > $reports[0];

			for ($i=1; $i < count($reports); $i++) {
				if ($incrementing && ($reports[$i] <= $reports[$i-1])) {
					return $correct;
				} else if (!$incrementing && ($reports[$i] > $reports[$i-1])) {
					return $correct;
				}

				$diff = abs($reports[$i] - $reports[$i - 1]);
				if ($diff < 1 || $diff > 3) {
					return $correct;
				}
			}

			return $correct + 1;
		},
		0
	);
	return $count;
}

function checkReport(array $levels): bool {
	$incrementing = $levels[1] > $levels[0];
	for ($i=1; $i < count($levels); $i++) {
		// wrong direction
		if ($incrementing && ($levels[$i] <= $levels[$i-1])) {
			return false;
		} else if (!$incrementing && ($levels[$i] > $levels[$i-1])) {
			return false;
		}

		// wrong difference
		$diff = abs($levels[$i] - $levels[$i-1]);
		if ($diff < 1 || $diff > 3) {
			return false;
		}
	}

	return true;
}

function part2 (array $input) {
	$count = A::reduce(
		$input,
		function($correct, $report) {
			$levels = A::map(Str::split($report, ' '), intval(...));

			if (checkReport($levels)) {
				return $correct + 1;
			}

			for ($i=0; $i < count($levels); $i++) {
				$dampened = array_values(A::without($levels, $i));
				if (checkReport($dampened)) {
					return $correct + 1;
				}
			}

			return $correct;
		},
		0
	);
	return $count;
}

return [
	'description' => 'Advent of Code: Day 00',
	'args' => [],
	'command' => static function ($cli): void {
		$input = @require_once(__DIR__ . '/../inputs/' . basename(__FILE__));
		$demoinput = Str::split(<<<INPUT
		7 6 4 2 1
		1 2 7 8 9
		9 7 6 2 1
		1 3 2 4 5
		8 6 4 4 1
		1 3 6 7 9
		INPUT, "\n");

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