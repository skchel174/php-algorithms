<?php

// Реализовать на РНР сортировку слиянием (не копируя готовое :))

function mergeSort($array) {
    $count = count($array);

    if ($count <= 1) {
        return $array;
    }
        
    $middle = ceil($count / 2);
    list($left, $right) = array_chunk($array, $middle);

    $left = mergeSort($left);
    $right = mergeSort($right);

    $merged = [];

    foreach ($left as $lKey => $lVal) {

        foreach ($right as $rKey => $rVal) {

            if ($lVal > $rVal) {
                $merged[] = $rVal;
                unset($right[$rKey]);
            } elseif ($lVal < $rVal) {
                $merged[] = $lVal;
                unset($left[$lKey]);
                continue 2;
            } else {
                $merged[] = $rVal;
                $merged[] = $lVal;
                unset($right[$rKey]);
                unset($left[$lKey]);
                continue 2;
            }
        }
    }

    return array_merge($merged, $left, $right);
}

$array = [3, 5, 4, 7, 1, 4, 6, 2, 9, 8];

print_r(mergeSort($array));