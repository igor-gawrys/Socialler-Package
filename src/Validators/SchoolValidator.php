<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class SchoolValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class SchoolValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
        ValidatorInterface::RULE_BUY => ['token' => 'required'],
    ];
}
