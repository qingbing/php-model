<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-10-30
 * Version      :   1.0
 */

class Html
{
    const ID_PREFIX = 'cf_'; // HTML-TAG 的id前缀
    static protected $count = 0; // 当前命名的空间的个数

}


class Htmlxxx
{

    /**
     * 获取当前应用的 "url"
     * @param string $url
     * @return string
     */
    static protected function normalizeUrl($url)
    {
        if (is_array($url)) {
            if (isset($url[0])) {
                if (($c = PFBase::app()->getController()) !== null) {
                    $url = $c->createUrl($url[0], array_splice($url, 1));
                } else {
                    $url = PFBase::app()->createUrl($url[0], array_splice($url, 1));
                }
            } else {
                $url = '';
            }
        }
        if ('' !== $url) {
            return $url;
        }
        return PFBase::app()->getRequest()->getUrl();
    }

    /**
     * 返回一个渲染了属性的 HTML-CODE
     * @param array $htmlOptions
     * @return string
     */
    static protected function renderAttributes($htmlOptions = [])
    {
        static $singleAttributes = [
            'selected' => 1,
            'checked' => 1,
            'disabled' => 1,
            'readonly' => 1,
            'multiple' => 1,
            'noresize' => 1,
            'declare' => 1,
            'defer' => 1,
            'ismap' => 1,
            'nohref' => 1,
        ];
        if ([] === $htmlOptions) {
            return '';
        }

        if (isset($htmlOptions['encode'])) {
            $encode = !!$htmlOptions['encode'];
            unset($htmlOptions['encode']);
        } else {
            $encode = false;
        }

        $html = '';
        if ($encode) {
            foreach ($htmlOptions as $name => $value) {
                if (isset($singleAttributes[$name])) {
                    $html .= $value ? (" {$name}=\"{$name}\"") : '';
                } else if (null !== $value) {
                    $html .= " {$name}=\"{$value}\"";
                }
            }
        } else {
            foreach ($htmlOptions as $name => $value) {
                if (isset($singleAttributes[$name])) {
                    $html .= $value ? (" {$name}=\"{$name}\"") : '';
                } else if (null !== $value) {
                    $html .= ' ' . $name . '="' . self::encode($value) . '"';
                }
            }
        }
        return $html;
    }

    /**
     * 为HTML控件生成ID
     * @param string $name
     * @return string
     */
    static protected function getIdByName($name)
    {
        return str_replace(['[]', '][', '[', ']'], ['', '_', '_', ''], $name);
    }

