<?php

namespace App\Services\Document;


use App\Repositories\Contracts\Document\DocumentUserRepositoryInterface;
use App\Services\Contracts\DocumentUserServiceInterface;

class DocumentUserService implements DocumentUserServiceInterface
{


    /**
     * @param DocumentUserRepositoryInterface $repository
     */
    public function __construct(
        private readonly DocumentUserRepositoryInterface $repository
    ){
    }


    public function store( array $data )
    {
        return $this->repository->baseQuery()->create($data);
    }


    public function destroy( $id )
    {
        // TODO: Implement destroy() method.
    }


    public function list( $request = null )
    {
        // TODO: Implement list() method.
    }


    public function show( $id )
    {
        // TODO: Implement show() method.
    }


    public function update( $id , array $data )
    {
        // TODO: Implement update() method.
    }

}
