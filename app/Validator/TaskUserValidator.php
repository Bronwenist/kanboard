<?php

namespace Kanboard\Validator;

use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Task User Validator
 *
 * @package  Kanboard\Validator
 * @author   Frederic Guillot
 */
class TaskUserValidator extends BaseValidator
{
    /**
     * Common validation rules
     *
     * @access private
     * @return array
     */
    private function commonValidationRules()
    {
        return array(
            new Validators\Required('task_id', t('Field required')),
            new Validators\NotEquals('user_id', 'owner_id', t('This user is already assigned to the task')),
            new Validators\Required('user_id', t('Field required'))
        );
    }

    /**
     * Validate creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, $this->commonValidationRules());

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }


}
