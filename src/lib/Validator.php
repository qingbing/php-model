<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model;

use Helper\Base;

abstract class Validator extends Base
{
    /* @var array 需要验证属性 */
    public $attributes; // 需要验证属性
    /* @var array 规则支持的 scenario */
    public $on;
    /* @var array 规则不支持的 scenario */
    public $except;
    /* @var boolean 属性值是否允许为空 */
    public $allowEmpty = true;
    /* @var string 空验证的error提示，{attribute}"可以替代成属性的"label" */
    public $emptyMessage = '"{attribute}"不允许为空';
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message;
    /* @var boolean 当发生验证规则不通过时，是否终止该属性剩下的规则验证 */
    public $skipOnError = false;

    /**
     * 判定 scenario 是否被 validator 支持;支持的方式有两种：
     *  1、"on"设置为空
     *  2、"on"里面被设置
     * @param string $scenario
     * @return bool
     */
    public function applyTo($scenario)
    {
        if (isset($this->except[$scenario])) {
            return false;
        }
        return empty($this->on) || isset($this->on[$scenario]);
    }

    /**
     * 判断传递值是否为空
     * @param mixed $value
     * @param bool|false $trim
     * @return bool
     */
    protected function isEmpty($value, $trim = false)
    {
        return null === $value || [] === $value || '' === $value || $trim && is_scalar($value) && '' === trim($value);
    }

    /**
     * 添加一个特定属性的验证错误消息
     * @param \Model\Model $object
     * @param string $attribute
     * @param string $message
     * @param array $params
     * @throws \Exception
     */
    protected function addError($object, $attribute, $message, $params = [])
    {
        $params['{attribute}'] = $object->getAttributeLabel($attribute);
        $object->addError($attribute, str_cover($message, $params));
    }

    /**
     * 判断当不允许为空时添加错误消息
     * @param \Model\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateEmpty($object, $attribute)
    {
        if (!$this->allowEmpty) {
            $this->addError($object, $attribute, $this->emptyMessage);
        }
    }

    /**
     * Model 的验证属性
     * @param \Model\Model $object
     * @param array $attributes
     * @throws \Exception
     */
    public function validate($object, $attributes = null)
    {
        if (is_array($attributes)) {
            $attributes = array_intersect($this->attributes, $attributes); // 求交集
        } else {
            $attributes = $this->attributes;
        }
        foreach ($attributes as $attribute) {
            if (!$this->skipOnError || !$object->hasErrors($attribute)) {
                $this->validateAttribute($object, $attribute);
            }
        }
    }

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Model\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    abstract protected function validateAttribute($object, $attribute);
}