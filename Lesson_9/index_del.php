<?php

/*
2. Реализовать удаление элемента массива по его значению. Обратите внимание на возможные дубликаты!
*/

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





function displayArr($array)
{
    foreach ($array as $key => $value) {
        echo $key . ' => ' . $value . '; <br>';
    }
}


function getRandomArray($n = 1)
{
    $arr = [];
    for ($i = 0; $i < $n; $i++) {
        $arr[] = random_int(0, 50);
    }
    return $arr;
}


function delElementArray($array, $valDel)
{
    foreach ($array as $key => $value) {
        if ($valDel == $value) {
            unset($array[$key]);
        }
    }
    return array_values($array);
}



$arr = getRandomArray(100);

heapSort($arr);

displayArr($arr);

echo '<hr>';

$arr = delElementArray($arr, 20);


displayArr($arr);
