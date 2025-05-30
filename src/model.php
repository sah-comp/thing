<?php

declare(strict_types=1);

namespace Thing;

/**
 * Generic model.
 */
final class Model
{
    /**
     * Perform an action.
     *
     * @param string $action The action to perform.
     * @return string
     */
    public function do(string $action = ''): string
    {
        // Optionally, add some logic here if needed.
        return $action;
    }
}
