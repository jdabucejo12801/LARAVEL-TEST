<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;


class ApiFilter {
    protected $safeParams = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq','gt','lt']
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code'
    ];

   protected function operatorMap($operator)
{
    switch ($operator) {
        case 'eq':
            return '=';
        case 'lt':
            return '<';
        case 'lte':
            return '<=';
        case 'gt':
            return '>';
        case 'gte':
            return '>=';
        default:
            return null;
    }
}
    public function transform(Request $request) {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            } 
            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if(isset($query[$operator])) {
                    $eloQuery[] = [$column, self::operatorMap($operator), $query[$operator]];
            
            }
            return $eloQuery;
        }
}   
}
}