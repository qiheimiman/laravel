<?php

namespace App\Validate;

use Illuminate\Support\Facades\Validator;
/**
 * 扩展验证器、封装验证场景
 */
class BaseValidate
{
    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [];

    /**
     * 验证提示信息
     * @var array
     */
    protected $message = [];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [];

    /**
     * 当前验证场景
     * @var string
     */
    protected $currentScene;

    /**
     * 验证失败错误信息
     * @var array
     */
    protected $error = [];

    /**
     * 场景需要验证的规则
     * @var array
     */
    protected $only = [];

    /**
     * 场景需要移除的验证规则
     * @var array
     */
    protected $remove = [];

    /**
     * 场景需要追加的验证规则
     * @var array
     */
    protected $append = [];

    /**
     * 验证正则定义
     * @var array
     */
    protected $regex = [];


    /**
     * 设置验证场景
     * @access public
     * @param  string  $name  场景名
     * @return $this
     */
    public function scene(string $name)
    {
        // 设置当前场景
        $this->currentScene = $name;

        return $this;
    }

    /**
     * 指定需要验证的字段列表
     * @access public
     * @param array $fields 字段名
     * @return $this
     */
    public function only(array $fields)
    {
        $this->only = $fields;

        return $this;
    }

    /**
     * 移除某个字段的验证规则
     * @access public
     * @param string|array $field 字段名
     * @param mixed        $rule  验证规则 true 移除所有规则
     * @return $this
     */
    public function remove($field, $rule = null)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                if (is_int($key)) {
                    $this->remove($rule);
                } else {
                    $this->remove($key, $rule);
                }
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->remove[$field] = $rule;
        }

        return $this;
    }

    /**
     * 追加某个字段的验证规则
     * @access public
     * @param string|array $field 字段名
     * @param mixed        $rule  验证规则
     * @return $this
     */
    public function append($field, $rule = null)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                $this->append($key, $rule);
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->append[$field] = $rule;
        }

        return $this;
    }

    /**
     * 数据自动验证
     * @access public
     * @param array $data  数据
     * @param array $rules 验证规则
     * @return bool
     */
    public function check(array $data, array $rules = []): bool
    {
        $this->error =[];

        if ($this->currentScene) {
            $this->getScene($this->currentScene);
        }

        if (empty($rules)) {
            // 读取验证规则
            $rules = $this->rule;
        }

        foreach ($this->append as $key => $rule) {
            if (!isset($rules[$key])) {
                $rules[$key] = $rule;
                unset($this->append[$key]);
            }
        }


        //如果场景需要验证的规则不为空
        if (!empty($this->only)) {
            $new_rules = [];
            foreach ($this->only as $key => $value) {
                if (array_key_exists($value,$rules)) {
                    $new_rules[$value] = $rules[$value];
                }
            }
            $rules = $new_rules;
        }
//        var_dump($data);die;
        $validator = Validator::make($data,$rules,$this->message);

        //验证失败
        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            return false;
        }

        return !empty($this->error) ? false : true;
    }

    /**
     * 获取数据验证的场景
     * @access protected
     * @param string $scene 验证场景
     * @return void
     */
    protected function getScene(string $scene): void
    {
        $this->only = $this->append = $this->remove = [];

        if (method_exists($this, 'scene' . $scene)) {
            call_user_func([$this, 'scene' . $scene]);
        } elseif (isset($this->scene[$scene])) {
            // 如果设置了验证适用场景
            $this->only = $this->scene[$scene];
        }
    }

    /**
     * 获取错误信息
     * @access public
     * @return array|string
     */
    public function getError()
    {
        return $this->error;
    }

}
