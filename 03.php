<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Kirby\Filesystem\F;

require_once __DIR__ . '/vendor/autoload.php';

function part1 (string $input): int {
	$sum = 0;
	$parts = Str::matchAll($input, '/mul\((\d{1,3})\,(\d{1,3})\)/');
	foreach ($parts[0] as $i=>$_) {
		$sum += intval($parts[1][$i]) * intval($parts[2][$i]);
	}
	return $sum;
}

function part2 (string $input) {
	$sum = 0;
	$ignored = false;
	$parts = Str::matchAll($input, '/mul\((\d{1,3})\,(\d{1,3})\)|(do(?:n\'t)?\(\))/');
	foreach ($parts[0] as $i=>$cmd) {
		match (true) {
			$cmd === 'do()' => $ignored = false,
			$cmd === "don't()" => $ignored = true,
			!$ignored => $sum += intval($parts[1][$i]) * intval($parts[2][$i]),
			default => null,
		};
	}
	return $sum;
}

$input = F::read(__DIR__ . '/inputs/03.txt');
$demoinput = <<<INPUT
xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))
INPUT;
$demoinput2 = <<<INPUT
xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))
INPUT;

// PART 1
println('1) Result of demo: ' . part1($demoinput));
println('1) Result of real input: ' . part1($input));
println('–––');
// PART 2
println('2) Result of demo: ' . part2($demoinput2));
println('2) Result of real input: ' . part2($input));

