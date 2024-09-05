<?php
namespace Core;

Class Util {
    
    function binarySearchInSortedArray($array, $target, $key) {
        $left = 0;
        $right = count($array) - 1;

        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);
            $currentValue = $array[$mid][$key];

            if ($currentValue === $target) {
                return $array[$mid];  // Return the associative array where the target value is found
            }

            if ($currentValue < $target) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }
        return null;  // Return null if the target value is not found
    }

 

    function binarySearchForFirstOccurrence($array, $target,$key) {
        $left = 0;
        $right = count($array) - 1;
        $result = -1;

        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);

            if ($array[$mid][$key] === $target) {
                $result = $mid;  // Found target, but keep searching in the left half for the first occurrence
                $right = $mid - 1;
            } elseif ($array[$mid][$key] < $target) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return $result;
    }

    function binarySearchForMultipleObjectInSortedOrder($array, $target,$key){
        $result = [];
        $index = $this->binarySearchForFirstOccurrence($array, $target,$key);

        for ($i = $index; $i < count($array) && $array[$i][$key] === $target; $i++) {
            $result[] = $array[$i];
        }
        return $result;
    }
}