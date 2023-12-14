<?php

namespace sprint\helpers;

class DataTable
{
    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public static function orderDt($request, $columns)
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '' . $column['db'] . ' ' . $dir;
                }
            }

            if (count($orderBy)) {
                $order = ' ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    public static function filterDt($request, $columns)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '' && strlen($request['search']['value']) > 0) {

            $str = trim($request['search']['value']);

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    if (!empty($column['db'])) {
                        $globalSearch[] = "" . $column['db'] . " LIKE '%" . $str . "%' ";
                    }
                }
            }
        }

        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if (
                    $requestColumn['searchable'] == 'true' &&
                    $str != ''
                ) {
                    if (!empty($column['db'])) {
                        $columnSearch[] = "" . $column['db'] . " LIKE '%" . $str . "%' ";
                    }
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = ' AND ' . $where;
        }

        return $where;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    public static function pluck($a, $prop)
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (empty($a[$i][$prop])) {
                continue;
            }
            //removing the $out array index confuses the filter method in doing proper binding,
            //adding it ensures that the array data are mapped correctly
            $out[$i] = $a[$i][$prop];
        }

        return $out;
    }

    public static function filter($alias)
    {
        $filter = [$alias . ".id > 0", $alias . ".deleted_at IS NULL"];

        $provinceArray = $_SESSION['user']['isSeller'] > 0 ? [$_SESSION['user']['province']] : $_POST["province"] ?? [];
        $districtArray = $_SESSION['user']['isSeller'] > 0 ? [$_SESSION['user']['district']] : $_POST["district"] ?? [];
        $dateFrom = $_POST["dateFrom"] ?? null;
        $dateTo = $_POST["dateTo"] ?? null;
        $campaign = $_POST["campaign"] ?? null;

        $province = array_map(function ($v) {
            return "'{$v}'";
        }, $provinceArray);

        $district = array_map(function ($v) {
            return "'{$v}'";
        }, $districtArray);

        $filter[] = !empty($provinceArray) ? $alias . ".province IN(" . implode(", ", $province) . ")" : null;
        $filter[] = !empty($districtArray) ? $alias . ".district IN(" . implode(", ", $district) . ")"  : null;
        #Filtering By Campain
        if ($campaign != null && $campaign != "All") {
            $dates = explode("=", $campaign);
            $filter[] = $alias . ".created_at >= DATE('" . substr($dates[0], 0, 10) . "')";
            $filter[] = $alias . ".created_at <= DATE('" . substr($dates[1], 0, 10) . "')";;
        }
        $filter[] = !empty($dateFrom) ? $alias . ".created_at >= '" . $dateFrom . "'" : null;
        $filter[] = !empty($dateTo) ? $alias . ".created_at <= '" . $dateTo . "'" : null;


        $filter = array_filter($filter);

        return !empty($filter) ? implode(" AND ", $filter) : "";
    }
}
