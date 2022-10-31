<?php


namespace App\Repositories\Contracts\Criteria;


use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

interface BaseCriterionInterface
{
    /**
     * @param Model|QueryBuilder|EloquentBuilder $model
     * @param BaseRepositoryInterface $repository
     * @return Model|QueryBuilder|EloquentBuilder
     */
    public function apply($model, BaseRepositoryInterface $repository);


    /*---------------- Sample Implementation -----------------*/
    /*
      public function apply($model, BaseRepositoryInterface $repository){
            if(request()->has('name_filter')){
                return $model->where('name', $request->get('name_filter'));
            }
      }
     */
}
