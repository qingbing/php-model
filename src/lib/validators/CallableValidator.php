<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-09
 * Version      :   1.0
 */

namespace Model\validators;

use Helper\Exception;
use Abstracts\Validator;

class CallableValidator extends Validator
{
    /**
     * 可被调用的过滤方法
     * @var callback
     */
    public $callback;

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        if (null === $this->callback || !is_callable($this->callback)) {
            throw new Exception('属性"callback"必须指定为一个可调用的回调', 101400401);
        }
        call_user_func_array($this->callback, [$object, $attribute]);
    }
}