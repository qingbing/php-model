<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-06-02
 * Version      :   1.0
 */

namespace Tools;


class Validate
{
    /**
     * 验证是否为空
     * @param mixed $var
     * @param bool|true $strict
     * @return bool
     */
    static public function isEmpty($var, $strict = true)
    {
        if (is_object($var) || is_array($var)) {
            return empty($var);
        }
        if ($strict) {
            return ('' === trim($var) || NULL === $var);
        } else {
            return empty($var);
        }
    }

    /**
     * 验证变量是否为32位md5值
     * @param string|array $var
     * @return bool
     */
    static public function isMd5($var)
    {
        return !!preg_match('#^[0-9a-zA-Z]{32}$#', $var);
    }

    /**
     * 验证变量是否为日期格式
     * @param string $var
     * @return bool
     */
    static public function isDate($var)
    {
        return !!preg_match('#^(\d{2})?\d{2}-[0|1]?\d-[0-3]?\d$#', $var);
    }

    /**
     * 验证变量是否为时间格式
     * @param string $var
     * @return bool
     */
    static public function isDatetime($var)
    {
        return !!preg_match('#^(\d{2})?\d{2}-[0|1]?\d-[0-3]?\d [0-2]?\d:[0-5]?\d:[0-5]?\d$#', $var);
    }

    /**
     * 验证变量是否为时间格式
     * @param string $var
     * @param bool $isJava
     * @return bool
     */
    static public function isTimestamp($var, $isJava = false)
    {
        if ($isJava) {
            return !!preg_match('#^1\d{12}$#', $var);
        } else {
            return !!preg_match('#^1\d{9}$#', $var);
        }
    }

    /**
     * 验证是否在给定范围内
     * @param mixed $var
     * @param array $range
     * @return bool
     */
    static public function inRange($var, $range = array())
    {
        return in_array($var, $range);
    }

    /**
     * 验证变量是否为手机格式
     * @param string $var
     * @return bool
     */
    static public function isBoolean($var)
    {
        return self::inRange($var, ['0', '1']);
    }

    /**
     * 验证变量是否为联系方式，支持电话手机和(可带区号)
     * @param string $var
     * @return bool
     */
    static public function isContact($var)
    {
        return !!preg_match('#(^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$)|(^0?1\d{10}$)#', $var);
    }

    /**
     * 验证变量是否为邮箱格式
     * @param string $var
     * @return bool
     */
    static public function isEmail($var)
    {
        return !!preg_match('/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/', $var);
    }

    /**
     * 验证变量是否为传真号码
     * @param string $var
     * @return bool
     */
    static public function isFix($var)
    {
        return !!preg_match('/^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$/', $var);
    }

    /**
     * 验证变量是否为IP地址格式
     * @param string $var
     * @return bool
     */
    static public function isIp($var)
    {
        return !!preg_match('/^(1\d{2}|2[0-4]\d|25[0-4]|[1-9]\d?)(\.(1\d{2}|2[0-4]\d|25[0-4]|[1-9]?\d)){3}$/i', $var);
    }

    /**
     * 验证变量是否为手机格式
     * @param string $var
     * @return bool
     */
    static public function isMobile($var)
    {
        return !!preg_match('/^0?1\d{10}$/', $var);
    }

    /**
     * 验证是否为数字类型
     * @param mixed $var
     * @return bool
     */
    static public function isNumeric($var)
    {
        return is_numeric($var);
    }

    /**
     * 验证是否为整数类型
     * @param mixed $var
     * @param bool $strict
     * @return bool
     */
    static public function isInteger($var, $strict = false)
    {
        if (is_int($var))
            return true;
        if ($strict)
            return false;
        if (!self::isNumeric($var))
            return false;
        return !!preg_match('#(^[+-]?[1-9]\d*$)|(^0$)#', $var);
    }

    /**
     * 验证数字，支持长度检查。[-1]太短;[0]正常;[1]太长;
     * @param mixed $var
     * @param int $min
     * @param int $max
     * @return int
     */
    static public function checkNumber($var, $min = null, $max = null)
    {
        if (null !== $min && $var < $min) {
            return -1;
        } else if (null !== $max && $var > $max) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 验证变量是否为电话号码格式
     * @param string $var
     * @return bool
     */
    static public function isPhone($var)
    {
        return !!preg_match('/^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$/', $var);
    }

    /**
     * 验证字符串，支持长度检查。[-1]太短;[0]正常;[1]太长;
     * @param string $var
     * @param int $minLength
     * @param int $maxLength
     * @return int
     */
    static public function checkString($var, $minLength = null, $maxLength = null)
    {
        $len = strlen($var);
        if (null !== $minLength && $len < $minLength) {
            return -1;
        } else if (null !== $maxLength && $len > $maxLength) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 验证变量是否为URL地址格式
     * @param string $var
     * @return bool
     */
    static public function isUrl($var)
    {
        return !!preg_match('/^https?:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i', $var);
    }

    /**
     * 验证变量是否为邮政编码格式
     * @param string $var
     * @return bool
     */
    static public function isZipcode($var)
    {
        return !!preg_match('/^\d{6}$/', $var);
    }
}