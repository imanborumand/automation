<?php


namespace App\Services\Contracts\Basic;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface UpdaterServiceInterface
{
    /**
     * Prepares data to update specified resource by $id
     * @param Model|EloquentBuilder|QueryBuilder|int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);
}
