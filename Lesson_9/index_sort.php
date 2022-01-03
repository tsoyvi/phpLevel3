<?php

/*
Создать массив на миллион элементов и отсортировать его различными способами. Сравнить скорости.
*/

function bubbleSort($array)
{
    for ($i = 0; $i < count($array); $i++) {
        $count = count($array);
        for ($j = $i + 1; $j < $count; $j++) {
            if ($array[$i] > $array[$j]) {
                $temp = $array[$j];
                $array[$j] = $array[$i];
                $array[$i] = $temp;
            }
        }
    }
    return $array;
}

//////////////////////////////////////////////
function shakerSort($array)
{
    $n = count($array);
    $left = 0;
    $right = $n - 1;
    do {
        for ($i = $left; $i < $right; $i++) {
            if ($array[$i] > $array[$i + 1]) {
                list($array[$i], $array[$i + 1]) = array($array[$i + 1], $array[$i]);
            }
        }
        $right -= 1;
        for ($i = $right; $i > $left; $i--) {
            if ($array[$i] < $array[$i - 1]) {
                list($array[$i], $array[$i - 1]) = array($array[$i - 1], $array[$i]);
            }
        }
        $left += 1;
    } while ($left <= $right);

    return $array;
}

///////////////////////////////////////////////
function quickSort(&$arr, $low, $high)
{
    $i = $low;
    $j = $high;
    $middle = $arr[($low + $high) / 2];   // middle – опорный элемент; в нашей реализации он находится посередине между low и high
    do {
        while ($arr[$i] < $middle) ++$i;  // Ищем элементы для правой части
        while ($arr[$j] > $middle) --$j;   // Ищем элементы для левой части
        if ($i <= $j) {
            // Перебрасываем элементы
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            // Следующая итерация
            $i++;
            $j--;
        }
    } while ($i < $j);

    if ($low < $j) {
        // Рекурсивно вызываем сортировку для левой части
        quickSort($arr, $low, $j);
    }

    if ($i < $high) {
        // Рекурсивно вызываем сортировку для правой части
        quickSort($arr, $i, $high);
    }

    return $arr;
}


////////////////////////////////////////////////
function heapify(&$arr, $countArr, $i)
{
    $largest = $i; // Инициализируем наибольший элемент как корень
    $left = 2 * $i + 1; // левый = 2*i + 1
    $right = 2 * $i + 2; // правый = 2*i + 2

    // Если левый дочерний элемент больше корня
    if ($left < $countArr && $arr[$left] > $arr[$largest])
        $largest = $left;

    //Если правый дочерний элемент больше, чем самый большой элемент на данный момент
    if ($right < $countArr && $arr[$right] > $arr[$largest])
        $largest = $right;

    // Если самый большой элемент не корень
    if ($largest != $i) {
        $swap = $arr[$i];
        $arr[$i] = $arr[$largest];
        $arr[$largest] = $swap;

        // Рекурсивно преобразуем в двоичную кучу затронутое поддерево
        heapify($arr, $countArr, $largest);
    }
}


function heapSort(&$arr)
{
    $countArr = count($arr);
    // Построение кучи (перегруппируем массив)
    for ($i = $countArr / 2 - 1; $i >= 0; $i--)
        heapify($arr, $countArr, $i);
    //Один за другим извлекаем элементы из кучи
    for ($i = $countArr - 1; $i >= 0; $i--) {
        // Перемещаем текущий корень в конец
        $temp = $arr[0];
        $arr[0] = $arr[$i];
        $arr[$i] = $temp;

        // вызываем процедуру heapify на уменьшенной куче
        heapify($arr, $i, 0);
    }
}



function displayArr($arr)
{
    foreach ($arr as $key => $value) {
        echo $key . ' => ' . $value . '; ';
    }
}


function getRandomArray($n = 1)
{
    $arr = [];
    for ($i = 0; $i < $n; $i++) {
        $arr[] = random_int(0, $n);
    }
    return $arr;
}


$arr = getRandomArray(100000); // компьютер слабенький поэтому размер массива на порядок меньше.

$start_time = microtime(true);

//$arr = bubbleSort($arr); // время выполнения  273.63623285294
// $arr = shakerSort($arr); // время выполнения 552.29389667511 
// $arr = quickSort($arr, 0, count($arr)); // время выполнения 0.1078999042511
heapSort($arr); // время выполнения  0.4896719455719

$end_time = microtime(true);


// displayArr($arr);



echo 'time - ' . ($end_time - $start_time);
