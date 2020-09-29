<?php
// Код получился некрасивым, но хотелось сделать без вложенных циклов

spiral([2, 3]);
echo '<br>';
spiral([3, 1]);
echo '<br>';
spiral([4, 4]);
echo '<br>';
spiral([7, 8]);
echo '<br>';
spiral([0, 7]);

function spiral($p) {
    // количество строк
    $rows = $p[1];
    // количество столбцов
    $cols = $p[0];

    if ($rows <= 0 || $cols <= 0) {
        exit('Error');
    }
    // конец спирали
    $max = $rows * $cols;
    // количество шагов по горизонтали
    $xSide = $cols - 1;
    // количество шагов по вертикали
    $ySide = $rows - 1;
    // координаты начала спирали
    $x = 0;
    $y = 0;
    // таблица
    $t = [];
    
    // количество итераций до конца первого витка
    $range = ($rows + $cols) * 2 - 4;
    $currentRange = $range;
    $rangeDecrement = 8;
    // направление движения
    $vectors = getVectors($xSide, $ySide);

    // В цикле проходим от 1 до конца спирали, задавая координаты для каждого значени.
    for ($i = 1; $i <= $max; $i++) {
        $t[$y][$x] = $i < 10 ? "0$i" : $i; 
        // Если текущее значение равно последнему значению очередного витка,
        // уменьшаем количество шагов по вертикали и горизонтали на два.
        if ($i == $currentRange) {
            $xSide -= 2;
            $ySide -= 2;
            // задаем направления движения с учетом угловых значений очередного витка
            $vectors = getVectors($xSide, $ySide, $currentRange);
            // получаем конечную точку очередного витка
            $currentRange += $range - $rangeDecrement;
            // каждый виток меньше предыдущего на 8 единиц
            $rangeDecrement += 8;
        }

        // получаем направление движения
        $vector = setVector($i, $vectors);
        
        // в зависимости от направления устанавливаем координату
        switch ($vector) {
            case 'd':
                $y++;
                break;
            case 'r':
                $x++;
                break;
            case 'u':
                $y--;
                break;
            case 'l':
                $x--;
                break;            
        }
    }

    // выводим таблицу
    foreach ($t as $v) {
        ksort($v);
        echo implode(' ', $v) . '<br>';
    };
}

// получаем точки, на которых необходимо изменить направлене движения
function getVectors($xSide, $ySide, $range = 0) {
    $down = $range;
    $right = $down + $ySide + 1;
    $up = $right + $xSide;
    $left = $up + $ySide;

    return [
        'down' => [
            'point' => $down,
            'vector' => 'd',
        ],
        'right' => [
            'point' => $right,
            'vector' => 'r',
        ],
        'up' => [
            'point' => $up,
            'vector' => 'u',
        ],
        'left' => [
            'point' => $left,
            'vector' => 'l',
        ],
    ];
}

// в зависимости от текущей позиции задаем направление движения
function setVector($position, $vectors) {
    if ($position >= $vectors['down']['point'] && $position < $vectors['right']['point']) {
        return $vectors['down']['vector'];
    } else if ($position >= $vectors['right']['point'] && $position < $vectors['up']['point']) {
        return $vectors['right']['vector'];
    } else if ($position >= $vectors['up']['point'] && $position < $vectors['left']['point']) {
        return $vectors['up']['vector'];
    } else if ($position >= $vectors['left']['point']) {
        return $vectors['left']['vector'];
    }
}