    /**
     * 编码HTML特殊字符
     * @param string $text
     * @return string
     */
    static public function encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, PFBase::app()->charset);
    }

    /**
     * 解码HTML特殊字符
     * @param string $text
     * @return string
     */
    static public function decode($text)
    {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }

    /**
     * 输出 data
     * @param string $text
     * @return string
     */
    static public function cdata($text)
    {
        return '<![CDATA[' . $text . ']]>';
    }

    /**
     * 生成并返回 HTML-TAG 的标签代码
     * @param string $tag
     * @param array $htmlOptions
     * @param mixed $content
     * @param bool $closeTag
     * @return string
     */
    static public function tag($tag, $htmlOptions = [], $content = false, $closeTag = true)
    {
        $html = '<' . $tag . self::renderAttributes($htmlOptions);
        if (false === $content) {
            $html .= $closeTag ? ' />' : '>';
        } else {
            $html .= $closeTag ? ('>' . $content . '</' . $tag . '>') : ('>' . $content);
        }
        return $html;
    }

    /**
     * 生成并返回开始 OpenTag 的标签代码
     * @param string $tag
     * @param array $htmlOptions
     * @return string
     */
    static public function openTag($tag, $htmlOptions = [])
    {
        return '<' . $tag . self::renderAttributes($htmlOptions) . '>';
    }

    /**
     * 生成并返回关闭 OpenTag 的标签代码
     * @param string $tag
     * @return string
     */
    static public function closeTag($tag)
    {
        return '</' . $tag . '>';
    }

    /**
     * 生成并返回一个 header-meta 标签代码
     * @param string $content
     * @param string $name
     * @param string $httpEquiv
     * @param array $options
     * @return string
     */
    static public function metaTag($content, $name = null, $httpEquiv = null, $options = [])
    {
        if (null !== $name) {
            $options['name'] = $name;
        }
        if (null !== $httpEquiv) {
            $options['http-equiv'] = $httpEquiv;
        }
        $options['content'] = $content;
        return self::tag('meta', $options);
    }

    /**
     * 生成并返回一个 link 标签代码
     * @param string $relation
     * @param string $type
     * @param string $href
     * @param string $media
     * @param array $options
     * @return string
     */
    static public function linkTag($relation = null, $type = null, $href = null, $media = null, $options = [])
    {
        if (null !== $relation) {
            $options['ref'] = $relation;
        }
        if (null !== $type) {
            $options['type'] = $type;
        }
        if (null !== $href) {
            $options['href'] = $href;
        }
        if (null !== $media) {
            $options['media'] = $media;
        }
        return self::tag('link', $options);
    }

    /**
     * 生成并返回用 CSS-TAG 包裹 css 内容的标签代码
     * @param string $text the CSS content.
     * @param string $media
     * @return string
     */
    static public function css($text, $media = '')
    {
        if ('' !== $media) {
            $media = ' media="' . $media . '"';
        }
        return "<style type=\"text/css\"{$media}>\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n</style>";
    }

    /**
     * 生成并返回引入 css 文件的标签代码
     * @param string $url CSS URL
     * @param string $media
     * @return string  CSS link.
     */
    static public function cssFile($url, $media = '')
    {
        if ('' !== $media) {
            $media = ' media="' . $media . '"';
        }
        return '<link rel="stylesheet" type="text/css" href="' . self::encode($url) . '"' . $media . ' />';
    }

    /**
     * 生成并返回用 JS-TAG 包裹 js 内容的标签代码
     * @param string $text
     * @return string
     */
    static public function script($text)
    {
        return "<script type=\"text/javascript\">\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n</script>";
    }

    /**
     * 生成并返回引入 js 文件的标签代码
     *
     * @param string $url the url for javascript file.
     * @return string
     */
    static public function scriptFile($url)
    {
        return '<script type="text/javascript" src="' . self::encode($url) . '"></script>';
    }

    /**
     * 为 HTML 加入刷新的 "meta"
     * @param int $seconds
     * @param string $url
     */
    static public function refresh($seconds = 3, $url = '')
    {
        $content = "$seconds";
        if ('' !== $url) {
            $content .= ';' . self::normalizeUrl($url);
        }
        PFBase::app()->getClientScript()->registerMetaTag($content, null, 'refresh');
    }

    /**
     * 生成 HTML 的 label 标签代码
     * @param string $text
     * @param string $for
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function label($text, $for, $htmlOptions = [])
    {
        if (false === $for) {
            unset($htmlOptions['for']);
        } else {
            $htmlOptions['for'] = $for;
        }
        return self::tag('label', $htmlOptions, $text);
    }

    /**
     * 生成 HTML 的 input 标签代码
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static protected function inputField($type, $name, $value, $htmlOptions = [])
    {
        $htmlOptions['type'] = $type;
        $htmlOptions['name'] = $name;
        $htmlOptions['value'] = $value;

        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = self::getIdByName($name);
        } else if ($htmlOptions['id'] === false) {
            unset($htmlOptions['id']);
        }
        return self::tag('input', $htmlOptions, false, true);
    }

    /**
     * 生成并返回一个 a 标签代码
     * @param string $text
     * @param string $url
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function link($text, $url = "#", $htmlOptions = [])
    {
        if ('' !== $url) {
            $htmlOptions['href'] = self::normalizeUrl($url);
        }
        return self::tag('a', $htmlOptions, $text);
    }

    /**
     * 生成并返回带有 mailto 的 a 标签代码
     * @param string $text
     * @param string $email
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function mailto($text, $email = '', $htmlOptions = [])
    {
        if ('' === $email) {
            $email = $text;
        }
        return self::link($text, 'mailto:' . $email, $htmlOptions);
    }

    /**
     * 生成并返回一个 IMAGE 标签代码
     * @param string $src
     * @param string $alt
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function image($src, $alt = '', $htmlOptions = [])
    {
        $htmlOptions['src'] = $src;
        if (!'' === $alt) {
            $htmlOptions['alt'] = $alt;
        }
        return self::tag('img', $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-BUTTON 标签代码
     * @param string $label
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function button($label = 'button', $htmlOptions = [])
    {
        if (!isset($htmlOptions['name'])) {
            $htmlOptions['name'] = self::ID_PREFIX . self::$count++;
        }
        if (!isset($htmlOptions['type'])) {
            $htmlOptions['type'] = 'button';
        }
        if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = $label;
        }
        return self::tag('input', $htmlOptions);
    }

    /**
     * 生成并返回 BUTTON 的标签代码
     * @param string $label
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function htmlButton($label = 'button', $htmlOptions = [])
    {
        if (!isset($htmlOptions['name'])) {
            $htmlOptions['name'] = self::ID_PREFIX . self::$count++;
        }
        if (!isset($htmlOptions['type'])) {
            $htmlOptions['type'] = 'button';
        }
        return self::tag('button', $htmlOptions, $label);
    }

    /**
     * 生成并返回 SUBMIT-BUTTON 的标签代码
     * @param string $label
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function submitButton($label = 'submit', $htmlOptions = [])
    {
        $htmlOptions['type'] = 'submit';
        return self::button($label, $htmlOptions);
    }

    /**
     * 生成并返回 RESET-BUTTON 的标签代码
     * @param string $label
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function resetButton($label = 'reset', $htmlOptions = [])
    {
        $htmlOptions['type'] = 'reset';
        return self::button($label, $htmlOptions);
    }

    /**
     * 生成并返回 IMAGE-BUTTON 的标签代码
     * @param string $src
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function imageButton($src, $htmlOptions = [])
    {
        $htmlOptions['src'] = $src;
        $htmlOptions['type'] = 'image';
        return self::button('submit', $htmlOptions);
    }

    /**
     * 生成并返回 LINK-BUTTON 的标签代码
     * @param string $label
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function linkButton($label = 'submit', $htmlOptions = [])
    {
        if (!isset($htmlOptions['submit'])) {
            $htmlOptions['submit'] = isset($htmlOptions['href']) ? $htmlOptions['href'] : '';
        }
        return self::link($label, '#', $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-TEXT 的标签代码
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    static public function textField($name, $value = '', $htmlOptions = [])
    {
        return self::inputField('text', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-PASSWORD 的标签代码
     * @param $name
     * @param string $value
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function passwordField($name, $value = '', $htmlOptions = [])
    {
        return self::inputField('password', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-FILE 的标签代码
     * @param string $name
     * @param string $value
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function fileField($name, $value = '', $htmlOptions = [])
    {
        return self::inputField('file', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-HIDDEN 的标签代码
     * @param string $name
     * @param string $value
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function hiddenField($name, $value = '', $htmlOptions = [])
    {
        return self::inputField('hidden', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 TEXT-AREA 的标签代码
     * @param string $name
     * @param string $value
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function textArea($name, $value = '', $htmlOptions = [])
    {
        $htmlOptions['name'] = $name;
        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = self::getIdByName($name);
        } else if (false === $htmlOptions['id']) {
            unset($htmlOptions['id']);
        }
        return self::tag('textarea', $htmlOptions, isset($htmlOptions['encode']) && !$htmlOptions['encode'] ? $value : self::encode($value));
    }

    /**
     * 生成并返回 INPUT-RADIO 的标签代码
     * @param string $name
     * @param bool $checked
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function radioButton($name, $checked = false, $htmlOptions = [])
    {
        if ($checked) {
            $htmlOptions['checked'] = 'checked';
        } else {
            unset($htmlOptions['checked']);
        }
        $value = isset($htmlOptions['value']) ? $htmlOptions['value'] : 1;

        if (isset($htmlOptions['is_list'])) {
            unset($htmlOptions['is_list']);
            $hidden = '';
        } else {
            // Get hidden file to make sure the field can be accepted by form.
            $hiddenOptions = isset($htmlOptions['id']) ? ['id' => self::ID_PREFIX . $htmlOptions['id']] : ['id' => false];
            $hidden = self::hiddenField($name, 0, $hiddenOptions);
        }
        return $hidden . self::inputField('radio', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-RADIO-GROUP 的标签代码
     * @param string $name
     * @param mixed $select => array, string
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function radioButtonList($name, $select, $data, $htmlOptions = [])
    {
        $template = isset($htmlOptions['template']) ? $htmlOptions['template'] : '{input} {label}';
        $separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : "<br>\n";
        $labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : [];
        unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['labelOptions']);
        $items = [];
        $baseId = self::getIdByName($name);
        $i = 0;
        $htmlOptions['is_list'] = 1;
        foreach ($data as $value => $text) {
            $checked = !strcmp($value, $select);
            $htmlOptions['value'] = $value;
            $htmlOptions['id'] = $baseId . '_' . $i++;
            $option = self::radioButton($name, $checked, $htmlOptions);
            $label = self::label($text, $htmlOptions['id'], $labelOptions);
            $items[] = strtr($template, ['{input}' => $option, '{label}' => $label]);
        }
        return self::tag('span', ['id' => $baseId,], implode($separator, $items));
    }

    /**
     * 生成并返回 INPUT-CHECKBOX 的标签代码
     * @param string $name
     * @param bool $checked
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function checkBox($name, $checked = false, $htmlOptions = [])
    {
        if ($checked) {
            $htmlOptions['checked'] = 'checked';
        } else {
            unset($htmlOptions['checked']);
        }
        $value = isset($htmlOptions['value']) ? $htmlOptions['value'] : 1;

        if (isset($htmlOptions['is_list'])) {
            unset($htmlOptions['is_list']);
            $hidden = '';
        } else {
            // Get hidden file to make sure the field can be accepted by form.
            $hiddenOptions = isset($htmlOptions['id']) ? ['id' => self::ID_PREFIX . $htmlOptions['id']] : ['id' => false];
            $hidden = self::hiddenField($name, 0, $hiddenOptions);
        }
        return $hidden . self::inputField('checkbox', $name, $value, $htmlOptions);
    }

    /**
     * 生成并返回 INPUT-CHECKBOX-GROUP 的标签代码
     * @param string $name
     * @param mixed $select => array, string
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function checkBoxList($name, $select, $data = [], $htmlOptions = [])
    {
        $template = isset($htmlOptions['template']) ? $htmlOptions['template'] : '{input} {label}';
        $separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : "<br>\n";
        $labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : [];
        unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['labelOptions']);
        if ('[]' !== substr($name, -2)) {
            $name .= '[]';
        }

        $items = [];
        $baseId = self::getIdByName($name);
        $i = 0;
        $htmlOptions['is_list'] = 1;
        foreach ($data as $value => $text) {
            $checked = (!is_array($select) && !strcmp($value, $select)) || (is_array($select) && in_array($value, $select));
            $htmlOptions['value'] = $value;
            $htmlOptions['id'] = $baseId . '_' . $i++;
            $option = self::checkBox($name, $checked, $htmlOptions);
            $label = self::label($text, $htmlOptions['id'], $labelOptions);
            $items[] = strtr($template, ['{input}' => $option, '{label}' => $label]);
        }
        return self::tag('span', ['id' => $baseId,], implode($separator, $items));
    }

    /**
     * 生成并返回 SELECT 的标签代码
     * @param string $name
     * @param string $select
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function dropDownList($name, $select, $data, $htmlOptions = [])
    {
        $htmlOptions['name'] = $name;
        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = self::getIdByName($name);
        } else if ($htmlOptions['id'] === false) {
            unset($htmlOptions['id']);
        }
        $options = "\n" . self::listOptions($select, $data, $htmlOptions);
        return self::tag('select', $htmlOptions, $options);
    }

    /**
     * 生成并返回 SELECT-MULTI 的标签代码
     * @param string $name
     * @param mixed $select => array, string
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function listBox($name, $select, $data, $htmlOptions = [])
    {
        if (!isset($htmlOptions['size'])) {
            $htmlOptions['size'] = 4;
        }
        if (isset($htmlOptions['multiple'])) {
            if ('[]' !== substr($name, -2)) {
                $name .= '[]';
            }
        }
        return self::dropDownList($name, $select, $data, $htmlOptions);
    }

    /**
     * 生成并返回 SELECT-MULTI-GROUP 的标签代码
     * @param mixed $selection
     * @param array $listData
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static protected function listOptions($selection, $listData, &$htmlOptions = [])
    {
        $encode = isset($htmlOptions['encode']) && !$htmlOptions['encode'];
        $content = '';
        if (isset($htmlOptions['prompt'])) {
            $content .= '<option value="">' . strtr($htmlOptions['prompt'], ['<' => '&lt;', '>' => '&gt;']) . "</option>\n";
        }
        if (isset($htmlOptions['options'])) {
            $options = $htmlOptions['options'];
        } else {
            $options = [];
        }
        foreach ($listData as $key => $value) {
            if (is_array($value)) {
                $content .= '<optgroup label="' . ($encode ? $key : self::encode($key)) . "\">\n";
                $dummy = ['options' => $options,];
                if (isset($htmlOptions['encode'])) {
                    $dummy['encode'] = $htmlOptions['encode'];
                }
                $content .= self::listOptions($selection, $value, $dummy);
                $content .= '</optgroup>' . "\n";
            } else {
                $attributes = ['value' => (string)$key, 'encode' => !$encode,];
                if ((!is_array($selection) && !strcmp($key, $selection)) || (is_array($selection) && in_array($key, $selection))) {
                    $attributes['selected'] = 'selected';
                }
                $content .= self::tag('option', $attributes, $encode ? (string)$value : self::encode($value)) . "\n";
            }
        }
        unset($htmlOptions['encode'], $htmlOptions['prompt'], $htmlOptions['options']);
        return $content;
    }

    /**
     * 生成并返回 FORM 的开始标签代码
     * @param mixed $action
     * @param string $method
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function beginForm($action = '', $method = 'post', $htmlOptions = [])
    {
        $htmlOptions['action'] = $url = self::normalizeUrl($action);
        $htmlOptions['method'] = $method;
        $form = self::tag('form', $htmlOptions, false, false);
        $hiddens = [];
        if (!strcasecmp($method, 'get') && ($pos = strpos($url, '?')) !== false) {
            foreach (explode('&', substr($url, $pos + 1)) as $pair) {
                if (($pos = strpos($pair, '=')) !== false) {
                    $hiddens[] = self::hiddenField(urldecode(substr($pair, 0, $pos)), urldecode(substr($pair, $pos + 1)), ['id' => false]);
                } else {
                    $hiddens[] = self::hiddenField(urldecode($pair), '', ['id' => false]);
                }
            }
        }
        if ($hiddens !== []) {
            $form .= "\n" . self::tag('div', ['style' => 'display:none'], implode("\n", $hiddens));
        }
        return $form;
    }

    /**
     * 生成并返回 FORM 的结束标签代码
     * @return string
     * @see beginForm
     */
    static public function endForm()
    {
        return '</form>';
    }

    /**
     * 生成并返回 model-label 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeLabel($model, $attribute, $htmlOptions = [])
    {
        if (isset($htmlOptions['for'])) {
            $for = $htmlOptions['for'];
        } else {
            $for = self::getIdByName(self::resolveName($model, $attribute));
        }

        if (isset($htmlOptions['label'])) {
            if (false === ($label = $htmlOptions['label'])) {
                return '';
            }
        } else
            $label = $model->getAttributeLabel($attribute);
        unset($htmlOptions['for'], $htmlOptions['label']);
        return self::label($label, $for, $htmlOptions);
    }

    /**
     * 生成并返回 model-input 标签代码
     * @param string $type
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static protected function activeInputField($type, $model, $attribute, $htmlOptions = [])
    {
        $htmlOptions['type'] = $type;
        if ('text' === $type || 'password' === $type) {
            if (!isset($htmlOptions['maxlength'])) {
                foreach ($model->getValidators($attribute) as $validator) {
                    if ($validator instanceof StringValidator && null !== $validator->maxLength) {
                        $htmlOptions['maxlength'] = $validator->maxLength;
                        break;
                    }
                }
            } else if ($htmlOptions['maxlength'] === false) {
                unset($htmlOptions['maxlength']);
            }
        }

        if ($type === 'file') {
            unset($htmlOptions['value']);
        } else if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = self::resolveValue($model, $attribute);
        }
        return self::tag('input', $htmlOptions);
    }

    /**
     * 生成并返回 model-input-text 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeTextField($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        return self::activeInputField('text', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-input-hidden 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeHiddenField($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        return self::activeInputField('hidden', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-input-password 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activePasswordField($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        return self::activeInputField('password', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-input-file 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeFileField($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        return self::activeInputField('file', $model, $attribute, $htmlOptions);
        $hiddenOptions = isset($htmlOptions['id']) ? ['id' => self::ID_PREFIX . $htmlOptions['id']] : ['id' => false];
        return self::hiddenField($htmlOptions['name'], '', $hiddenOptions) . self::activeInputField('file', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-textarea 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeTextArea($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $text = self::resolveValue($model, $attribute);
        return self::tag('textarea', $htmlOptions, isset($htmlOptions['encode']) && !$htmlOptions['encode'] ? $text : self::encode($text));
    }

    /**
     * 生成并返回 model-input-radio 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeRadioButton($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = 1;
        }
        if (!isset($htmlOptions['checked']) && $htmlOptions['value'] == self::resolveValue($model, $attribute)) {
            $htmlOptions['checked'] = 'checked';
        }

        // Get hidden file to make sure the field can be accepted by form.
        $hiddenOptions = isset($htmlOptions['id']) ? ['id' => self::ID_PREFIX . $htmlOptions['id']] : ['id' => false];
        $hidden = self::hiddenField($htmlOptions['name'], 0, $hiddenOptions);

        return $hidden . self::activeInputField('radio', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-input-checkbox 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeCheckBox($model, $attribute, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = 1;
        }
        if (!isset($htmlOptions['checked']) && $htmlOptions['value'] == self::resolveValue($model, $attribute)) {
            $htmlOptions['checked'] = 'checked';
        }

        $hiddenOptions = isset($htmlOptions['id']) ? ['id' => self::ID_PREFIX . $htmlOptions['id']] : ['id' => false];
        $hidden = self::hiddenField($htmlOptions['name'], 0, $hiddenOptions);
        return $hidden . self::activeInputField('checkbox', $model, $attribute, $htmlOptions);
    }

    /**
     * 生成并返回 model-select 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeDropDownList($model, $attribute, $data, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        $options = "\n" . self::listOptions($selection, $data, $htmlOptions);
        if (isset($htmlOptions['multiple'])) {
            if (substr($htmlOptions['name'], -2) !== '[]') {
                $htmlOptions['name'] .= '[]';
            }
        }
        return self::tag('select', $htmlOptions, $options);
    }

    /**
     * 生成并返回 model-select-list 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeListBox($model, $attribute, $data, $htmlOptions = [])
    {
        if (!isset($htmlOptions['size'])) {
            $htmlOptions['size'] = 4;
        }
        return self::activeDropDownList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * 生成并返回 model-checkbox-list 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeCheckBoxList($model, $attribute, $data, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);
        return self::checkBoxList($name, $selection, $data, $htmlOptions);
    }

    /**
     * 生成并返回 model-radio-group 标签代码
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     * @return string
     */
    static public function activeRadioButtonList($model, $attribute, $data, $htmlOptions = [])
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);
        return self::radioButtonList($name, $selection, $data, $htmlOptions);
    }

    /**
     * 返回 model 中属性渲染的表单名
     * @param \pf\core\Model $model
     * @param string $attribute
     * @return string
     */
    public static function resolveName($model, &$attribute)
    {
        $fn = get_class($model);
        $className = substr($fn, strrpos($fn, '\\') + 1);
        if (false !== ($pos = strpos($attribute, '['))) {
            if (0 !== $pos) {
                // e.g. name[a][b]
                return $className . '[' . substr($attribute, 0, $pos) . ']' . substr($attribute, $pos);
            }
            if (false !== ($pos = strpos($attribute, ']')) && $pos !== strlen($attribute) - 1) {
                // e.g. [a][b]name
                $sub = substr($attribute, 0, $pos + 1);
                $attribute = substr($attribute, $pos + 1);
                return $className . $sub . '[' . $attribute . ']';
            }
            if (preg_match('/\](\w+\[.*)$/', $attribute, $matches)) {
                $name = $className . '[' . str_replace(']', '][', trim(strtr($attribute, ['][' => ']', '[' => ']']), ']')) . ']';
                $attribute = $matches[1];
                return $name;
            }
        }
        return $className . '[' . $attribute . ']';
    }

    /**
     * 返回 model 中属性渲染的表单名
     * @param \pf\core\Model $model
     * @param string $attribute
     * @param array $htmlOptions additional HTML attributes for the HTML tag.
     */
    static public function resolveNameId($model, &$attribute, &$htmlOptions)
    {
        if (!isset($htmlOptions['name'])) {
            $htmlOptions['name'] = self::resolveName($model, $attribute);
        }
        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = self::getIdByName($htmlOptions['name']);
        } else if (false === $htmlOptions['id']) {
            unset($htmlOptions['id']);
        }
    }

    /**
     * 解析并返回模型的值
     * 可以识别以数组格式写入的属性名称，eg:如果属性名称是"name [a] [b]"，将返回值"$ model-> name ['a'] ['b']"
     * @param \pf\core\Model $model
     * @param string $attribute attribute name
     * @return mixed attribute value
     */
    static public function resolveValue($model, $attribute)
    {
        if (false !== ($pos = strpos($attribute, '['))) {
            if (0 === $pos) { // [a]name[b][c], should ignore [a]
                if (preg_match('/\](\w+)/', $attribute, $matches)) {
                    $attribute = $matches[1];
                }
                if (false === ($pos = strpos($attribute, '['))) {
                    return $model->{$attribute};
                }
            }
            $name = substr($attribute, 0, $pos);
            $value = $model->{$name};
            $ta = explode('][', rtrim(substr($attribute, $pos + 1), ']'));
            foreach ($ta as $id) {
                if (is_array($value) && isset($value[$id])) {
                    $value = $value[$id];
                } else {
                    return null;
                }
            }
            return $value;
        }
        return $model->{$attribute};
    }
}