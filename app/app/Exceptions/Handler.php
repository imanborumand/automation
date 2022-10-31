<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException as HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use UnexpectedValueException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    protected array $httpCodes = [
        200,
        201,
        404,
        500,
        401,
        405,
        403,
        400,
        422,
    ];


    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() : void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }


    /**
     * @param           $request
     * @param Throwable $e
     * @return \Illuminate\Http\Response|JsonResponse|Response
     * @throws Throwable
     */
    public function render( $request , Throwable $e ) : \Illuminate\Http\Response|JsonResponse|Response
    {
//        if ($e instanceof HttpException) {
//            if($e->getMessage() == "Unauthenticated!"){
//                return response()->json([
//                    'error' => $e->getMessage()
//                ], $this->getStatusCode(403));
//            }
//
//        }
        if ($request->acceptsHtml() and str_contains( $request->url() , 'api/' )  == false) {
            return parent::render( $request , $e );
        }

        $className = str_replace('"', "", get_class($e));
        if (
            $this->isHttpException($e) ||
            !(substr( $className, 0, 14) == CUSTOM_EXCEPTIONS_PATH) &&
            !($e instanceof UnexpectedValueException)
        ) {

            $httpCode = $this->getResponseHttpCode($e);
            $statusCode = $this->getStatusCode($httpCode);

            throw  new  CustomApiException($e->getMessage(),$httpCode,$statusCode);
        }

        return parent::render( $request , $e );
    }


    /**
     * @param $e
     * @return bool|int
     */
    private function getResponseHttpCode( $e) : bool|int
    {

        $exceptionCode = $e->getCode();
        $notHttpExceptionCode = $this->checkException($e);

        if($notHttpExceptionCode){
            return $notHttpExceptionCode;
        }

        if($this->isHttpException($e) && $e->getStatusCode() != 0 && in_array($e->getStatusCode(),$this->httpCodes) ) {
            return $e->getStatusCode();

        }elseif(isset($exceptionCode) && $exceptionCode != 0  && in_array($exceptionCode,$this->httpCodes)) {
            return $exceptionCode;
        }
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }


    /**
     * @param $httpCode
     * @return int
     */
    private function getStatusCode($httpCode) : int
    {
        $mapping = [
            '200' =>   STATUS_CODE_OK,
            '201' =>   STATUS_CODE_CREATED,
            '404' =>   STATUS_CODE_NOT_FOUND,
            '500' =>   STATUS_CODE_INTERNAL_SERVER_ERROR,
            '401' =>   STATUS_CODE_UNAUTHENTICATED,
            '405' =>   STATUS_CODE_METHOD_NOT_ALLOWED,
            '403' =>   STATUS_CODE_FORBIDDEN,
            '400' =>   STATUS_CODE_BAD_REQUEST,
            '422' =>   STATUS_CODE_UNPROCESSABLE_ENTITY,
        ];

        return  $mapping[$httpCode] ?? STATUS_CODE_INTERNAL_SERVER_ERROR;
    }


    /**
     * @param $e
     * @return bool|int
     */
    private function checkException($e) : bool|int
    {
        if ($e instanceof AuthenticationException) {
            return 401;
        }
        return  false;
    }

}
