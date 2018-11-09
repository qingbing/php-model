<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;

class FilterValidator extends Validator
{
    /**
     * 可被调用的过滤方法
     * @var callback
     */
    public $filter;

    /**
     * 回掉过滤
     * @param \pf\core\Model $object
     * @param string $attribute
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        if (null === $this->filter || !is_callable($this->filter)) {
            throw new Exception('The "filter" property must be specified with a valid callback.');
        }
        $object->{$attribute} = call_user_func_array($this->filter, [$object->{$attribute}]);
    }
}