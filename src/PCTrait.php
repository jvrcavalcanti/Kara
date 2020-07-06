<?php

namespace Kara;

trait PCTrait
{
    protected function getTopic()
    {
        if (isset($this->topic)) {
            return $this->topic;
        }

        $namespace = static::class;
        $arr = explode("\\", $namespace);
        $name = strtolower(explode("Consumer", $arr[sizeof($arr) - 1])[0]) . "s";
        return $name;
    }

    protected function getSerializeType()
    {
        if (isset($this->type)) {
            return $this->type;
        }

        return Serialize::TEXT;
    }
}
