<?php


namespace App\Services\Contracts\Basic;


interface CreatorServiceInterface
{
    /**
     * Prepares data to be stored in database
     * @param array $data data from request->validated()
     * @return mixed
     */
    public function store(array $data);
}
