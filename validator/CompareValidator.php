<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\Unit;

class CompareValidator extends Validator
{
    public $compareAttribute; // 需要对比的属性
    public $compareValue; // 指定对比的值
    public $strict = false; // 是否需要严格的对比
    /**
     * 对比的操作符
     * <pre>
     * "=" or "==" : 验证两个值是否相等
     * "!=" : 验证以查看两个值是否不相等
     * ">" : 验证要验证的值是否大于要比较的值
     * ">=" : 验证要验证的值是否大于或等于要比较的值
     * "<" : 验证要验证的值是否小于要比较的值
     * "<=" : 验证要验证的值是否小于或等于要比较的值
     * </pre>
     * @var string
     */
    public $operator = '=';

    /**
     * 对比验证的规则
     * @param \pf\core\Model $object
     * @param string $attribute
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (null !== $this->compareValue) {
            $compareTo = $compareValue = $this->compareValue;
        } else {
            $compareAttribute = null === $this->compareAttribute ? $attribute . '_repeat' : $this->compareAttribute;
            $compareValue = $object->$compareAttribute;
            $compareTo = $object->getAttributeLabel($compareAttribute);
        }

        switch ($this->operator) {
            case '=':
            case '==':
                if (($this->strict && $value !== $compareValue) || (!$this->strict && $value != $compareValue)) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must be repeated exactly with "{compareAttribute}".';
                }
                break;
            case '!=':
                if (($this->strict && $value === $compareValue) || (!$this->strict && $value == $compareValue)) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must not be equal to "{compareAttribute}".';
                }
                break;
            case '>':
                if ($value <= $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must be greater than "{compareAttribute}".';
                }
                break;
            case '>=':
                if ($value < $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must be greater than or equal to "{compareAttribute}".';
                }
                break;
            case '<':
                if ($value >= $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must be less than "{compareAttribute}".';
                }
                break;
            case '<=':
                if ($value > $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}" must be less than or equal to "{compareAttribute}".';
                }
                break;
            default:
                throw new Exception(Unit::replace('Invalid operator "{operator}".', [
                    '{operator}' => $this->operator,
                ]));
        }
        if (!empty($message)) {
            $this->addError($object, $attribute, $message, [
                '{compareAttribute}' => $compareTo,
            ]);
        }
    }
}