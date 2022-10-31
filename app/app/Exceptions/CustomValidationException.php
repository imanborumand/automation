<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;


/**
 * Class CustomValidationException
 *
 * @package App\Exceptions
 */
class CustomValidationException extends Exception
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
	 * @var array
	 */
	public array $errorArray;


    /**
     * CustomValidationException constructor.
     *
     * @param string $message
     * @param array  $errorArray
     * @param int    $httpCode
     * @param int    $statusCode
     */
    public function __construct( string $message = "", array $errorArray = [], int $httpCode = Response::HTTP_BAD_REQUEST , int $statusCode = STATUS_CODE_BAD_REQUEST)
	{

		parent::__construct($message, $httpCode);
		$this->errorMessage = $message;
		$this->errorHttpCode = $httpCode;
		$this->errorStatusCode = $statusCode;
		$this->errorArray = $errorArray;
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

		return $this->validationErrorResponse($this->errorArray,$this->errorMessage)
					->setHttpCode($this->errorHttpCode)
					->setStatusCode($this->errorStatusCode)
					->response();
	}
}
