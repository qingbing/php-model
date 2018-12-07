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

class CompareValidator extends Validator
{
    /* @var string 需要对比的属性 */
    public $compareAttribute;
    /* @var mixed 指定对比的值 */
    public $compareValue;
    /* @var bool 是否需要严格的对比 */
    public $strict = false;
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
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (null !== $this->compareValue) {
            $compareTo = $compareValue = $this->compareValue;
        } else {
            $compareAttribute = null === $this->compareAttribute ? $attribute . '_repeat' : $this->compareAttribute;
            $compareValue = $object->{$compareAttribute};
            $compareTo = $object->getAttributeLabel($compareAttribute);
        }

        switch ($this->operator) {
            case '=':
            case '==':
                if (($this->strict && $value !== $compareValue) || (!$this->strict && $value != $compareValue)) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须和"{compareAttribute}"相等';
                }
                break;
            case '!=':
                if (($this->strict && $value === $compareValue) || (!$this->strict && $value == $compareValue)) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须不等于"{compareAttribute}"';
                }
                break;
            case '>':
                if ($value <= $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须大于"{compareAttribute}"';
                }
                break;
            case '>=':
                if ($value < $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须大于等于"{compareAttribute}"';
                }
                break;
            case '<':
                if ($value >= $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须小于"{compareAttribute}"';
                }
                break;
            case '<=':
                if ($value > $compareValue) {
                    $message = (null !== $this->message) ? $this->message : '"{attribute}"必须小于等于"{compareAttribute}"';
                }
                break;
            default:
                throw new Exception(str_cover('无效的对比操作符"{operator}"', [
                    '{operator}' => $this->operator,
                ]), 101400201);
        }
        if (!empty($message)) {
            $this->addError($object, $attribute, $message, [
                '{compareAttribute}' => $compareTo,
            ]);
        }
    }
}