<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\Unit;
use pf\PFBase;

class CaptchaValidator extends Validator
{
    public $message = 'The verification code is incorrect.';
    public $caseSensitive = false; // 验证码对比是否区分大小写
    public $captchaAction = 'captcha'; // 验证码的验证码所在的action路由

    /**
     * 安全规则，不需要做任何验证规则
     * @param \pf\core\Model $object
     * @param $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        $captcha = $this->getCaptchaAction();
        if (is_array($value) || !$captcha->validate($value, $this->caseSensitive)) {
            $this->addError($object, $attribute, $this->message);
        }
    }

    /**
     * 返回验证码所在的"action"
     * @return \pf\web\action\Captcha
     * @throws Exception
     */
    protected function getCaptchaAction()
    {
        if (null === $captcha = PFBase::app()->getController()->createAction($this->captchaAction)) {
            if (false !== strpos($this->captchaAction, '/')) {
                // contains controller or module
                if (null !== ($ca = PFBase::app()->createController($this->captchaAction))) {
                    list($controller, $actionID) = $ca;
                    /* @var \pf\web\Controller $controller */
                    $captcha = $controller->createAction($actionID);
                }
            }
            if (null === $captcha) {
                throw new Exception(Unit::replace('CaptchaValidator.action "{id}" is invalid. Unable to find such an action in the current controller.', [
                    '{id}' => $this->captchaAction
                ]));
            }
        }
        return $captcha;
    }
}