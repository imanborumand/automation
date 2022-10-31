<?php


namespace App\Services\Contracts\Basic;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface RestorerServiceInterface
{
    /**
     * Restores the soft-deleted or archived resource by $id
     *
     * @param Model|EloquentBuilder|QueryBuilder|int $id
     * @return mixed
     */
    public function restore($id);
}
