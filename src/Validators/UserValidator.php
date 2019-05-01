<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class UserValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class UserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['full_name' => "required",'email' => "required|email","password" => "required",'school_id' => 'required','grade_id' => 'required','school_id' => 'required'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
