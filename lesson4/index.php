<?php

require_once 'class/Node.php';
require_once 'class/Tree.php';
require_once 'class/Math.php';

$tree = new Tree();

$tree->insert(7)
     ->insert(2)
     ->insert(9)
     ->insert(1)
     ->insert(0)
     ->insert(5)
     ->insert(4)
     ->insert(6)
     ->insert(8)
     ->insert(12);

echo '<pre>';
print_r($tree);
echo '</pre>';     

$tree->traverse('LRN', fn($node) => print($node->value . ' '));

$tree->traverse('LRN', function(&$node) {
    $node = null;
});

print_r($tree);

echo '<hr>';

$math = new Math();

echo $math->calculate('(2 + 2 + 2 + 2 + 2) * 0') . '<br>'; // 0
echo $math->calculate('(2 + 3) * 10 + (7 - 4) + 5 - 4') . '<br>'; // 54
echo $math->calculate('11 * (42 + 23) - (40 ^ 2 / 5)') . '<br>'; // 395
echo $math->calculate('((3 + 7) * (2 - 5 * 1)) / 7') . '<br>'; // -4.2857142857143
