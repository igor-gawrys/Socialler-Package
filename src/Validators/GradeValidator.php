<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class GradeValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class GradeValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
        
        ],
        ValidatorInterface::RULE_REGISTER => [
        	 'school_id' => 'required|integer',
             'verify_key' => 'school_key_correct',
             'name' => 'required|string',
             'description' => 'required|string',
             'user_full_name' => 'required|string',
             'user_email' => 'required|email|unique:users,email',
             'user_password' => 'required|min:6|string',
             'user_password_confirmation' => 'same:user_password'
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
