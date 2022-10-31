<?php


namespace App\Services\Contracts\Basic;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface ReadByRequestServiceInterface
{
    /**
     * Returns listing of specified resource based on parameters in the request
     * @param Request|FormRequest $request
     * @return mixed
     */
    public function listBy($request);
}
