<?php declare (strict_types = 1);
namespace Thing;

/**
 * Generic model
 */
final class Model
{
    /**
     * Do something.
     *
     * @param string $something A string with the thing to do.
     * @return string
     */
    public function do(string $something = ''): string
    {
        return $something;
    }
}
