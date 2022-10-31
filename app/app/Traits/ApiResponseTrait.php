<?php namespace App\Traits;

/*
 * this trait creates api response schema
 */

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;



trait ApiResponseTrait
{

    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var string
     */
    protected string $responseMessage = ""; // custom message
    /**
     * @var bool
     */
    protected bool $status     = true; // false or true
    /**
     * @var int
     */
    protected  int $httpCode   = Response::HTTP_OK; // http code example 400, 401, 200
    /**
     * @var int
     */
    protected int $statusCode = STATUS_CODE_OK; // one number code big then 1000
    /**
     * @var array
     */
    protected array $result = [];
    /**
     * @var array
     */
    protected array $errors = [];

    protected array $paginate = [];


    public function setData($data = []) : static
    {
        if (! is_array($data) or isset($data['list'])) {
            if(isset($data['list'])) {
                $data['list'] = $this->checkList($data['list']);
            } else {
                $data = $this->checkList($data);
            }
        }


        $this->data = $data;
        return $this;
    }



    /**
     * if list in special key remove paginage
     * @param $data
     * @return mixed
     */
    private function checkList( $data) : mixed
    {
        $dataArray = collect($data)->toArray();
        if(isset($dataArray['pagination']) and isset($dataArray['data'])) {
            $this->paginate = [
                'total'         => $dataArray['pagination']['total'] ,
                'per_page'      => $dataArray['pagination']['per_page'] ,
                'current_page'  => $dataArray['pagination']['current_page'] ,
                'last_page' => $dataArray['pagination']['total_pages'] ,
                'next_page_url' => (string) $dataArray['pagination']['next_page_url'],
                'last_page_url' => "" ,
                'prev_page_url' => (string) $dataArray['pagination']['prev_page_url'] ,
            ];
            return  $dataArray['data'];
        }

        if (isset($dataArray['current_page'])) {
            $this->paginate = [
                'total'         => $dataArray['total'] ,
                'per_page'      => $dataArray['per_page'] ,
                'current_page'  => $dataArray['current_page'] ,
                'next_page_url' => $dataArray['next_page_url'] ,
                'last_page_url' => $dataArray['last_page_url'] ,
                'prev_page_url' => $dataArray['prev_page_url'] ,
                'last_page' => $dataArray['last_page'] ,
            ];

            //remove default laravel paginate key and merge new paginate
            $data = array_merge( [] , array_diff_key( $dataArray , array_fill_keys( [
                 'current_page' ,
                 'first_page_url' ,
                 'from' ,
                 'last_page' ,
                 'last_page_url' ,
                 'next_page_url' ,
                 'path' ,
                 'per_page' ,
                 'to' ,
                 'total' ,
                 'prev_page_url' ,
             ] , null ) ) );
            return $data['data'];
        }

        return $data;
    }



    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @param string $responseMessage
     * @return $this
     */
    public function setResponseMessage(string $responseMessage) : static
    {
        if($responseMessage != '') {
            $this->responseMessage = $responseMessage;
        }
        return $this;
    }


