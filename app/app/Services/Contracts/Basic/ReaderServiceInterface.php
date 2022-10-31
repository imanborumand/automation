<?php


namespace App\Services\Contracts\Basic;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface ReaderServiceInterface
{

    /**
     * Returns listing of specified resource
     * @param Request|FormRequest|null $request
     * @return mixed
     */
    public function list($request = null);

    /**
     * Returns data of specified resource
     * @param Model|EloquentBuilder|QueryBuilder|int $id
     * @return mixed
     */
    public function show($id);
}
