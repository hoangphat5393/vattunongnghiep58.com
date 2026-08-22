<?php

namespace App\Traits;

use Str;

trait Filterable
{
    public function scopeFilter($query, $request)
    {

        $param = $request->all();

        foreach ($param as $field => $value) {

            if ($value != '' && $value != 'all') {

                $method = 'filter'.Str::studly($field);

                if (method_exists($this, $method)) {

                    $this->{$method}($query, $value);

                } else {

                    if (! empty($this->filterable) && is_array($this->filterable)) {

                        if (in_array($field, $this->filterable)) {

                            $query->where($this->table.'.'.$field, $value);

                        } elseif (array_key_exists($field, $this->filterable)) {

                            $query->where($this->table.'.'.$this->filterable[$field], $value);

                        }

                    }

                }

            }

        }

        return $query;

    }
}
