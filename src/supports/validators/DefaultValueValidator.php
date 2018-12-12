<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace ModelSupports\validators;

use Abstracts\Validator;

class DefaultValueValidator extends Validator
{
    /* @var mixed 设置默认的值 */
    public $value;
    /* @var bool 是否当属性的值为空时才为属性赋值 */
    public $setOnEmpty = true; //

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
     * @param string $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        if (!$this->setOnEmpty) {
            $object->{$attribute} = $this->value;
        } else {
            $value = $object->{$attribute};
            if (null === $value || '' === $value) {
                $object->{$attribute} = $this->value;
            }
        }
    }
}