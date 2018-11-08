<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model;

use Helper\Unit;

class ValidatorFactory
{
    /**
     * The list of built-in validators (name=>class)
     * @var array
     */
    public static $builtInValidators = [
        'boolean' => '\Model\validators\BooleanValidator',
        'compare' => '\Model\validators\CompareValidator',
        'contact' => '\Model\validators\ContactValidator',
        'datetime' => '\Model\validators\DatetimeValidator',
        'date' => '\Model\validators\DateValidator',
        'default' => '\Model\validators\DefaultValueValidator',
        'ip' => '\Model\validators\IpValidator',
        'mobile' => '\Model\validators\MobileValidator',
        'multiIn' => '\Model\validators\MultiRangeValidator',
        'numerical' => '\Model\validators\NumberValidator',
        'password' => '\Model\validators\PasswordValidator',
        'phone' => '\Model\validators\PhoneValidator',
        'in' => '\Model\validators\RangeValidator',
        'email' => '\Model\validators\EmailValidator',
        'filter' => '\Model\validators\FilterValidator',
        'match' => '\Model\validators\RegularExpressionValidator',
        'required' => '\Model\validators\RequiredValidator',
        'safe' => '\Model\validators\SafeValidator',
        'string' => '\Model\validators\StringValidator',
        'type' => '\Model\validators\TypeValidator',
        'url' => '\Model\validators\UrlValidator',
        'username' => '\Model\validators\UsernameValidator',
        'fax' => '\Model\validators\FaxValidator',
        'file' => '\Model\validators\FileValidator',
        'captcha' => '\Model\validators\CaptchaValidator',
        'zip' => '\Model\validators\ZipValidator',
        'unique' => '\Model\validators\UniqueValidator',
    ];

    /**
     * 创建验证器
     * @param string $name
     * @param \Model\Model $object
     * @param array $attributes
     * @param array $params
     * @return \Model\Validator
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
            $params['class'] = '\Model\validators\InlineValidator';
            $params['method'] = $name;
        } else if (isset(self::$builtInValidators[$name])) {
            $params['class'] = self::$builtInValidators[$name];
        } else {
            $params['class'] = $name;
        }
        return Unit::createObject($params);
    }
}