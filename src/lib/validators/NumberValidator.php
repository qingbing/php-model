<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;

use Abstracts\Validator;

class NumberValidator extends Validator
{
    /* @var bool 是否限定为整数 */
    public $integerOnly = false;
    /* @var integer 允许的最大值 */
    public $max;
    /* @var integer 允许的最小值 */
    public $min;
    /* @var string 超过最大值时提示的错误信息 */
    public $tooBigMessage = '"{attribute}"已经超过最大值"{max}"';
    /* @var string 不及最小值时提示的错误信息 */
    public $tooSmallMessage = '"{attribute}"已经小于了最小值"{min}"';
    /* @var string 整数的正则表达式 */
    public $integerPattern = '/^\s*[+-]?\d+\s*$/';
    /* @var string 浮点数的正则表达式 */
    public $numberPattern = '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

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
        if (!is_numeric($value)) {
            $message = $this->message !== null ? $this->message : '"{attribute}"必须是数字';
            $this->addError($object, $attribute, $message);
            return;
        }
        if ($this->integerOnly) {
            if (!preg_match($this->integerPattern, "$value")) {
                $message = $this->message !== null ? $this->message : '"{attribute}"必须是整数';
                $this->addError($object, $attribute, $message);
                return;
            }
        } else if (!preg_match($this->numberPattern, "$value")) {
            $message = null !== $this->message ? $this->message : '"{attribute}"必须是数字';
            $this->addError($object, $attribute, $message);
            return;
        }
        if (null !== $this->min && $value < $this->min) {
            $this->addError($object, $attribute, $this->tooSmallMessage, ['{min}' => $this->min]);
            return;
        }
        if (null !== $this->max && $value > $this->max) {
            $this->addError($object, $attribute, $this->tooBigMessage, ['{max}' => $this->max]);
        }
    }
}