<?php

require_once __DIR__ . '/vendor/autoload.php';
$input = trim(file_get_contents(__DIR__ . '/inputs/' . basename(__FILE__, 'php') . 'txt'));
$demoinput1 = <<<INPUT
...0...
...1...
...2...
6543456
7.....7
8.....8
9.....9
INPUT;
$demoinput2 = <<<INPUT
89010123
78121874
87430965
96549874
45678903
32019012
01329801
10456732
INPUT;
$demoinput3 = <<<INPUT
012345
123456
234567
345678
4.6789
56789.
INPUT;

function toMap(string $input): array {
	$map = array_map(
		fn ($l) => array_map(
			fn($ch) => $ch === '.' ? null : intval($ch),
			str_split($l)
		),
		explode(string: $input, separator: "\n")
	);
	return $map;
}

function printMap(array $map) {
	ray()->html(
		'<pre>'.
		A::join(A::map($map, fn($line) => A::join($line, '')), "\n")
		.'</pre>'
	);
}

function vec_sub(int $x1, int $y1, int $x2, int $y2): array {
	return [$x1 - $x2, $y1 - $y2];
}
function vec_add(int $x1, int $y1, int $x2, int $y2): array {
	return [$x1 + $x2, $y1 + $y2];
}
function in_bounds2(int $x, int $y, int $Xmax, int $Ymax): bool {
	return $x >= 0 && $x < $Xmax && $y >= 0 && $y < $Ymax;
}

function part1 (string $input) {
	$map = toMap($input);
	$maxX = count($map[0]);
	$maxY = count($map);

	$paths = [];
	foreach ($map as $i => $line) {
		foreach ($line as $j => $point) {
			if ($point === 0) {
				$paths []= p1next($map, $point,  $j, $i, $maxX, $maxY, $j, $i);
			}
		}
	}

	return count(array_unique(array_merge(...$paths)));
}

function p1next(array $map, int $cur, int $x, int $y, int $mx, int $my, int $sx, int $sy) : array {
	if ($cur === 9) {
		return ["$sx,$sy,$x,$y"];
	}

	$found = [];
	$next = $cur + 1;

	foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$i, $j]) {
		[$xn, $yn] = vec_add($x, $y, $i, $j);
		if (!in_bounds2($xn, $yn, $mx, $my)) {
			continue;
		}
		if ($map[$yn][$xn] !== $next) {
			continue;
		}
		$found = array_merge($found, p1next($map, $next, $xn, $yn, $mx, $my, $sx, $sy));
	}

	return $found;
}

function part2 (string $input) {
	$map = toMap($input);
	$maxX = count($map[0]);
	$maxY = count($map);

	$paths = 0;
	foreach ($map as $i => $line) {
		foreach ($line as $j => $point) {
			if ($point === 0) {
				$paths += p2next($map, $point,  $j, $i, $maxX, $maxY);
			}
		}
	}

	return $paths;
}

function p2next(array $map, int $cur, int $x, int $y, int $mx, int $my) : int {
	if ($cur === 9) {
		return 1;
	}

	$found = 0;
	$next = $cur + 1;

	foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$xplus, $yplus]) {
		[$xn, $yn] = vec_add($x, $y, $xplus, $yplus);
		if (!in_bounds2($xn, $yn, $mx, $my)) {
			continue;
		}
		if ($map[$yn][$xn] !== $next) {
			continue;
		}
		$found += p2next($map, $next, $xn, $yn, $mx, $my);
	}

	return $found;
}


$s = microtime(true);

// 1
$p = microtime(true);
println('1) Result of demo1: ' . part1($demoinput1));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

$p = microtime(true);
println('1) Result of demo2: ' . part1($demoinput2));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

$p = microtime(true);
println('1) Result of real input: ' . part1($input));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

// 2
$p = microtime(true);
println('2) Result of demo3 (227): ' . part2($demoinput3));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

$p = microtime(true);
println('2) Result of demo2 (81): ' . part2($demoinput2));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

$p = microtime(true);
println('2) Result of real input: ' . part2($input));
printf("» %.3fms\n", (microtime(true)-$p) * 1000);

printf("TOTAL: %.3fms\n", (microtime(true)-$s) * 1000);
