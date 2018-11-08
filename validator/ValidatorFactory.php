<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\PFBase;

class ValidatorFactory
{
    /**
     * The list of built-in validators (name=>class)
     * @var array
     */
    public static $builtInValidators = [
        'boolean' => '\pf\validator\BooleanValidator',
        'compare' => '\pf\validator\CompareValidator',
        'contact' => '\pf\validator\ContactValidator',
        'datetime' => '\pf\validator\DatetimeValidator',
        'date' => '\pf\validator\DateValidator',
        'default' => '\pf\validator\DefaultValueValidator',
        'ip' => '\pf\validator\IpValidator',
        'mobile' => '\pf\validator\MobileValidator',
        'multiIn' => '\pf\validator\MultiRangeValidator',
        'numerical' => '\pf\validator\NumberValidator',
        'password' => '\pf\validator\PasswordValidator',
        'phone' => '\pf\validator\PhoneValidator',
        'in' => '\pf\validator\RangeValidator',
        'email' => '\pf\validator\EmailValidator',
        'filter' => '\pf\validator\FilterValidator',
        'match' => '\pf\validator\RegularExpressionValidator',
        'required' => '\pf\validator\RequiredValidator',
        'safe' => '\pf\validator\SafeValidator',
        'string' => '\pf\validator\StringValidator',
        'type' => '\pf\validator\TypeValidator',
        'url' => '\pf\validator\UrlValidator',
        'username' => '\pf\validator\UsernameValidator',
        'fax' => '\pf\validator\FaxValidator',
        'file' => '\pf\validator\FileValidator',
        'captcha' => '\pf\validator\CaptchaValidator',
        'zip' => '\pf\validator\ZipValidator',
        'unique' => '\pf\validator\UniqueValidator',
    ];

    /**
     * 创建验证器
     * @param string $name
     * @param \pf\core\Model $object
     * @param array $attributes
     * @param array $params
     * @return mixed|\pf\core\Core
     * @throws \pf\exception\Exception
     */
    static public function create($name, $object, $attributes, $params = [])
    {
        // 将属性转换成数组
        if (is_string($attributes)) {
            $attributes = preg_split('/[\s,]+/', $attributes, -1, PREG_SPLIT_NO_EMPTY);
        }
        // 分析规则适用的场景（scenario）
        if (isset($params['on'])) {
            if (is_array($params['on'])) {
                $on = $params['on'];
            } else {
                $on = preg_split('/[\s,]+/', $params['on'], -1, PREG_SPLIT_NO_EMPTY);
            }
        } else {
            $on = [];
        }
        // 分析规则不适用的场景（scenario）
        if (isset($params['except'])) {
            if (is_array($params['except'])) {
                $except = $params['except'];
            } else {
                $except = preg_split('/[\s,]+/', $params['except'], -1, PREG_SPLIT_NO_EMPTY);
            }
        } else {
            $except = [];
        }
        $params['attributes'] = $attributes;
        $params['on'] = empty($on) ? [] : array_combine($on, $on); // 绑定支持的 scenario
        $params['except'] = empty($except) ? [] : array_combine($except, $except); // 绑定拒绝的 scenario

        if (method_exists($object, $name)) {
            $params['class'] = '\pf\validator\InlineValidator';
            $params['method'] = $name;
        } else if (isset(self::$builtInValidators[$name])) {
            $params['class'] = self::$builtInValidators[$name];
        } else {
            $params['class'] = $name;
        }
        return PFBase::createObject($params);
    }
}