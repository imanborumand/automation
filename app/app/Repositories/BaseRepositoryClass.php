<?php

namespace App\Repositories;

use App\Models\ModelBase;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Exception;
use App\Models\User;
use App\Repositories\Contracts\Criteria\BaseCriterionInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class BaseRepositoryClass implements BaseRepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var User|Authenticatable|null
     */
    protected $user;

    /**
     * Override this property if you want more or less size.
     * @var integer default value for pagination size
     */
    protected int $pagination = 25;

    /**
     * @var Collection criteria that should be applied on Query results.
     */
    protected Collection $criteria;

    /**
     * @var Dispatcher|null
     */
    protected ?Dispatcher $modelEventDispatcher = null;


    public function __construct()
    {
        $this->model = $this->getModel();
        $this->criteria = collect();
        $this->user = auth()->user();
    }

    /**
     * Determines if $id is an object of model class or is it's ID.
     * @param Model|EloquentBuilder|QueryBuilder $id
     * @return bool
     * @see getModel()
     */
    public function isModel($id): bool
    {
        return is_a($id, $this->getModel());
    }

    /**
     * @inheritDoc
     */
    public function user($user = null): BaseRepositoryInterface
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function pushCriterion(string $criterion)
    {
        if (class_exists($criterion)) {
            $criteriaObject = new $criterion();
            if (is_a($criteriaObject, BaseCriterionInterface::class))
                $this->criteria->push($criteriaObject);
        }
    }

    /**
     * @inheritDoc
     */
    public function pushCriterionInstance(BaseCriterionInterface $criterion)
    {
        $this->criteria->push($criterion);
    }

    /**
     * Applies all existing Criterion in the $criteria collection on the query.
     * @param Builder $query
     * @return mixed
     * @see $criteria
     */
    public function applyCriteria(Builder $query): Builder
    {
        if ($this->criteria->isNotEmpty())
            foreach ($this->criteria as $criterion) {
                $query = $criterion->apply($query, $this);
            }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function baseQuery(array $columns = ['*']): Builder
    {
        $query = $this->model::query()->select($columns);
        return $this->applyCriteria($query);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail($id, array $columns = ['*'])
    {
        return $this->isModel($id)
            ? $id
            : $this->baseQuery($columns)->findOrFail($id);
    }


    /**
     * @inheritDoc
     */
    public function list(array $columns = ['*'])
    {
        return $this->baseQuery($columns)->get();
    }

    /**
     * @inheritDoc
     */
    public function show($id, array $columns = ['*'])
    {

        return $this->isModel($id)
            ? $id
            : $this->findOrFail($id, $columns);
    }

    /**
     * @param array $data
     * @return Builder|Model
     */
    public function store(array $data)
    {
        return $this->baseQuery()->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function update($id, array $data)
    {
        $model = $this->findOrFail($id);
        $model->fill($data);
        $model->push();
        return $model;
    }


    /**
     * @param $id
     * @return bool|mixed|null
     * @throws Exception
     */
    public function destroy($id)
    {
        $model = $this->findOrFail($id);
        return $model->delete();
    }

    /**
     * @inheritDoc
     */
    public function withoutEvents(): BaseRepositoryInterface
    {
        $this->modelEventDispatcher = $this->model::getEventDispatcher();
        $this->model::unsetEventDispatcher();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resetEvents(): BaseRepositoryInterface
    {
        if (!is_null($this->modelEventDispatcher))
            $this->model::setEventDispatcher($this->modelEventDispatcher);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPaginationSize(): int
    {
        return $this->pagination;
    }

    public function getByIdWithRelations(int $id, array $with = []): ModelBase
    {
        $model = $this->baseQuery()->where('id', $id);
        if(count($with) > 0) {
            $model = $model->with($with);
        }
        return $model->first();
    }
}

