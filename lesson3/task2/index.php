<?php

// Доработать алгоритм бинарного поиска для нахождения кол-ва повторений в массиве. 
// Сложность O(logn) не должна измениться. 
// Учтите, что массив длиной n может состоять из одного значения [4, 4, 4, 4, ...(n)..., 4].

function f($num, $array) {
    $res = [];

    $start = 0;
    $end = count($array) - 1;

    $base = floor(($start + $end) / 2);

    while ($start <= $end) {
        if ($array[$base] == $num) {
            if (!in_array($base, $res)) {
                $res[] = $base;
            }

            $next = end($res) + 1;
            $last = reset($res) - 1;

            if ($num == $array[$next]) {
                array_push($res, $next);
            }

            if ($num == $array[$last]) {
                array_unshift($res, $last);
            }

            if ($num !== $array[$next] && $num !== $array[$last]) {
                echo "НАЙДЕНО ВХОЖДЕНИЙ ИСКОМОГО ЧИСЛА: " . count($res) . "<br>";
                echo "в диапазоне от элемента с индексом: $last до элемента c индексом $next<br>";
                return $res;
            }
            
            continue;
        } elseif ($array[$base] < $num) {
            $start = $base + 1;
        } else {
            $end = $base - 1;
        }
        
        $base = floor(($start + $end) / 2);
    }

    echo "ЧИСЛО НЕ НАЙДЕНО<br>";
    return null;
}

$array = [1, 2, 3, 5, 5, 5, 5, 7, 9];

print_r(f(5, $array));
