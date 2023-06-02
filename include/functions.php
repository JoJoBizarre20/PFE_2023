<?php

function CountDateWithTodayOnCollection($sourceArray,$columnNAme,$operator,$operand=null)
{
    $d=new DateTime();
    $d->setTime(0, 0, 0);
    $operand=is_null($operand)?$d:$operand;
    $count = 0;
    foreach($sourceArray as $row)
    {
        if(evaluateComparison(new DateTime($row[$columnNAme]),$operator,$operand))
        {
            $count++;
        }
    }

    return $count;
}

function evaluateComparison($leftOperand, $operator, $rightOperand) {
    switch ($operator) {
        case '>':
            return $leftOperand > $rightOperand;
        case '>=':
            return $leftOperand >= $rightOperand;
        case '<':
            return $leftOperand < $rightOperand;
        case '<=':
            return $leftOperand <= $rightOperand;
        case '==':
            return $leftOperand == $rightOperand;
        case '!=':
            return $leftOperand != $rightOperand;
        default:
            throw new Exception("Invalid operator: $operator");
    }
}

function CountDatesInThisWeekOnCollection($sourceArray,$columnNAme)
{
    $count = 0;
    $currentDate = new DateTime();
    // Set the time to the beginning of the day
    $currentDate->setTime(0, 0, 0);

    // Get the start and end dates of the current week
    $weekStart = clone $currentDate;
    $weekStart->modify('this week');

    $weekEnd = clone $weekStart;
    $weekEnd->modify('+6 days');
    foreach($sourceArray as $row)
    {
        $dateToCheckObject = new DateTime( $row[$columnNAme]);
        // Check if the date is within the current week
        if ($dateToCheckObject >= $weekStart && $dateToCheckObject <= $weekEnd) {
            $count++;
        } 
    }
    return $count;

}

function logDate($datestring)
{
    return substr($datestring,0,10);
}


function dd($variable)
{
    d($variable);   
    exit;
}
function d($variable)
{
    $highlightedVariable = highlight_string("<?php\n" . var_export($variable, true), true);
    $css = '<style>
        .code-block {
            background-color: #f4f4f4;
            padding: 10px;
        }
    </style>';
    

    $backtrace = debug_backtrace();
    $caller = $backtrace[1]; // Get information about the calling file and line

    echo $css;


    echo '<pre id="code-block-content" >';
    echo 'Called from: ' . $caller['file'] . ' on line ' . $caller['line'] . PHP_EOL;
    echo $highlightedVariable;
    echo '</pre>';
    echo '</div>';
}