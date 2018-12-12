<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace ModelSupports\validators;


use Abstracts\Validator;

class RequiredValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不能为空';

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}