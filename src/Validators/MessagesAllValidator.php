<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class MessagesAllValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class MessagesAllValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['content' => 'required','grade_id' => 'required'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
