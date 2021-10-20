<?php 
namespace App\Actions;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class SlugAction
 */
class SlugAction
{
    /**
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * 
     */

    /**
     * Slug the current model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $force
     *
     * @return bool
     */
    public function slug(Model $model, bool $force = false): bool
    {
        $this->defineModel($model);

        $attributes = [];

        foreach ($this->model->sluggable() as $attribute => $config) {
            $slug = $this->buildSlug($attribute, $config, $force);

            if ($slug !== null) {
                $this->model->setAttribute($attribute, $slug);
                $attributes[] = $attribute;
            }
        }

        return $this->model->isDirty($attributes);
    }

    /**
     * Build the slug for the given attribute of the current model.
     *
     * @param string $attribute
     * @param array $config
     * @param bool $force
     *
     * @return null|string
     */
    public function buildSlug(string $attribute, array $config, bool $force = null): null|string
    {
        $slug = $this->model->getAttribute($attribute);

        if ($force || $this->needsSlugging($attribute, $config)) {
            $source = $this->getSlugSource($config['source']);

            if ($source || is_numeric($source)) {
                $slug = $this->generateSlug($source, $config, $attribute);
            }
        }

        return $slug;
    }

    /**
     * Determines whether the model needs slugging.
     *
     * @param string $attribute
     * @param array $config
     *
     * @return bool
     */
    protected function needsSlugging(string $attribute, array $config): bool
    {
        if (
            $config['onUpdate'] === true ||
            empty($this->model->getAttributeValue($attribute))
        ) {
            return true;
        }

        if ($this->model->isDirty($attribute)) {
            return false;
        }

        return (!$this->model->exists);
    }


    /**
     * Get the source string for the slug.
     *
     * @param mixed $from
     *
     * @return string
     */
    protected function getSlugSource($from): string
    {
        if (is_null($from)) {
            return $this->model->__toString();
        }

        $sourceStrings = array_map(function($key) {
            $value = data_get($this->model, $key);
            if (is_bool($value)) {
                $value = (int) $value;
            }

            return $value;
        }, (array) $from);

        return implode(' ', $sourceStrings);
    }

    /**
     * Generate a slug from the given source string.
     *
     * @param string $source
     * @param array $config
     * @param string $attribute
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function generateSlug(string $source, array $config, string $attribute): string
    {
        $slug = Str::slug($source);
        if ($this->model::where($attribute, $slug)->exists()) {
            $max = $this->model::where($config['source'], $source)->latest('id')->limit(1)->value('sku');
            
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($matches) {
                    return $matches[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }

        return $slug;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return $this
     */
    public function defineModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }
}