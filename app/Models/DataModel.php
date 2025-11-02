<?php

//Example usage of join
/*  getJoinData('users', [
                        ['profiles', 'users.id', 'profiles.user_id']
                        ]);
    getJoinData('orders', [
                        ['customers', 'orders.customer_id', 'customers.id'],
                        ['products', 'orders.product_id', 'products.id']
                        ]);
    getJoinData('orders', [
                        ['customers', 'orders.customer_id', 'customers.id']
                        ], ['orders.*', 'customers.name'], ['orders.id' => 5], true);*/

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class DataModel extends Model
{   
    function addLog($userId, $action, $details = null)
    {
        DB::table('logs')->insert([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    function getFilteredData($main, $joins = [], $where = [], $search = null, $searchColumns = [], $select = ['*'], $orderBy = null, $paginate = null)
    {
        $query = DB::table($main);

        // Apply joins dynamically
        foreach ($joins as $join) {
            $query->join($join[0], $join[1], '=', $join[2]);
        }

        // Apply where filters
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (!empty($value)) {
                    $query->where($key, $value);
                }
            }
        }

        // Apply search filters
        if (!empty($search) && !empty($searchColumns)) {
            $query->where(function ($q) use ($search, $searchColumns) {
                foreach ($searchColumns as $index => $col) {
                    if ($index === 0) {
                        $q->where($col, 'like', "%{$search}%");
                    } else {
                        $q->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });
        }
        if ($orderBy) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->select($select)->get();
    }


    function getJoinData($main, $joins = [], $select = ['*'], $where = null, $single = false, 
                        $orderBy = null, $paginate = null,$startDate = null, $endDate = null,
                        $dateColumn = 'created_at')
    {
        $query = DB::table($main);

        // Apply joins dynamically
        foreach ($joins as $join) {
            // Each join array format: ['table', 'main_column', 'alt_column']
            $query->join($join[0], $join[1], '=', $join[2]);
        }

        // Optional WHERE condition
        if ($where) {
            $query->where($where);
        }

        // Select columns
        $query->select($select);
        if ($orderBy) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        if ($paginate) {
            return $query->paginate($paginate);
        }
        if ($startDate && $endDate) {
            $query->whereBetween($dateColumn, [$startDate, $endDate]);
        }

        // Return one or multiple records
        return $single ? $query->first() : $query->get();
    }
    function getData($table)
    {
        return DB::table($table)->get();
    }
    function getWhere($table, $where, $select = 1, $extra = null)
    {
        $query = DB::table($table);
        
        foreach ($where as $key => $value) {
            $query->where($key, $value);
        }
        
        if ($select == 1) {
            return $query->get()->first();
        } elseif ($select == 2) {
            return $query->get();
        } else {
            return $query->select(DB::raw('COUNT(' . $extra[0] . ') as ' . $extra[1]))->get()->first();
        }

    }
    function insertData($table, $data)
    {
        return DB::table($table)->insert($data);
    }
    function insertRetID($table, $data)
    {
        return DB::table($table)->insertGetId($data);
    }
    function updateData($table, $data, $where)
    {
        return DB::table($table)->where($where)->update($data);
    }
    function deleteData($table, $where)
    {
        return DB::table($table)->where($where)->delete();
    }
}
