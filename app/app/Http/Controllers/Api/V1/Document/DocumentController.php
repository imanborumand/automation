<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Document\AssignDocumentRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Services\Contracts\DocumentServiceInterface;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{

    /**
     * @param DocumentServiceInterface $service
     */
    public function __construct(
        private  readonly DocumentServiceInterface $service
    ){
    }


    /**
     * @param AssignDocumentRequest $request
     * @return JsonResponse
     */
    public function assign( AssignDocumentRequest $request) : JsonResponse
    {
        return $this->apiResponse(
            $this->service->assignDocumentToUser($request->validated())
        );
    }

    public function assignList()
    {
        return $this->apiResponse(
            DocumentResource::collection(
                $this->service->assignList()
            )
        );
    }
}
