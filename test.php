<?php

//     function exclaim($str) {
//         return $str . "! ";
//     }
    
//     function ask($str) {
//         return $str . "? ";
//     }
    
//     function printFormatted($str, $format) {
//         // Call the $format callback function
//         echo $format($str);
//     }
    
//     // Pass "exclaim" and "ask" as callback functions to printFormatted()
//     printFormatted("Hello world", "exclaim");
//     printFormatted("Hello world", "ask");


//     $strings = ["apple", "orange", "banana"=>"This is testing!", "coconut"];
// $lengths = array_map(function($item) {
//     return strlen($item);
// }, $strings);
// print_r($lengths);

$array= array(array(1,2,3),array(4,5,6),array(7,8,9));

// $sum = array_map(function($item){
//     return array_sum($item);
// },$array);
// print_r($sum);

// foreach($array as $key=>$value){
//     $sum[$key]=$sum[$key]+$item[$key];
//     print_r($sum);
// }
// print_r($sum);

// $sum = 0;
// $it_sum = 0;

function sum($array){
    $total=0;
    $columnSums = array_fill(0, count($array[0]), 0);
        foreach($array as $key=>$value){
            foreach($value as $i=>$item){
                // $columnSums[$i] += $item;
                echo $item." ";
            }
            // $col=implode(" ",$columnSums);
            $total += array_sum($value);
            echo array_sum($value);
            echo "\n";
            // echo "\n";
            // print_r($columnSums);
        }
        echo $total;
    }
sum($array);


    // print_r($summ);
    // echo $sum;
    // echo $it_sum;


//  ============Chat GPT===========
// Example 2D array
// $array = [
//     [1, 2, 3],
//     [4, 5, 6],
//     [7, 8, 9]
// ];

// // Initialize an array to store column sums
// $columnSums = array_fill(0, count($array[0]), 0);
// print_r($columnSums);

// // Iterate through each row
// foreach ($array as $row) {
//     // Iterate through each column
//     foreach ($row as $colIndex => $value) {
//         // Add the value to the corresponding column sum
//         $columnSums[$colIndex] += $value;
//     }
// }

// // Output the column sums


// // ========black boxx============

// // Sample 2D array
// $array = array(
//     array(1, 2, 3),
//     array(4, 5, 6),
//     array(7, 8, 9)
// );

// // Calculate the sum of each column
// $columnSums = array();
// foreach (range(0, count($array[0]) - 1) as $colIndex) {
//     $columnSums[] = array_sum(array_column($array, $colIndex));
// }

// print_r($columnSums);

// Output:
// Array
// (
//     [0] => 12
//     [1] => 15
//     [2] => 18
// )


// Given table as a 2D array
// $table = array(
//     array(1, 2, 3, 6),
//     array(4, 5, 6, 15),
//     array(7, 8, 9, 24),
//     array(12, 15, 18, 45)
// );

// // Calculate and display the sums
// for ($i = 0; $i < count($table); $i++) {
//     $rowSum = array_sum($table[$i]);
//     echo "Sum of row " . ($i + 1) . ": $rowSum\n";
// }

// // Access the last row (lower line)
// $lastRow = $table[count($table) - 1];
// echo "Last row: " . implode(" ", $lastRow) . "\n";


?>






