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

  function process_querystring(\Illuminate\Database\Eloquent\Builder $query, \Illuminate\Http\Request $request)
  {
    $querySelect = $request->input('select', $request->input('fields', 'entities.*,contents.*'));
    $queryWhere = $request->input('filters', $request->input('filter', $request->input('where', null)));
    $queryOrder = $request->input('order', $request->input('sort',$request->input('orderby', null)));
    $queryTake = $request->input('take', null);
    $querySkip = $request->input('skip', null);

    $select = [
        "entities" => ['entities.id'],
        "contents" => []
    ];
    $joins = [];


    // Selects: Group selects per type because each one of them needs to be processed differently, the entity fields
    // are directly selected, the content fields are attached used the withContents special method, and any other
    // needs to find the related model
    $fields = explode(",", $querySelect);
    foreach ($fields as $field) {
      $fieldParts = to_full_field_name($field);
      if (!isset( $select[$fieldParts['table']]))  $select[$fieldParts['table']] = [];
      $select[$fieldParts['table']][] = $fieldParts['field'];
    }

    if (count($select['entities']) > 0 && $select['entities'][0] != '*') {
      $query->select($select['entities']);
    } else {
      $query->select('entities.*');
    }
    if (count($select['contents']) > 0 && $select['contents'][0] != '*') {
      $query->withContents($select['contents']);
    } else if (isset($select['contents'][0]) && $select['contents'][0] != '*') {
      $query->withContents();
    }
    unset($select['entities']);
    unset($select['contents']);
    foreach (array_keys($select) as $relationName) {
      $relationName = str_singular($relationName);
      $tableName = str_plural($relationName);
      if (count($select[$tableName]) > 0 && $select[$tableName][0] != '*') {
        $dataFields = $select[$tableName];
        $query->with([$relationName => function ($select) use ($dataFields, $relationName) {
          $dataFields[] = 'id';
          $select->addSelect($dataFields);
        }]);
      } else {
        $query->with($relationName);
      }
    }

    // Filters / Wheres
    if ($queryWhere !== NULL) {
      $filters = explode(",", $queryWhere);
      foreach ($filters as $filter) {
        $filterParts = preg_split('/(\:|\<|\>|\!:)/i', $filter, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $fieldParts = to_full_field_name($filterParts[0]);
        if ($fieldParts['table'] != 'entities' && $fieldParts['table'] != 'contents' && !isset($joins[$fieldParts['table']])) {
          $joins[$fieldParts['table']] = true;
          $query->leftJoin($fieldParts['table'], 'entities.id', "{$fieldParts['table']}.id");
        }
        if ($filterParts[0] == "published") {
          $query->isPublished();
        } else {
          if ($filterParts[1] == ':') {$filterParts[1] = '=';}
          if ($filterParts[1] == '!:') {$filterParts[1] = '!=';}
          $query->where($fieldParts['field'], $filterParts[1], $filterParts[2]);
        }
      }
    }

    //Order
    if ($queryOrder !== NULL) {
      $orders = explode(",", $queryOrder);
      foreach ($orders as $order) {
        $orderParts = explode(":", $order);
        $fieldParts = to_full_field_name($orderParts[0]);
        $orderParts[1] = (isset($orderParts[1]) && $orderParts[1] == 'desc') ? 'desc' : 'asc';
        if ($fieldParts['table'] != 'contents') {
          if ($fieldParts['table'] != 'entities' && !isset($joins[$fieldParts['table']])) {
            $joins[$fieldParts['table']] = true;
            $query->leftJoin($fieldParts['table'], 'entities.id', "{$fieldParts['table']}.id");
          }
          $query->orderBy($fieldParts['field'], $orderParts[1]);
        } else {
          $query->orderByContents($fieldParts['field'], $orderParts[1]);
        }
      }
    }
    if ($queryTake) {
      $query->take($queryTake);
    }
    if ($querySkip) {
      $query->skip($querySkip);
    }
    return $query;
  }
  function to_full_field_name($field) {
    $fieldParts = explode(".", $field);
    if (count($fieldParts) == 1) {
      $fieldParts[1] = $fieldParts[0];
      $fieldParts[0] = "entities";
    }
    if ($fieldParts[0] == "e" || $fieldParts[0] == "entity") {
      $fieldParts[0] = "entities";
    }
    if ($fieldParts[0] == "c" || $fieldParts[0] == "content") {
      $fieldParts[0] = "contents";
    }
    if (!isset($select[$fieldParts[0]])) {
      $select[str_plural($fieldParts[0])] = [];
    }
    if ($fieldParts[0] == "entities") {
      $fieldParts[1] = "entities.". $fieldParts[1];
    }
    return [
      "field" => $fieldParts[1],
      "table" => str_plural($fieldParts[0]),
      "relation" => str_singular($fieldParts[0])
    ];
  }
}