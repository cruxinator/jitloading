<?php

namespace Cruxinator\JitLoading;

use Cruxinator\JitLoading\Exceptions\JitLoadException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use LogicException;

/**
 * Trait JitLoadRelationships
 * @mixin Model
 */
trait JitLoadRelationships
{
    /**
     * The maximum collection size that we will autoload for
     * @var int
     */
    protected $autoloadThreshold = 6000;

    /**
     * @var ?Collection
     */
    protected $parentCollection = null;

    /**
     * If null, let eager-load enforcement be determined by environment
     * If true, always enforce eager-loading
     * If false, always disable eager-loading
     *
     * @var bool|null
     */
    protected static $enforceEagerLoad = null;

    /**
     * Check to see if we should autoload
     * @return bool
     */
    private function shouldAutoLoad(): bool
    {
        return $this->parentCollection
            && count($this->parentCollection) > 1
            && count($this->parentCollection) <= $this->autoloadThreshold;
    }

    /**
     * @param string $file
     * @return bool|string
     */
    private function getBlade(string $file)
    {
        if (strpos($file, 'framework/views/') === false) {
            return false;
        }

        $blade = file($file)[0];

        return trim(str_replace(['<?php /* ', ' */ ?>'], '', $blade));
    }

    /**
     * Log the fact we have used the JIT loader, if required
     *
     * @param string $relationship
     * @return bool
     * @throws JitLoadException
     */
    private function logAutoload(string $relationship): bool
    {
        if (! isset($this->logChannel)) {
            //return false;
        }

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)[4];

        $file = $stack['file'];

        $context = [
            'relationship' => static::class.'::'.$relationship,
            'file' => $file,
            'line' => $stack['line'],
            'view' => $this->getBlade($file),
            'stack' => (new \Exception())->getTraceAsString(),
        ];

        if ($this->strictEagerLoad()) {
            $message = 'Relationship '.static::class.'::'.$relationship.' was JIT-loaded.';

            throw new JitLoadException($message);
        }

        Log::info(
            '[LARAVEL-JIT-LOADER] Relationship '.static::class.'::'.$relationship.' was JIT-loaded.',
            $context
        );

        return true;
    }

    /**
     * Load the relationship for the given method, and then get a relationship value from a method.
     * @param string $method
     * @return mixed
     *
     * @throws LogicException
     */
    public function getRelationshipFromMethod($method)
    {
        $relation = $this->$method();

        if (! $relation instanceof Relation) {
            throw new LogicException(
                sprintf('%s::%s must return a relationship instance.', static::class, $method)
            );
        }

        if ($this->shouldAutoLoad()) {
            if (! $this->relationLoaded($method)) {
                $this->logAutoload($method);
                $this->parentCollection->load($method);

                return $this->relations[$method];
            }
        }

        return tap($relation->getResults(), function ($results) use ($method) {
            $this->setRelation($method, $results);
        });
    }

    /**
     * Create a new Eloquent collection, and assign all models as a member of this collection
     *
     * @param array $models
     * @return Collection
     */
    public function newCollection($models = []): Collection
    {
        $collection = new Collection($models);
        unset($models);

        foreach ($collection as $model) {
            $model->parentCollection = $collection;
        }

        return $collection;
    }

    /**
     * Disable autoloading of relationships on this model
     * @return self
     */
    public function disableAutoload(): self
    {
        $this->autoloadThreshold = 0;

        return $this;
    }

    protected function strictEagerLoad(): bool
    {
        if (null !== static::$enforceEagerLoad) {
            return static::$enforceEagerLoad;
        }
        if ('testing' == App::environment() || 'local' == App::environment()) {
            return true;
        }

        return false;
    }
}
