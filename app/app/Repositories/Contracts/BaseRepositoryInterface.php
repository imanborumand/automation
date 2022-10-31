<?php


namespace App\Repositories\Contracts;




use App\Models\User\User;
use App\Repositories\Contracts\Criteria\BaseCriterionInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{

    /**
     * Returns the model class for repository
     * @return string Class name of model i.e. Sample::class
     */
    public function getModel(): string;

    /**
     * Sets user that query results depends on, default can be auth()->user()
     * @param User|Authenticatable|null $user
     * @return $this
     */
    public function user($user = null): self;

    /**
     * Gets user that query results depends on, default can be auth()->user()
     * @return Authenticatable|null
     */
    public function getUser(): ?Authenticatable;

    /**
     * Returns default pagination size for repository
     * @return int pagination size (limit)
     */
    public function getPaginationSize(): int;

    /**
     * Pushes criterion class to be instantiated in $criteria collection to be applied on query results.
     * @param string $criterion
     * @see $criteria
     * @see BaseCriterionInterface
     */
    public function pushCriterion(string $criterion);

    /**
     * Pushes criterion instance in $criteria collection to be applied on query results.
     * @param BaseCriterionInterface $criterion
     * @see $criteria
     * @see BaseCriterionInterface
     */
    public function pushCriterionInstance(BaseCriterionInterface $criterion);

    /**
     * Try to find a resource with $id or fail if can't find any matching.
     * @param Model|ModelBase|EloquentBuilder|QueryBuilder|int $id
     * @param array $columns
     * @return EloquentBuilder|EloquentBuilder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function findOrFail($id, array $columns = ['*']);


    /**
     * Show list of specified resource
     * @param array $columns indicates which columns should be selected
     *
     * @return Collection|mixed
     */
    public function list(array $columns = ['*']);


    /**
     * Show specified resource by $id
     * @param Model|ModelBase|EloquentBuilder|QueryBuilder $id
     * @param array $columns indicates which columns should be selected
     * @return mixed
     */
    public function show($id, array $columns = ['*']);

    /**
     * Store a resource in database
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update specified resource by $id with $data
     * @param Model|ModelBase|EloquentBuilder|QueryBuilder $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Destroy specified resource by $id
     * @param Model|ModelBase|EloquentBuilder|QueryBuilder $id
     * @return mixed
     */
    public function destroy($id);


    /**
     * Returns a starter query to be called in other methods!
     * @param array $columns
     * @return EloquentBuilder
     */
    public function baseQuery(array $columns = ['*']): EloquentBuilder;

    /**
     * Stores current model event dispatcher and
     * Unsets that so queries won't fire eloquent events.
     * Can be reset by calling resetEvents on repository instance
     * @return $this
     * @see resetEvents()
     */
    public function withoutEvents(): self;

    /**
     * Resets event dispatcher for model class, if exist.
     * Can be undone by calling withoutEvents on repository instance
     * @return $this
     * @see withoutEvents()
     */
    public function resetEvents(): self;

}
