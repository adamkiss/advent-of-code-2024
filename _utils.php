<?php

function println(...$strings): void {
	foreach ($strings as $s) {
		print $s;
	}
	print "\n";
}
