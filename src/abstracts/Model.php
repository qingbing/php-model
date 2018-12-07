<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-07
 * Version      :   1.0
 */
namespace Abstracts;

use Helper\Base;
use Helper\Exception;
use Model\ValidatorFactory;
use Model\validators\SafeValidator;

abstract class Model extends Base
{
    private $_scenario = ''; // 场景（scenario）
    private $_attributeLabels = [];

    private $_validators; // validators
    private $_errors = []; // 错误信息 attribute name => array of errors

    /**
     * 返回 model 在当前调用的 scenario
     * @return string
     */
    public function getScenario()
    {
        return $this->_scenario;
    }

    /**
     * 设置 model 在当前调用的 scenario
     * @param string $value
     */
    public function setScenario($value)
    {
        $this->_scenario = $value;
    }

    /**
     * 构造函数后被调用
     */
    public function init()
    {
    }

    /**
     * 定义并返回模型属性的验证规则，每一个规则都必须遵守一下规则：
     * ['attribute list', 'validator name', 'on'=>'scenario name', ...validation parameters...]
     *
     * 几个常见的例子
     * <pre>
     * [
     *     ['username', 'required'],
     *     ['username', 'length', 'min'=>3, 'max'=>12],
     *     ['password', 'compare', 'compareAttribute'=>'password2', 'on'=>'register'],
     *     ['password', 'authenticate', 'on'=>'login'],
     * ];
     * </pre>
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 返回模型的属性名称列表
     * @return array
     */
    abstract public function attributeNames();

    /**
     * 获取所有的属性值并返回
     * @param array $names
     * @return array attribute values (name=>value).
     */
    public function getAttributes($names = null)
    {
        $values = [];
        foreach ($this->attributeNames() as $name) {
            $values[$name] = $this->{$name};
        }

        if (is_array($names)) {
            $values2 = [];
            foreach ($names as $name) {
                $values2[$name] = isset($values[$name]) ? $values[$name] : null;
            }
            return $values2;
        }
        return $values;
    }

