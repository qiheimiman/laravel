<?php


namespace App\Admin\Validate;

use App\Validate\BaseValidate;
class IndexValidate extends BaseValidate
{

    protected $rule = [
        'id' => ['required'],

    ];

    protected $message = [
        'id.required' => 'ID必填啊',
    ];

    protected $scene = [
        'index' => ['id'],
    ];

}
