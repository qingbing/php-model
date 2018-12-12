<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports\validators;

use Abstracts\Validator;

class InlineValidator extends Validator
{
    /* @var string 在模型中验证的方法名 */
    public $method;
    /* @var array 需要传递给验证方法的其他参数 */
    public $params;

    /**
     * 通过内部方法验证
     * 如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
     * @param string $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        $method = $this->method;
        $object->{$method}($attribute, $this->params);
    }
}