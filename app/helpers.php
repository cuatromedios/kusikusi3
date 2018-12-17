<?php
/**
 * Created by PhpStorm.
 * User: ramses
 * Date: 12/12/18
 * Time: 8:41 AM
 */

if (!function_exists('params_as_array')) {
  function params_as_array($params, $startingFrom = 0)
  {
    $totalFields = count($params);
    if ($totalFields == $startingFrom + 1 && is_array($params[$startingFrom])) {
      return $params[$startingFrom];
    } else {
      $result = [];
      for ($i = $startingFrom, $totalFields = $totalFields; $i < $totalFields; $i++) {
        $result[] = $params[$i];
      }
      return $result;
    }
  }

  function deserialize_select(\Illuminate\Database\Eloquent\Builder $query, \Illuminate\Http\Request $request)
  {

    $select = $request->input('select', $request->input('fields', 'entities.*,contents.*'));

    $result = [
        "entities" => [],
        "contents" => []
    ];

    $fields = explode(",", $select);
    foreach ($fields as $field) {
      $fieldParts = explode(".", $field);
      if (count($fieldParts) == 1) {
        $fieldParts[1] = $fieldParts[0];
        $fieldParts[0] = "entities";
      }
      if ($fieldParts[0] == "e") {
        $fieldParts[0] = "entities";
      }
      if ($fieldParts[0] == "c") {
        $fieldParts[0] = "contents";
      }
      if (!isset($result[$fieldParts[0]])) {
        $result[$fieldParts[0] = []];
      }
      $result[$fieldParts[0]][] = $fieldParts[1];
    }

    if ($result['entities'][0] != '*') {
      $query->select($result['entities']);
    } else {
      $query->select('entities.*');
    }
    if ($result['contents'][0] != '*') {
      $query->withContents($result['contents']);
    } else {
      $query->withContents();
    }

    return $query;
  }
}