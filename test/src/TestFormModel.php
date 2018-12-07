<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-10-30
 * Version      :   1.0
 */

namespace Test;

use TestClass\TestForm;
use TestCore\Tester;

class TestFormModel extends Tester
{
    /**
     * 执行函数
     * @return mixed|void
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function run()
    {
        // $model 实例化
        $model = new TestForm('test');

        // 获取 model 的属性
        $attributeNames = $model->attributeNames();
        var_dump($attributeNames);

        // 获取所有或指定属性的值
        $attributes = $model->getAttributes();
        var_dump($attributes);

        // model 实例定义（model自定义）的属性标签
        $attributeLabels = $model->attributeLabels();
        var_dump($attributeLabels);

        // 获取指定属性的显示标签
        foreach ($attributeNames as $attributeName) {
            var_dump("{$attributeName} : {$model->getAttributeLabel($attributeName)}");
        }

        // 获取  model 所有标签
        $attributeLabels = $model->getAttributeLabels();
        var_dump($attributeLabels);

        // 获取所有验证器
        $attributeValidators = $model->getValidators();
        var_dump($attributeValidators);

        // 获取所有需要验证的属性
        $unSafeAttributeNames = $model->getUnSafeAttributeNames();
        var_dump($unSafeAttributeNames);

        // 为 model 设置属性值
        $model->setAttributes([
            'email' => 'ddd@ss.com',
            'boolean' => '1',
            'contact' => '028-46664743',
            'date' => '2018-09-01',
            'datetime' => '2018-09-01 01:01:01',
            'fax' => '028-46664743',
            'ip' => '192.128.11.2',
            'mobile' => '13999999999',
            'numerical' => '5',
            'password' => '511111',
            'phone' => '028-46664743',
            'required' => '028-46664743',
            'in' => 'pear',
            'string' => 'apple',
            'type' => '2018-09-01 01:01:01',
            'match' => '2018',
            'url' => 'http://www.baidu.com',
            'callable' => 11,
            'multiIn' => ['pear'],
            'username' => '12',
            'zip' => '12',
        ]);

        // 验证 model 属性
        if ($model->validate()) {
            // 获取属性值
            var_dump($model->getAttributes());
            var_dump('验证成功');
        } else {
            // 打印错误消息
            var_dump($model->getErrors());
            var_dump('验证失败');
        }
    }
}