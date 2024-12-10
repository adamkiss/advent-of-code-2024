<?php

function println(...$strings): void {
	print join(', ', $strings);
	print "\n";
}
