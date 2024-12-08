<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

require_once __DIR__ . '/vendor/autoload.php';

function toMap(string $input): array {
	return A::map(
		Str::split($input, "\n"),
		fn($line) => str_split($line)
	);
}

function printMap(array $map) {
	ray()->html(
		'<pre>'.
		A::join(A::map($map, fn($line) => A::join($line, '')), "\n")
		.'</pre>'
	);
}

function part1 (string $input) {
	$map = toMap($input);
	$pm = toMap($input);
	$Xmax = count($map[0]);
	$Ymax = count($map);
	$symbols = [];
	$antis = [];
	foreach ($map as $y => $line) {
		foreach ($line as $x => $symbol) {
			if ($symbol === '.') {
				continue;
			}

			if (array_key_exists($symbol, $symbols)) {
				foreach ($symbols[$symbol] as $antenna) {
					$diff = [$x - $antenna[0], $y - $antenna[1]];
					$anti1 = [$antenna[0] - $diff[0], $antenna[1] - $diff[1]];
					$anti2 = [$x + $diff[0], $y + $diff[1]];
					if (
						$anti1[0] >= 0 && $anti1[0] < $Xmax &&
						$anti1[1] >= 0 && $anti1[1] < $Ymax &&
						!in_array($anti1, $antis)
					) {
						$antis [] = $anti1;

						if ($map[$anti1[1]][$anti1[0]] === '.') {
							$map[$anti1[1]][$anti1[0]] = '#';
						}
					}
					if (
						$anti2[0] >= 0 && $anti2[0] < $Xmax &&
						$anti2[1] >= 0 && $anti2[1] < $Ymax &&
						!in_array($anti2, $antis)
					) {
						$antis [] = $anti2;

						if ($map[$anti2[1]][$anti2[0]] === '.') {
							$map[$anti2[1]][$anti2[0]] = '#';
						}
					}
				}
			}

			$symbols[$symbol] []= [$x, $y,];
		}
	}

	// printMap($map);
	// rd(array_unique($antis));

	return count($antis);
}

function part2 (string $input) {
	$map = toMap($input);
	$pm = toMap($input);
	$Xmax = count($map[0]);
	$Ymax = count($map);
	$symbols = [];
	$antis = [];
	foreach ($map as $y => $line) {
		foreach ($line as $x => $symbol) {
			if ($symbol === '.' || $symbol === '#') {
				continue;
			}

			$key = "{$x},{$y}";
			if (!isset($antis[$key])) {
				$antis[$key] = true;
				$pm[$y][$x] = '#';
			}

			if (array_key_exists($symbol, $symbols)) {
				foreach ($symbols[$symbol] as $antenna) {
					$diff = [$x - $antenna[0], $y - $antenna[1]];

					$back = $antenna;
					do {
						$back = [$back[0] - $diff[0], $back[1] - $diff[1]];
						$key = "{$back[0]},{$back[1]}";
						if (
							$back[0] >= 0 && $back[0] < $Xmax &&
							$back[1] >= 0 && $back[1] < $Ymax &&
							!isset($antis[$key])
						) {
							$antis[$key] = true;

							if ($map[$back[1]][$back[0]] === '.') {
								$map[$back[1]][$back[0]] = '#';
							}
							$pm[$back[1]][$back[0]] = '#';
						}
					} while (
						$back[0] >= 0 && $back[0] < $Xmax &&
						$back[1] >= 0 && $back[1] < $Ymax
					);

					$forward = [$x, $y];
					do {
						$forward = [$forward[0] + $diff[0], $forward[1] + $diff[1]];
						$key = "{$back[0]},{$back[1]}";
						if (
							$forward[0] >= 0 && $forward[0] < $Xmax &&
							$forward[1] >= 0 && $forward[1] < $Ymax &&
							!isset($antis[$key])
						) {
							$antis[$key] = true;

							if ($map[$forward[1]][$forward[0]] === '.') {
								$map[$forward[1]][$forward[0]] = '#';
							}
							$pm[$forward[1]][$forward[0]] = '#';
						}
					} while (
						$forward[0] >= 0 && $forward[0] < $Xmax &&
						$forward[1] >= 0 && $forward[1] < $Ymax
					);
				}
			}

			$symbols[$symbol] []= [$x, $y];
		}
	}

	// printMap($pm);
	// rd(array_unique($antis));

	return count($antis);
}
$input = @require_once(__DIR__ . '/inputs/' . basename(__FILE__));
$demoinput = <<<INPUT
............
........0...
.....0......
.......0....
....0.......
......A.....
............
............
........A...
.........A..
............
............
INPUT;

// PART 1
println('1) Result of demo: ' . part1($demoinput));
println('1) Result of real input: ' . part1($input));
println('–––');
// PART 2
println('2) Result of demo: ' . part2($demoinput));
println('2) Result of real input: ' . part2($input));