    /**
     * 获取属性标签，该属性在必要时需要被实例类重写
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * 获取并返回属性的标签
     * @param string $attribute
     * @return string
     * @throws Exception
     */
    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attribute])) {
            return $this->_attributeLabels[$attribute] = $labels[$attribute];
        } else {
            $ans = $this->attributeNames();
            if (!in_array($attribute, $ans)) {
                throw new Exception(str_cover('在Model"{model}"中找不到属性"{attribute}"', [
                    '{model}' => get_class($this),
                    '{attribute}' => $attribute,
                ]), 101400101);
            }
            return $this->_attributeLabels[$attribute] = $this->generateAttributeLabel($attribute);
        }
    }

    /**
     * 获取 model 的所有属性标签
     * @return array
     * @throws Exception
     */
    public function getAttributeLabels()
    {
        $labels = [];
        foreach ($this->getAttributes() as $attribute => $value) {
            $labels[$attribute] = $this->getAttributeLabel($attribute);
        }
        return $labels;
    }

    /**
     * 将属性设置为空
     * @param array $names
     */
    public function unsetAttributes($names = null)
    {
        if (null === $names) {
            $names = $this->attributeNames();
        }
        foreach ($names as $name) {
            $this->{$name} = null;
        }
    }

    /**
     * 判断是否有错误(主要为验证错误)
     * @param string $attribute
     * @return bool
     */
    public function hasErrors($attribute = null)
    {
        if (null === $attribute) {
            return [] !== $this->_errors;
        } else {
            return isset($this->_errors[$attribute]);
        }
    }

    /**
     * 获取属性是否有产生错误（主要为验证错误）
     * @param string $attribute
     * @return null|array
     */
    public function getError($attribute)
    {
        return isset($this->_errors[$attribute]) ? $this->_errors[$attribute] : null;
    }

    /**
     * 获取模型是否有产生错误（主要为验证错误）
     * @param string $attribute
     * @return array
     */
    public function getErrors($attribute = null)
    {
        if (null === $attribute) {
            return $this->_errors;
        } else {
            return isset($this->_errors[$attribute]) ? $this->_errors[$attribute] : [];
        }
    }

    /**
     * 为属性添加一个错误消息
     * @param string $attribute
     * @param string $error
     */
    public function addError($attribute, $error)
    {
        $this->_errors[$attribute] = $error;
    }

    /**
     * 添加错误消息列表
     * @param array $errors
     */
    public function addErrors(array $errors = [])
    {
        foreach ($errors as $attribute => $error) {
            if (is_array($error)) {
                foreach ($error as $e) {
                    $this->addError($attribute, $e);
                }
            } else {
                $this->addError($attribute, $error);
            }
        }
    }

    /**
     * 清除相应属性的错误消息
     * @param null|string $attribute
     */
    public function clearErrors($attribute = null)
    {
        if (null === $attribute) {
            $this->_errors = [];
        } else {
            unset($this->_errors[$attribute]);
        }
    }

    /**
     * 创建当前模型的验证器
     * @return array
     * @throws Exception
     * @throws \ReflectionException
     */
    public function createValidators()
    {
        $validators = [];
        foreach ($this->rules() as $rule) {
            if (isset($rule[0], $rule[1])) {
                $validators[] = ValidatorFactory::create($rule[1], $this, $rule[0], array_slice($rule, 2));
            } else {
                throw new Exception(str_cover('"{class}"指定了无效验证规则，规则必须指定验证器名和需要验证属性', [
                    '{class}' => get_class($this),
                ]), 101400102);
            }
        }
        return $validators;
    }

    /**
     * 获取验证器
     * @param array|null $attribute
     * @return \Model\Validator[]
     * @throws Exception
     * @throws \ReflectionException
     */
    public function getValidators($attribute = null)
    {
        if (null === $this->_validators) {
            $this->_validators = $this->createValidators();
        }

        $validators = [];
        $scenario = $this->getScenario();
        foreach ($this->_validators as $validator) {
            if ($validator->applyTo($scenario)) {
                if (null === $attribute || in_array($attribute, $validator->attributes, true)) {
                    $validators[] = $validator;
                }
            }
        }
        return $validators;
    }

    /**
     * 返回所有被认为需要安全验证的属性
     * @return array
     * @throws Exception
     * @throws \ReflectionException
     */
    public function getUnSafeAttributeNames()
    {
        $unsafe = [];
        foreach ($this->getValidators() as $validator) {
            if (!$validator instanceof SafeValidator) {
                foreach ($validator->attributes as $name) {
                    $unsafe[] = $name;
                }
            }
        }
        return array_unique($unsafe);
    }

    /**
     * 批量设置 model 的属性
     * @param array $values
     * @param bool|true $unsafeOnly
     * @return $this
     * @throws Exception
     * @throws \ReflectionException
     */
    public function setAttributes($values, $unsafeOnly = true)
    {
        if (!is_array($values)) {
            return $this;
        }
        $attributes = array_flip($unsafeOnly ? $this->getUnSafeAttributeNames() : $this->attributeNames());

        // 设置值
        foreach ($values as $name => $value) {
            if (isset($attributes[$name])) {
                $this->{$name} = $value;
            } else if ($unsafeOnly && PHP_DEBUG) {
                throw new Exception(str_cover('设置"{class}.{attribute}"失败', [
                    '{attribute}' => $name,
                    '{class}' => get_class($this)
                ]), 101400103);
            }
        }
        return $this;
    }

    /**
     * 在验证前执行，如果返回为 "false"，则不执行一下验证
     * @return bool
     */
    protected function beforeValidate()
    {
        return true;
    }

    /**
     * 验证通过后执行
     */
    protected function afterValidate()
    {
    }

    /**
     * 对属性进行验证
     * @param array|null $attributes
     * @param bool|true $clearErrors
     * @return bool
     * @throws \Exception
     */
    public function validate($attributes = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }
        if (!$this->beforeValidate()) {
            return false;
        }
        foreach ($this->getValidators() as $validator) {
            $validator->validate($this, $attributes);
        }
        $this->afterValidate();
        // 有错误发生，验证失败，不执行后续操作
        if ($this->hasErrors()) {
            return false;
        }
        return true;
    }

    /**
     * 构造属性标签
     * @param string $name
     * @return string
     */
    protected function generateAttributeLabel($name)
    {
        return ucwords(trim(strtolower(str_replace(['-', '_', '.'], ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }

    /**
     * __isset：魔术方法，当用isset函数作用于类实例属性时被唤醒，检查属性是否存在
     * @param string $property
     * @return bool
     */
    public function __isset($property)
    {
        if (method_exists($this, $getter = 'get' . $property)) {
            return $this->$getter() !== null;
        } else {
            $names = $this->attributeNames();
            return in_array($property, $names);
        }
    }
}