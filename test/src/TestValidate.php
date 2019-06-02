<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-06-02
 * Version      :   1.0
 */

namespace Test;

use TestCore\Tester;
use Tools\Validate;

class TestValidate extends Tester
{
    /**
     * 执行函数
     * @return mixed|void
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function run()
    {
        // 验证是否为空
        var_dump('isEmpty');
        var_dump(Validate::isEmpty(''));
        // 验证是否 md5
        var_dump('isMd5');
        var_dump(Validate::isMd5('12345678901234567890123456789012'));
        // 验证是否 date
        var_dump('isDate');
        var_dump(Validate::isDate('2000-10-01'));
        // 验证是否 datetime
        var_dump('isDatetime');
        var_dump(Validate::isDatetime('2000-10-01 00:00:00'));
        // 验证是否 时间戳
        var_dump('isTimestamp');
        var_dump(Validate::isTimestamp('1234567890'));
        var_dump(Validate::isTimestamp('1234567890000', true));
        // 验证是否 在给定范围内
        var_dump('inRange');
        var_dump(Validate::inRange(1, [1, 2, 3]));
        // 验证是否 为boolean值（0，1）
        var_dump('isBoolean');
        var_dump(Validate::isBoolean('0'));
        var_dump('isContact');
        // 验证是否 联系方式，支持电话手机和(可带区号)
        var_dump(Validate::isContact('13605054899'));
        var_dump(Validate::isContact('028-87461234'));
        var_dump(Validate::isContact('028-87461234-010'));
        // 验证是否 邮箱
        var_dump('isEmail');
        var_dump(Validate::isEmail('13605054899@qq.com'));
        var_dump(Validate::isEmail('top-world@qq.com'));
        // 验证是否 传真号码
        var_dump('isFix');
        var_dump(Validate::isFix('028-87461234'));
        var_dump(Validate::isFix('028-87461234-010'));
        // 验证是否 IP地址
        var_dump('isIp');
        var_dump(Validate::isIp('127.0.0.1'));
        // 验证是否 手机格式
        var_dump('isMobile');
        var_dump(Validate::isMobile('13605054899'));
        // 验证是否 手机格式
        var_dump('isMobile');
        var_dump(Validate::isMobile('13605054899'));
        // 验证是否 数字
        var_dump('isNumeric');
        var_dump(Validate::isNumeric(11));
        var_dump(Validate::isNumeric('11'));
        var_dump(Validate::isNumeric(11.11));
        var_dump(Validate::isNumeric('11.11'));
        // 验证是否 整数
        var_dump('isInteger');
        var_dump(Validate::isInteger(11));
        var_dump(Validate::isInteger('11'));
        // 验证是否 数字范围 [-1]太短;[0]正常;[1]太长;
        var_dump('checkNumber');
        var_dump(Validate::checkNumber('11', 11, 20));
        var_dump(Validate::checkNumber('11', 0, 11));
        // 验证是否 电话号码
        var_dump('isPhone');
        var_dump(Validate::isPhone('028-87461234'));
        var_dump(Validate::isPhone('028-87461234-010'));
        // 验证是否 字符串长度范围 [-1]太短;[0]正常;[1]太长;
        var_dump('checkString');
        var_dump(Validate::checkString('11', 2, 20));
        var_dump(Validate::checkString('11', 0, 2));
        // 验证是否 URL
        var_dump('isPhone');
        var_dump(Validate::isUrl('http://www.phpcorner.net'));
        var_dump(Validate::isUrl('https://www.phpcorner.net'));
        // 验证是否 邮政编码
        var_dump('isZipcode');
        var_dump(Validate::isZipcode('100000'));
        // 多个值满足同一个验证
        var_dump('multi');
        var_dump(Validate::multi([10, 20], 'checkNumber', [10, 20]));
        var_dump(Validate::multi(['12', 'xxxx'], 'checkString', [2, 4]));
        var_dump(Validate::multi(['12', '2345'], 'isInteger'));
    }
}