<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\db\Criteria;

class UniqueValidator extends Validator
{
    public $message = '"{attribute}"."{value}" has already been taken.';
    public $caseSensitive = true; // 是否区分大小写
    public $allowEmpty = true; // 允许为空

    /**
     * 安全规则，不需要做任何验证规则
     * @param \pf\db\ActiveRecord $object
     * @param $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        $criteria = new Criteria();
        if (!$object->getIsNewRecord()) {
            $bk = ":{$object->primaryKey()}";
            $criteria->addWhere("`{$object->primaryKey()}`!={$bk}");
            $criteria->addParam($bk, $object->getPrimaryKey());
        }
        $bk = ":{$attribute}";
        $criteria->addParam($bk, $value);
        if ($this->caseSensitive) {
            $criteria->addWhere("`{$attribute}`={$bk}");
        } else {
            $criteria->addWhere("LOWER(`{$attribute}`)=LOWER({$bk})");
        }
        $record = $object->find($criteria);
        if (null !== $record) {
            $this->addError($object, $attribute, $this->message, [
                '{value}' => $value,
            ]);
        }
    }
}