<?php
/*
Подсчитать практически количество шагов при поиске описанными в методичке алгоритмами.
*/


class Search
{
    public $searchArray;
    public $countSteps = 0;

    public function __construct($searchArray)
    {
        $this->searchArray = $searchArray;
    }

    public function linearSearch($num)
    {
        $this->countSteps = 1;
        $count = count($this->searchArray);

        for ($i = 0; $i < $count; $i++) {
            if ($this->searchArray[$i] == $num) return $i;
            elseif ($this->searchArray[$i] > $num) return null;
            $this->countSteps++;
        }
        return null;
    }

    public function binarySearch($num)
    {
        $this->countSteps = 1;
        //определяем границы массива
        $left = 0;
        $right = count($this->searchArray) - 1;

        while ($left <= $right) {

            //находим центральный элемент с округлением индекса в меньшую сторону
            $middle = floor(($right + $left) / 2);
            //если центральный элемент и есть искомый   
            if ($this->searchArray[$middle] == $num) {
                return $middle;
            } elseif ($this->searchArray[$middle] > $num) {
                //сдвигаем границы массива до диапазона от left до middle-1
                $right = $middle - 1;
            } elseif ($this->searchArray[$middle] < $num) {
                $left = $middle + 1;
            }

            $this->countSteps++;
        }
        return null;
    }

    public function interpolationSearch($num)
    {
        $this->countSteps = 1;

        $start = 0;
        $last = count($this->searchArray) - 1;

        while (($start <= $last) && ($num >= $this->searchArray[$start])
            && ($num <= $this->searchArray[$last])
        ) {

            $pos = floor($start + (
                (($last - $start) / ($this->searchArray[$last] - $this->searchArray[$start]))
                * ($num - $this->searchArray[$start])
            ));
            if ($this->searchArray[$pos] == $num) {
                return $pos;
            }

            if ($this->searchArray[$pos] < $num) {
                $start = $pos + 1;
            } else {
                $last = $pos - 1;
            }
            
            $this->countSteps++;
        }

        return null;
    }
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
//////////////////////////////////////////////////////////////////////////////


function displayArr($arr)
{
    foreach ($arr as $key => $value) {
        echo $key . ' => ' . $value . '; <br>';
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


$arr = getRandomArray(5000);
heapSort($arr);
//displayArr($arr);


$search = new Search($arr);
$key = $search->linearSearch(20);
echo 'Найдено с индексом - ' . $key . '<br>';
echo 'Количество шагов при линейном поиске - ' . $search->countSteps . '<hr>';


$key = $search->binarySearch(20);
echo 'Найдено с индексом - ' . $key . '<br>';
echo 'Количество шагов при бинарном поиске - ' . $search->countSteps . '<hr>';

$key = $search->interpolationSearch(20);
echo 'Найдено с индексом - ' . $key . '<br>';
echo 'Количество шагов при интерполяционном поиске - ' . $search->countSteps . '<hr>';
