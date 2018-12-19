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
    $selectedFields = $request->input('select', $request->input('fields', 'entities.*,contents.*'));
    $selectedFilters = $request->input('filters', $request->input('filter', $request->input('where', null)));

    $result = [
        "entities" => [],
        "contents" => []
    ];

    $fields = explode(",", $selectedFields);
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
        $result[$fieldParts[0]] = [];
      }
      $result[$fieldParts[0]][] = $fieldParts[1];
    }


    if (count($result['entities']) > 0 && $result['entities'][0] != '*') {
      $query->select($result['entities']);
    } else {
      $query->select('entities.*');
    }
    if (count($result['contents']) > 0 && $result['contents'][0] != '*') {
      $query->withContents($result['contents']);
    } else {
      $query->withContents();
    }
    unset($result['entities']);
    unset($result['contents']);
    foreach (array_keys($result) as $tableData) {
      if (count($result[$tableData]) > 0 && $result[$tableData][0] != '*') {
        $dataFields = $result[$tableData];
        $query->with([$tableData => function ($select) use ($dataFields, $tableData) {
          $dataFields[] = 'id';
          $select->addSelect( $dataFields);
        }]);
      } else {
        $query->with($tableData);
      }
    }

    if ($selectedFilters !== NULL) {
      $filters = explode(",", $selectedFilters);
      foreach ($filters as $filter) {
        $filterParts = explode(":", $filter);
        if ($filterParts[0] == "model") {
          $query->ofModel($filterParts[1]);
        } else if ($filterParts[0] == "published") {
          $query->isPublished();
        } else {
          $query->where($filterParts[0], $filterParts[1]);
        }
      }
    }
    
    return $query;
  }
}