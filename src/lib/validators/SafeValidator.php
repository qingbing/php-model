<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;

use Abstracts\Validator;

class SafeValidator extends Validator
{
    /**
     * 该规则被认为是无需检测属性
     * @param \Abstracts\Model $object
     * @param string $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
    }
}