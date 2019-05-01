<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class GiveBadgeValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class GiveBadgeValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['badge_id' => 'required','user_id'=> 'required'],
        ValidatorInterface::RULE_UPDATE => ['badge_id' => 'required','user_id'=> 'required'],
    ];
}
