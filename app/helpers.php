<?php
/**
 * Created by PhpStorm.
 * User: ramses
 * Date: 12/12/18
 * Time: 8:41 AM
 */

if (! function_exists('params_as_array')) {
  function params_as_array($params, $startingFrom = 0)
  {
    $totalFields = count($params);
    if ($totalFields == $startingFrom + 1 && is_array($params[$startingFrom])) {
      return $params[$startingFrom];
    } else {
      $result = [];
      for ($i = $startingFrom, $totalFields = $totalFields; $i < $totalFields; $i++)  {
        $result[] = $params[$i];
      }
      return $result;
    }
  }
}