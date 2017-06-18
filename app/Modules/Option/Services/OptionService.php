<?php

namespace Awok\Modules\Option\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Option\Models\Option;

class OptionService extends BaseService
{
    public function __construct()
    {
        $this->setBaseModel(Option::class);
    }

    /**
     * Get Option
     *
     * @param null $type
     * @param      $key
     *
     * @return mixed
     */
    public function get($type = null, $key, $default = null)
    {
        $option = new Option();
        if ($type) {
            $option->where('type', '=', $type);
        }
        $result = $option->where('key', '=', $key)->first();
        if (! $result) {
            return $default;
        }

        return $result->value;
    }

    public function set($type, $key, $value)
    {
        return Option::updateOrCreate(
            ['type' => $type, 'key' => $key],
            ['value' => $value]
        );
    }
}