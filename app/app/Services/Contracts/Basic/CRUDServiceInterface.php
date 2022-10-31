<?php


namespace App\Services\Contracts\Basic;


interface CRUDServiceInterface extends
    CreatorServiceInterface,
    ReaderServiceInterface,
    UpdaterServiceInterface,
    DestroyerServiceInterface
{
    // just an Abstraction
}
