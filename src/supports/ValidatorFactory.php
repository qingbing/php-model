<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports;

use Helper\Unit;

class ValidatorFactory
{
    /**
     * The list of built-in validators (name=>class)
     * @var array
     */
    public static $builtInValidators = [
        'boolean' => '\ModelSupports\validators\BooleanValidator',
        'compare' => '\ModelSupports\validators\CompareValidator',
        'contact' => '\ModelSupports\validators\ContactValidator',
        'callable' => '\ModelSupports\validators\CallableValidator',
        'datetime' => '\ModelSupports\validators\DatetimeValidator',
        'date' => '\ModelSupports\validators\DateValidator',
        'default' => '\ModelSupports\validators\DefaultValueValidator',
        'ip' => '\ModelSupports\validators\IpValidator',
        'mobile' => '\ModelSupports\validators\MobileValidator',
        'multiIn' => '\ModelSupports\validators\MultiRangeValidator',
        'numerical' => '\ModelSupports\validators\NumberValidator',
        'password' => '\ModelSupports\validators\PasswordValidator',
        'phone' => '\ModelSupports\validators\PhoneValidator',
        'in' => '\ModelSupports\validators\RangeValidator',
        'email' => '\ModelSupports\validators\EmailValidator',
        'match' => '\ModelSupports\validators\RegularExpressionValidator',
        'required' => '\ModelSupports\validators\RequiredValidator',
        'safe' => '\ModelSupports\validators\SafeValidator',
        'string' => '\ModelSupports\validators\StringValidator',
        'type' => '\ModelSupports\validators\TypeValidator',
        'url' => '\ModelSupports\validators\UrlValidator',
        'username' => '\ModelSupports\validators\UsernameValidator',
        'fax' => '\ModelSupports\validators\FaxValidator',
        'zip' => '\ModelSupports\validators\ZipValidator',
    ];

    /**
     * 创建验证器
     * @param string $name
     * @param \Abstracts\Model $object
     * @param array $attributes
     * @param array $params
     * @return \Abstracts\Validator
     * @throws \Helper\Exception
     * @throws \ReflectionException
     */
    static public function create($name, $object, $attributes, $params = [])
    {
        // 将属性转换成数组
        if (is_string($attributes)) {
            $attributes = str_explode($attributes, ',');
        }
        // 分析规则适用的场景（scenario）
        if (isset($params['on'])) {
            if (is_array($params['on'])) {
                $on = $params['on'];
            } else {
                $on = str_explode($params['on'], ',');
            }
        } else {
            $on = [];
        }
        // 分析规则不适用的场景（scenario）
        if (isset($params['except'])) {
            if (is_array($params['except'])) {
                $except = $params['except'];
            } else {
                $except = str_explode($params['except'], ',');
            }
        } else {
            $except = [];
        }
        $params['attributes'] = $attributes;
        $params['on'] = empty($on) ? [] : array_combine($on, $on); // 绑定支持的 scenario
        $params['except'] = empty($except) ? [] : array_combine($except, $except); // 绑定拒绝的 scenario

        if (method_exists($object, $name)) {
            $params['class'] = '\ModelSupports\validators\InlineValidator';
            $params['method'] = $name;
        } else if (isset(self::$builtInValidators[$name])) {
            $params['class'] = self::$builtInValidators[$name];
        } else {
            $params['class'] = $name;
        }
        return Unit::createObject($params);
    }
}