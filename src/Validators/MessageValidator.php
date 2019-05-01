<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class MessageValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class MessageValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['content' => 'required','user_id' => 'integer'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
