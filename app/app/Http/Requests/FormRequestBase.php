<?php namespace App\Http\Requests;

use App\Exceptions\CustomValidationException;
use App\Traits\GetBrowserAndOsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * Class FormRequestBase
 * all FormRequest must by extend this class
 * @package App\Http\Requests
 */
abstract  class FormRequestBase extends FormRequest
{



    public function __construct( array $query = [] , array $request = [] , array $attributes = [] , array $cookies = [] , array $files = [] , array $server = [] , $content = null )
    {

        parent::__construct( $query , $request , $attributes , $cookies , $files , $server , $content );
    }


    /**
     * @param Validator $validator
     * @return void
     * @throws CustomValidationException
     * @throws ValidationException
     */
    protected function failedValidation( Validator $validator ) : void
    {

        if( strpos( request()->url() , 'api/' ) ) {
            throw new CustomValidationException("not_valid_data",array_values($validator->errors()->toArray()), Response::HTTP_BAD_REQUEST,STATUS_CODE_BAD_REQUEST);
        }

        parent::failedValidation( $validator ); // TODO: Change the autogenerated stub
    }




}
