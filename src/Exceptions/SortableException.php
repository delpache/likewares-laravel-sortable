<?php

namespace Likewares\Sortable\Exceptions;

use Exception;

class SortableException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        switch ($code) {
            case 0:
                $message = 'Argument de tri non valide.';
                break;
            case 1:
                $message = 'Relation \'' . $message . '\' n\'existe pas.';
                break;
            case 2:
                $message = 'Relation \'' . $message . '\' n\'est pas une instance de HasOne ou BelongsTo.'; //hasMany
                break;
        }

        parent::__construct($message, $code, $previous);
    }
}
