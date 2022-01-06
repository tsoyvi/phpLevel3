<?php

// 
///////// Вывод ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
///////////////////////


class Node
{
    public $operator;
    public $left;
    public $right;

    public function __construct($operator, $left = null, $right = null)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }
}


class Parser
{
    public $index = 0;
    public $error = '';


    public function skip($strExp)
    {
        while ($this->index > strlen($strExp))
            $this->index++;
        return ($this->index < strlen($strExp)) ? $this->index : -1;
    }

    public function parse($strExp)
    {
        $this->index = 0;
        return $this->parseE($strExp);
    }


    public function parseE($strExp)
    {

        $t1 = $this->parseT1($strExp);
        if ($this->skip($strExp) < 0) {

            return $t1;
        }

        switch ($strExp[$this->index]) {
            case "+":
                $this->index++;
                $node = new Node('+', $t1, $this->parseE($strExp));
                return $node;
            case "-":
                $this->index++;
                $node = new Node('-', $t1, $this->parseE($strExp));
                return $node;
        }
        $this->error .= "<br>n=" . $this->index . "> parseE: no + or - after term T1";
        return $t1;
    }


    public function parseT1($strExp)
    {
        $t2 = $this->parseT2($strExp);
        if ($this->skip($strExp) < 0)
            return $t2;

        switch ($strExp[$this->index]) {
            case "*":
                $this->index++;
                $node = new Node('*', $t2, $this->parseT1($strExp));
                return $node;
            case "/":
                $this->index++;
                $node = new Node('/', $t2, $this->parseT1($strExp));
                return $node;
        }

        return $t2;
    }

    public function parseT2($strExp)
    {
        if ($this->skip($strExp) < 0) {
            $this->error .= "<br>n=" . $this->index . "> parseT2: empty term";
            return;
        }

        if ($strExp[$this->index] == "-") {
            $this->index++;
            $node = new Node('M', $this->parseT3($strExp));
            return $node;
        }

        return $this->parseT3($strExp);
    }


    public function parseT3($strExp)
    {
        if ($this->skip($strExp) < 0) {
            $this->error .= "<br>n=" . $this->index . "> parseT3: empty after unary minus";
            return;
        }

        if ($strExp[$this->index] == "(") {
            $this->index++;
            $expr = $this->parseE($strExp);
            if ($this->skip($strExp) < 0 || $strExp[$this->index] !== ")")
                $this->error .= "<br>n=" . $this->index . "> parseT3: no close bracket )";
            else
                $this->index++;
            return $expr;
        }

        if ($this->isdigit($strExp, $this->index)) {

            $indexOld = $this->index;
            do  $this->index++;
            while ($this->index < strlen($strExp)  && $this->isdigit($strExp, $this->index));

            if ($this->index < strlen($strExp) && $strExp[$this->index] == ".") {
                $this->index++;
                while ($this->index < strlen($strExp)  && $this->isdigit($strExp, $this->index))
                    $this->index++;
            }
            $node = new Node('N', substr($strExp, $indexOld, $this->index - $indexOld));
            return $node;
        }

        $this->error .= "<br>n=" . $this->index . "> parseT3: unknown error";
        return;
    }

    public function isdigit($strExp, $index)
    {
        return $strExp[$index] >= "0" && $strExp[$index] <= "9";
    }


    public function calc($result)
    {
        switch ($result->operator) {
            case "N":
                return $result->left;
            case "+":
                return $this->calc($result->left) + $this->calc($result->right);
            case "-":
                return $this->calc($result->left) - $this->calc($result->right);
            case "*":
                return $this->calc($result->left) * $this->calc($result->right);
            case "/":
                return $this->calc($result->left) / $this->calc($result->right);
            case "M":
                return -$this->calc($result->left);
        }
    }

    public function get($result)
    {
        switch ($result->operator) {
            case "N":
                return $result->left;
            case "+":
                return "(" . $this->get($result->left) . "+" . $this->get($result->right) . ")";
            case "-":
                return "(" . $this->get($result->left) . "-" . $this->get($result->right) . ")";
            case "*":
                return "(" . $this->get($result->left) . "*" . $this->get($result->right) . ")";
            case "/":
                return "(" . $this->get($result->left) . "/" . $this->get($result->right) . ")";
            case "M":
                return "-" . $this->get($result->left);
        }
    }
}



$parser = new Parser();
$strExprExpression = '-6.5*2+4/5';
$result = $parser->parse($strExprExpression);


echo 'Математическое выражение ' . $strExprExpression;
echo '<br>';

echo $parser->get($result) . ' = '; // (-6.5*2)+(4/5))
echo $parser->calc($result) . '<br>'; // -12.2
echo $parser->error . '<br>';



// За основу взята логика с сайта https://synset.com/logic/ru/intro/01_parser.html
