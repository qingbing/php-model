# php-model
## 描述
model 的相关操作，包含了模型的后台验证功能

## 注意事项
 - model 的相关操作,包含有attributeNames、setAttributes、getAttributes、getAttributeLabel、getAttributeLabels 等
 - 对于设置了 model 属性后，采用 validate 进行验证，验证成功返回true，否则返回false
 - 当验证失败时，通过 getError、getErrors 可以获取相应的错误提示
 - 可以为 model 设置不同的 scenario 以便让验证适用不同的场景
 - model 支持的验证如下
   - "method" : 直接指定本类方法验证
   - "class" : 直接指定具体的验证类来进行验证
   - boolean : bool 值验证
   - compare : 对比验证
   - contact : 联系方式验证
   - callable : 回调方式验证
   - datetime : 时间验证， eg：2000-01-01 01:01:01
   - date : 日期验证，eg：2000-01-01
   - default : 默认值验证，当为null时，属性会被设置成默认值
   - ip : ip地址验证
   - mobile : 手机电话验证
   - multiIn : 范围内验证，支持多选项
   - numerical : 数字验证
   - password : 密码验证，6-18个字符
   - phone : 宅电验证
   - in : 范围内验证
   - email : 邮箱地址验证
   - match : 匹配验证
   - required : 必填验证
   - safe : 安全，该类验证没有验证实体
   - string : 字符串验证
   - type : 类型验证
   - url : url地址验证
   - username : 用户名验证
   - fax : 传真号验证
   - zip : 邮编验证


## 使用方法
```
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
```
## ====== 异常代码集合 ======

异常代码格式：1011 - XXX - XX （组件编号 - 文件编号 - 代码内异常）
```
 - 101100101 : 在Model"{model}"中找不到属性"{attribute}"
 - 101100102 : "{class}"指定了无效验证规则，规则必须指定验证器名和需要验证属性
 - 101100103 : 设置"{class}.{attribute}"失败
 
 - 101100201 : 无效的对比操作符"{operator}"
 
 - 101100301 : 属性"range"必须定义为数组
 
 - 101100401 : 属性"callback"必须指定为一个可调用的回调
 
 - 101100501 : 验证正则表达式无效
 
 - 101100601 : 必须指定"range"属性，且为数组列表
```