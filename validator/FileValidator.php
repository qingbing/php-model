<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\File;
use pf\helper\Unit;
use pf\PFBase;
use pf\web\UploadFile;

class FileValidator extends Validator
{
    /**
     * 允许上传的文件扩展名,默认为"null",表示允许所有的文件格式上传
     * @var null|array => ['gif', 'jpg', ....]
     */
    public $types;
    /**
     * 允许上传的文件的MIME类型，默认为"null",表示允许所有的"mime"类型，使用该属性，不要安装"fileinfo PECL"扩展
     * @var null | array
     */
    public $mimeTypes;
    /**
     * 上传文件所需的最小字节数
     * @var int
     */
    public $minSize;
    /**
     * 上传文件所需的最大字节数，大小限制也受“upload_max_filesize”INI设置和“MAX_FILE_SIZE”隐藏字段值的影响
     * @var int
     */
    public $maxSize; // 上传文件所需的最大字节数
    /**
     * 上传的文件过大时使用的错误消息
     * @var string
     */
    public $tooLargeMessage = 'The file "{file}" is too large. Its size cannot exceed {limit} bytes.';
    /**
     * 上传的文件太小时使用的错误消息
     * @var string
     */
    public $tooSmallMessage = 'The file "{file}" is too small. Its size cannot be smaller than {limit} bytes.';
    /**
     * 当上传的文件具有扩展名时使用的错误消息
     * @var string
     */
    public $wrongTypeMessage = 'The file "{file}" cannot be uploaded. Only files with these extensions are allowed: {extensions}.';
    /**
     * 当上传文件的"mime"类型不在"mimeTypes"列表中时使用的错误消息
     * @var string
     */
    public $wrongMimeTypeMessage = 'The file "{file}" cannot be uploaded. Only files of these MIME-types are allowed: {mimeTypes}.';
    /**
     * 可以保存的最大文件数，默认为"1"，可以通过定义更高的数字，同时上传多个文件
     * @var int
     */
    public $maxFiles = 1;
    /**
     * 上传的文件超过最大文件数时使用的错误消息
     * @var string
     */
    public $tooManyMessage = '"{attribute}" cannot accept more than {limit} files.';

    /**
     * 安全规则，不需要做任何验证规则
     * 对上传到的文件进行相关验证并处理
     * @param \pf\core\Model $object
     * @param $attribute
     * @return null
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($this->maxFiles > 1) {
            $files = $object->{$attribute};
            if (!is_array($files) || !isset($files[0]) || !$files[0] instanceof UploadFile) {
                $files = UploadFile::getInstances($object, $attribute);
            }
            if ([] === $files) {
                return $this->emptyAttribute($object, $attribute);
            }
            if (count($files) > $this->maxFiles) {
                $this->addError($object, $attribute, $this->tooManyMessage, [
                    '{attribute}' => $attribute,
                    '{limit}' => $this->maxFiles,
                ]);
            } else
                foreach ($files as $file) {
                    $this->validateFile($object, $attribute, $file);
                }
        } else {
            $file = $object->{$attribute};
            if (!$file instanceof UploadFile) {
                $file = UploadFile::getInstance($object, $attribute);
                if (null === $file) {
                    return $this->emptyAttribute($object, $attribute);
                }
            }
            $this->validateFile($object, $attribute, $file);
        }
        return null;
    }

    /**
     * @param \pf\core\Model $object the object being validated
     * @param string $attribute
     * @param UploadFile $file
     * @return null
     * @throws Exception
     */
    protected function validateFile($object, $attribute, $file)
    {
        if (null === $file || ($error = $file->getError()) == UPLOAD_ERR_NO_FILE) {
            return $this->emptyAttribute($object, $attribute);
        } else if (
            $error == UPLOAD_ERR_INI_SIZE
            || $error == UPLOAD_ERR_FORM_SIZE
            || (null !== $this->maxSize && $file->getSize() > $this->maxSize)
        ) {
            $this->addError($object, $attribute, $this->tooLargeMessage, [
                '{file}' => $file->getName(),
                '{limit}' => $this->getSizeLimit()
            ]);
        } else if ($error == UPLOAD_ERR_PARTIAL) {
            throw new Exception(PFBase::L('The file "{file}" was only partially uploaded.', [
                '{file}' => $file->getName(),
            ]));
        } else if ($error == UPLOAD_ERR_NO_TMP_DIR) {
            throw new Exception(PFBase::L('Missing the temporary folder to store the uploaded file "{file}".', [
                '{file}' => $file->getName(),
            ]));
        } else if ($error == UPLOAD_ERR_CANT_WRITE) {
            throw new Exception(PFBase::L('Failed to write the uploaded file "{file}" to disk.', [
                '{file}' => $file->getName(),
            ]));
        } else if (defined('UPLOAD_ERR_EXTENSION') && $error == UPLOAD_ERR_EXTENSION) { // available for PHP 5.2.0 or above
            throw new Exception('A PHP extension stopped the file upload.');
        }

        if (null !== $this->minSize && $file->getSize() < $this->minSize) {
            $this->addError($object, $attribute, $this->tooSmallMessage, [
                '{file}' => $file->getName(),
                '{limit}' => $this->minSize,
            ]);
        }

        if (null !== $this->types) {
            if (!is_array($this->types)) {
                throw new Exception('"FileValidator.types" must be an array.');
            }
            if (!in_array(strtolower($file->getExtensionName()), $this->types)) {
                $this->addError($object, $attribute, $this->wrongTypeMessage, [
                    '{file}' => $file->getName(),
                    '{extensions}' => implode(', ', $this->types),
                ]);
            }
        }


        if (null !== $this->mimeTypes) {
            if (!is_array($this->mimeTypes)) {
                throw new Exception('"FileValidator.mimeTypes" must be an array.');
            }
            $mimeType = File::getMimeType($file->getTempName());
            if (null === $mimeType || !in_array(strtolower($mimeType), $this->mimeTypes)) {
                $this->addError($object, $attribute, $this->wrongMimeTypeMessage, [
                    '{file}' => $file->getName(),
                    '{mimeTypes}' => implode(', ', $this->mimeTypes),
                ]);
            }
        }
        return null;
    }

    /**
     * 当上传文件为空时，根据是否允许为空选择是否报错
     * @param \pf\core\Model $object the object being validated
     * @param string $attribute the attribute being validated
     * @return null
     */
    protected function emptyAttribute($object, $attribute)
    {
        if (!$this->allowEmpty) {
            $message = $this->message !== null ? $this->message : '"{attribute}" can not be empty.';
            $this->addError($object, $attribute, $message);
        }
        return null;
    }

    /**
     * 返回上传文件允许的最大"size"，取决因素有以下三种情况：
     * <pre>
     * php.ini中的 "upload_max_filesize"
     * "MAX_FILE_SIZE" 隐藏字段
     * FileValidator.maxSize
     * </pre>
     * @return integer the size limit for uploaded files.
     */
    protected function getSizeLimit()
    {
        $limit = ini_get('upload_max_filesize');
        $limit = Unit::switchSize($limit, 'B');
        if (null !== $this->maxSize && $limit > 0 && $this->maxSize < $limit) {
            $limit = $this->maxSize;
        }
        if (isset($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE'] > 0 && $_POST['MAX_FILE_SIZE'] < $limit) {
            $limit = $_POST['MAX_FILE_SIZE'];
        }
        return $limit;
    }
}