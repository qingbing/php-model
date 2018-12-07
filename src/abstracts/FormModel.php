<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-07
 * Version      :   1.0
 */

namespace Abstracts;

use Helper\Unit;

defined('PHP_DEBUG') or define('PHP_DEBUG', false);

abstract class FormModel extends Model
{
    private static $_names = [];

    /**
     * @param string $scenario 调用场景（"login", "insert", "update" ...）
     */
    public function __construct($scenario = '')
    {
        $this->setScenario($scenario);
        $this->init();
    }

    /**
     * 返回属性名称列表
     * @return array
     * @throws \ReflectionException
     */
    public function attributeNames()
    {
        $className = get_class($this);
        if (!isset(self::$_names[$className])) {
            $reflection = Unit::getReflectionClass($this);
            $names = [];
            foreach ($reflection->getProperties() as $property) {
                $name = $property->getName();
                if ($property->isPublic() && !$property->isStatic()) {
                    $names[] = $name;
                }
            }
            self::$_names[$className] = $names;
        }
        return self::$_names[$className];
    }
}