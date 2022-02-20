<?php

namespace App\Common\response;


use Illuminate\Http\JsonResponse;

class Response
{

    /**
     * 错误码
     * @var
     */
    public $code;

    /**
     * 错误信息
     * @var
     */
    public $message;

    /**
     * data
     * @var
     */
    public $data;

    private function __construct($code, $message, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * 请求成功的方法
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public static function success($data = [], $message = 'success')
    {
        if(empty($data)){
            $data = (object)$data;
        }
        $instance = new self(200, $message, $data);

        return response()->json($instance);
    }
    /**
     * 请求成功的方法，该方法可以返回 data = null 的对象
     * @param $data

     */
    public static function success_null($data = null)
    {
        $instance = new self(200, "success", $data);
        return response()->json($instance);
    }
    /**
     * 请求错误
     * @param $code
     * @param null $message
     * @return JsonResponse
     */
    public static function error($code = [], $message = null)
    {
        if (is_array($code)) {
            $message = isset($code['message']) && $message == null ? $code['message'] : $message;
            $code = isset($code['code']) ? $code['code'] : null;
        }
        $instance = new self($code, $message, new \stdClass());
        return response()->json($instance);
    }

}
