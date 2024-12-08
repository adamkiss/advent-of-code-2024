<?php

class Pos {
    public string $kind;
    public array $coord; // Coord is now a plain array

    public function __construct(string $kind, array $coord) {
        $this->kind = $kind;
        $this->coord = $coord;
    }
}

function createMap(string $input): array {
    $map = [];
    $w = 0;
    $h = 0;

    $lines = explode("\r\n", $input);

    foreach ($lines as $y => $line) {
        $chars = str_split($line);
        foreach ($chars as $x => $c) {
            $kind = $c;
            $map[] = new Pos($kind, [$x, $y]);
        }

        $w = strlen($line);
        $h = $y;
    }

    return [$map, $w, $h + 1];
}

function indexMap(array $map): array {
    $index = [];

    foreach ($map as $pos) {
        if ($pos->kind !== '.') {
            $key = $pos->kind;
            if (!array_key_exists($key, $index)) {
                $index[$key] = [];
            }
            $index[$key][] = $pos->coord;
        }
    }

    return $index;
}

function printMap(array $map, array $nodes, int $w, int $h) {
    $grid = array_fill(0, $h, array_fill(0, $w, '.'));

    foreach ($map as $pos) {
        $grid[$pos->coord[1]][$pos->coord[0]] = $pos->kind;
    }

    foreach (array_keys($nodes) as $coord) {
        $coord = explode(',', $coord);
        if ($grid[$coord[1]][$coord[0]] === '.') {
            $grid[$coord[1]][$coord[0]] = '#';
        }
    }

    foreach ($grid as $row) {
        echo implode('', $row) . "\n";
    }
}

function part1(string $input): int {
    [$map, $w, $h] = createMap($input);
    $index = indexMap($map);
    $nodes = [];

    foreach ($index as $positions) {
        $len = count($positions);
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                $d = [
                    $positions[$j][0] - $positions[$i][0],
                    $positions[$j][1] - $positions[$i][1]
                ];
                $a = [
                    $positions[$i][0] - $d[0],
                    $positions[$i][1] - $d[1]
                ];
                $b = [
                    $positions[$j][0] + $d[0],
                    $positions[$j][1] + $d[1]
                ];

                if ($a[0] >= 0 && $a[0] < $w && $a[1] >= 0 && $a[1] < $h) {
                    $nodes["{$a[0]},{$a[1]}"] = true;
                }
                if ($b[0] >= 0 && $b[0] < $w && $b[1] >= 0 && $b[1] < $h) {
                    $nodes["{$b[0]},{$b[1]}"] = true;
                }
            }
        }
    }

    return count($nodes);
}

function part2(string $input): int {
    [$map, $w, $h] = createMap($input);
    $index = indexMap($map);
    $nodes = [];

    foreach ($index as $positions) {
        $len = count($positions);
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                $a = $positions[$i];
                $b = $positions[$j];

                $d = [$a[0] - $b[0], $a[1] - $b[1]];

                while (true) {
                    $a = [$a[0] - $d[0], $a[1] - $d[1]];
                    if ($a[0] >= 0 && $a[0] < $w && $a[1] >= 0 && $a[1] < $h) {
                        $nodes["{$a[0]},{$a[1]}"] = $a;
                    } else {
                        break;
                    }
                }

                while (true) {
                    $b = [$b[0] + $d[0], $b[1] + $d[1]];
                    if ($b[0] >= 0 && $b[0] < $w && $b[1] >= 0 && $b[1] < $h) {
                        $nodes["{$b[0]},{$b[1]}"] = $b;
                    } else {
                        break;
                    }
                }
            }
        }
    }

    return count($nodes);
}


const TEST_INPUT = <<<INPUT
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

const TEST_RESULT1 = 14;
const TEST_RESULT2 = 34;

function assertEq($a, $b, $message) {
    if ($a !== $b) {
        throw new Exception($message . " Expected $b, got $a");
    }
}

$input = require_once('inputs/08.php');

$before = microtime(true);
echo part1($input) . "\n";
echo part2($input) . "\n";
$after = microtime(true);

echo ($after - $before) . "\n";
