<?php

namespace Igorgawrys\Socialler\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CommentValidator.
 *
 * @package namespace Igorgawrys\Socialler\Validators;
 */
class CommentValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['conent','post_id'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
