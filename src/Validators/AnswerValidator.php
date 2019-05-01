<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class AnswerValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class AnswerValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['content','comment_id'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
