<?php

require_once __DIR__ . '/vendor/autoload.php';

function part1 (string $input) {
	return true;
}

function part2 (string $input) {
	return true;
}

$input = @require_once(__DIR__ . '/inputs/' . basename(__FILE__));
$demoinput = <<<INPUT
PASTEDEMOINPUTHERE
INPUT;

// PART 1
println('1) Result of demo: ' . part1($demoinput));
println('1) Result of real input: ' . part1($input));
println('–––');
// PART 2
println('2) Result of demo: ' . part2($demoinput));
println('2) Result of real input: ' . part2($input));