    /**
     * @return null
     */
    public function getResponseMessage()
    {
        $generalExceptionConfig = config('general.exceptions_messages.en');

        if (array_key_exists($this->responseMessage, $generalExceptionConfig)) {
            return $generalExceptionConfig[$this->responseMessage];
        }

        return $this->responseMessage;
    }


    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status) : static
    {
        $this->status = $status;
        return $this;
    }


    /**
     * @return bool
     */
    public function getStatus() : bool
    {
        return $this->status;
    }


    /**
     * @param int $httpCode
     * @return $this
     */
    public function setHttpCode( int $httpCode) : static
    {
        $this->httpCode = $httpCode;
        return $this;
    }


    /**
     * @return int
     */
    public function getHttpCode() : int
    {
        return $this->httpCode;
    }


    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode) : static
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }


    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors($errors = []) : static
    {
        $this->errors = $errors;
        return $this;
    }


    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }


    /**
     * @param string|null $message
     * @param int         $statusCode
     * @param int         $httpCode
     * @param bool        $status
     * @return $this
     */
    public function setParams( string|null $message, int $statusCode, int $httpCode, bool $status) : static
    {
        $this->setStatusCode($statusCode)
             ->setHttpCode($httpCode)
             ->setResponseMessage($message)
             ->setStatus($status);
        return $this;
    }


    /**
     * @return $this
     */
    public function setResult() : static
    {
        $this->result['data']        = $this->getData();
        $this->result["message"]     = $this->getResponseMessage();
        $this->result["status"]    	 = $this->getStatus();
        $this->result["status_code"] = $this->getStatusCode();
        $this->result['errors']      = $this->getErrors();
        $this->result['paginate']    = $this->paginate;
        return $this;
    }


    /**
     * @return array
     */
    public function getResult() : array
    {
        return $this->result;
    }


    /**
     * @return JsonResponse
     */
    public function response() : JsonResponse
    {
        $this->setResult();
        return response()->json($this->getResponseData($this->getResult()), $this->getHttpCode());
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function successResponse(string $message = null) : static
    {
        $message = $message ?? 'success!';
        $this->setParams($message,STATUS_CODE_OK,Response::HTTP_OK,true);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function createdResponse(string $message = null) : static
    {
        $message = $message ?? 'item created';
        $this->setParams($message,STATUS_CODE_CREATED,Response::HTTP_CREATED,true);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function notFoundResponse(string $message = null) : static
    {
        $message = $message ?? 'not Found!';
        $this->setParams($message,STATUS_CODE_NOT_FOUND,Response::HTTP_NOT_FOUND,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function  failedResponse(string $message = null) : static
    {
        $message = $message ?? 'failed Response';
        $this->setParams($message,STATUS_CODE_INTERNAL_SERVER_ERROR,Response::HTTP_INTERNAL_SERVER_ERROR,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function internalErrorResponse(string $message = null) : static
    {
        $message = $message ?? 'internal Error' ;

        $this->setParams($message,STATUS_CODE_INTERNAL_SERVER_ERROR,Response::HTTP_INTERNAL_SERVER_ERROR,false);
        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function queryErrorResponse(string $message = null)
    {
        $message = $message ?? 'query Error ';
        $this->setParams($message,STATUS_CODE_INTERNAL_SERVER_ERROR,Response::HTTP_INTERNAL_SERVER_ERROR,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function notAuthenticatedResponse(string $message = null)
    {
        $message = $message ?? 'not Authenticated';
        $this->setParams($message,STATUS_CODE_UNAUTHENTICATED,Response::HTTP_UNAUTHORIZED ,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function methodNotAllowedHttpException(string $message = null)
    {
        $message = $message ?? 'method Not Allowed';
        $this->setParams($message,STATUS_CODE_METHOD_NOT_ALLOWED,Response::HTTP_METHOD_NOT_ALLOWED,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function notAuthorizedResponse(string $message = null) : static
    {
        $message = $message ?? 'not Authorized Response';
        $this->setParams($message,STATUS_CODE_FORBIDDEN, Response::HTTP_FORBIDDEN,false);
        return $this;
    }


    /**
     * @param string|null $message
     * @return $this
     */
    public function badRequestResponse(string $message = null) : static
    {
        $message = $message ?? 'error bad request!';
        $this->setParams($message,STATUS_CODE_BAD_REQUEST,Response::HTTP_BAD_REQUEST,false);
        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function unprocessableEntityResponse(string $message = null) : static
    {
        $message = $message ?? 'unprocessable Entity Response!';
        $this->setParams($message,STATUS_CODE_UNPROCESSABLE_ENTITY,Response::HTTP_UNPROCESSABLE_ENTITY,false);
        return $this;
    }

    /**
     * @param array       $errors
     * @param string|null $message
     * @return $this
     */
    public function validationErrorResponse( array $errors, ?string $message = '') : static
    {

        $message = $message ? config('general.exceptions_messages.en.' . $message) : "error validation";
        $this->setErrors($errors);

        $this->setParams($message,STATUS_CODE_BAD_REQUEST,Response::HTTP_BAD_REQUEST ,false);

        return $this;
    }


    /**
     * @param             $data
     * @param bool        $status
     * @param int         $statusCode
     * @param int         $httpCode
     * @param string|null $message
     * @param array       $errors
     * @return $this
     */
    public function customResponse($data, bool $status = true, int $statusCode = STATUS_CODE_OK, int $httpCode = Response::HTTP_OK, string $message =null, array $errors = []) : static
    {
        $message = $message ?? "custom Response";
        $this->setData($data);
        $this->setErrors($errors);
        $this->setParams($message,$statusCode,$httpCode,$status);
        return $this;
    }


    /**
     * @param array $result
     * @return array
     */
    private function getResponseData(array $result) : array
    {
        if(isset($result['data']['message']) &&
            isset($result['data']['status'])  &&
            isset($result['data']['errors'])  &&
            isset($result['data']['status_code']) &&
            isset($result['data']['data'])){

            return $result['data'];

        }else{
            $result['data'] = $result['data'] ?? [];
            return $result ;
        }
    }

}
