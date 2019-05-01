<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class PointValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class PointValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['quantity' => 'required','user_id' => 'required'],
        ValidatorInterface::RULE_UPDATE => ['quantity' => 'required'],
    ];
}
