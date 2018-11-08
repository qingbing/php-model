<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class DefaultValueValidator extends Validator
{
    public $value; // 默认设置的值
    public $setOnEmpty = true; // 是否当属性的值为空时才为属性赋值

    /**
     * 属性值设置的规则
     * @param \pf\core\Model $object
     * @param $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        if (!$this->setOnEmpty) {
            $object->$attribute = $this->value;
        } else {
            $value = $object->$attribute;
            if (null === $value || '' === $value) {
                $object->$attribute = $this->value;
            }
        }
    }
}