<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

require_once __DIR__ . '/vendor/autoload.php';

function toMap(string $input) : array {
	return A::map(
		Str::split($input, "\n"),
		fn($line) => str_split($line)
	);
};

function getDirection(string $symbol): array {
	return match($symbol) {
		'^' => [0, -1],
		'v' => [0, 1],
		'<' => [-1, 0],
		'>' => [1, 0]
	};
}

function turnRight(array $direction = []) {
	return match($direction) {
		[0, -1] => [1, 0],
		[0, 1]  => [-1, 0],
		[-1, 0] => [0, -1],
		[1, 0]  => [0, 1]
	};
}

function part1 (string $input) {
	$map = toMap($input);
	$pos = null;
	$vec = null;
	foreach ($map as $y => $line) {
		if (!is_null($pos)) {
			break;
		}
		foreach ($line as $x => $symbol) {
			if (in_array($symbol, ['v', '^', '<', '>'])) {
				$pos = [$x, $y];
				$vec = getDirection($symbol);
				break;
			}
		}
	}

	do {
		$newPos = [$pos[0] + $vec[0], $pos[1] + $vec[1]];
		if (
			$newPos[0] < 0
			|| $newPos[0] >= count($map[0])
			|| $newPos[1] < 0
			|| $newPos[1] >= count($map)
		) {
			break;
		}else if ($map[$newPos[1]][$newPos[0]] === '#') {
			$vec = turnRight($vec);
		} else {
			$pos = $newPos;
			$map[$newPos[1]][$newPos[0]] = 'X';
		}
	} while (true);

	$X = A::reduce(
		$map,
		fn($total, $it) => $total + A::reduce(
			$it,
			fn($subtotal, $symbol) => $subtotal + ($symbol === 'X' ? 1 : 0),
			0
		),
		0
	);

	return $X;
}

function isMapTraversalLooped(array $map, array $pos, array $vec) : bool {
	$justTurned = false;
	$turns = [];
	do {
		$newPos = [$pos[0] + $vec[0], $pos[1] + $vec[1]];
		// Out of bounds
		if (
			$newPos[0] < 0
			|| $newPos[0] >= count($map[0])
			|| $newPos[1] < 0
			|| $newPos[1] >= count($map)
		) {
			return false;
		}

		if (
			$map[$newPos[1]][$newPos[0]] === '#'
			|| $map[$newPos[1]][$newPos[0]] === 'O'
		) {
			$vec = turnRight($vec);
			$off = join('-',[...$pos, ...$vec]);
			if (in_array($off, $turns)) {
				return true;
			}

			$turns [] = $off;
			$map[$pos[1]][$pos[0]] = '+';
		} else {
			$pos = $newPos;
			$char = match($vec) {
					[0, -1] => '|',
					[0, 1] => '|',
					[-1, 0] => '-',
					[1, 0] => '-'
				};
			if ($map[$pos[1]][$pos[0]] === '.') {
				$map[$pos[1]][$pos[0]] = $char;
			}
		}
	} while (true);
}

function printMap(array $map) {
	// ray()->html(
	// 	'<pre>'.
	// 	A::join(A::map($map, fn($line) => A::join($line, '')), "\n")
	// 	.'</pre>'
	// );
	echo "---\n\n";
	echo A::join(A::map($map, fn ($line) => A::join($line, '')), "\n");
}

function part2 (string $input) {
	$map = toMap($input);
	$pos = null;
	$vec = null;
	foreach ($map as $y => $line) {
		if (!is_null($pos)) {
			break;
		}
		foreach ($line as $x => $symbol) {
			if (in_array($symbol, ['v', '^', '<', '>'])) {
				$pos = [$x, $y];
				$vec = getDirection($symbol);
				break;
			}
		}
	}

	$looped = 0;
	foreach ($map as $y => $line) {
		foreach ($line as $x => $symbol) {
			if ($symbol === '.') {
				$testMap = $map;
				$testMap[$y][$x] = 'O';
				if (isMapTraversalLooped($testMap, $pos, $vec)) {
					$looped++;
				}
			}
		}
	}

	return $looped;
}

$input = @require_once(__DIR__ . '/inputs/' . basename(__FILE__));
$demoinput = <<<INPUT
....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...
INPUT;

// PART 1
println('1) Result of demo: ' . part1($demoinput));
println('1) Result of real input: ' . part1($input));
println('–––');
// PART 2
println('2) Result of demo: ' . part2($demoinput));
println('2) Result of real input: ' . part2($input));