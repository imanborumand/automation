<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;

/**
 * Class CustomNotFoundException
 *
 * @package App\Exceptions
 */
class CustomNotFoundException extends Exception
{
    use  ApiResponseTrait;


    /**
     * @var string|null
     */
    public  ? string $errorMessage;

    /**
     * @var int
     */
    public int $errorHttpCode;
    /**
     * @var int
     */
    public int $errorStatusCode;


    /**
     * CustomNotFoundException constructor.
     *
     * @param string $message
     * @param int    $httpCode
     * @param int    $statusCode
     */
    public function __construct( string $message = "", int $httpCode = Response::HTTP_NOT_FOUND , int $statusCode = STATUS_CODE_NOT_FOUND)
    {
         parent::__construct($message, $httpCode);
		$this->errorMessage = $message;
		$this->errorHttpCode = $httpCode;
		$this->errorStatusCode = $statusCode;
	}


	/**
	 *
	 */
	public function report()
    {
        //
    }


	/**
	 * @return JsonResponse
	 */
	public function render() : JsonResponse
    {

		return $this->notFoundResponse($this->errorMessage)
					->setHttpCode($this->errorHttpCode)
					->setStatusCode($this->errorStatusCode)
					->response();
    }

}
