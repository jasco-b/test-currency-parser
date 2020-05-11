<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 23:03
 */

namespace App\Domain\Currency\Exceptions;


use Throwable;

class ValidationError extends BaseCurrencyException
{
    /**
     * @var array
     */
    private $errors;

    public function __construct($errors = [], string $message = "Validation error", int $code = 422, Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function getFirstError()
    {
        return current(current(current($this->errors)));
    }
}
