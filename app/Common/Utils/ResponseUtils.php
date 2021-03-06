<?php
declare(strict_types=1);

namespace App\Common\Utils;


use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use App\Constants\BusinessCode;


/**
 * API请求返回服务类
 *
 * Class ResponseUtils
 *
 * @package App\Common\Utils
 */
class ResponseUtils
{
    /**
     * @var int
     */
    private $http_code = 200;

    /**
     * http头部信息
     *
     * @var string[]
     */
    private $http_headers = [
        'Author' => 'tieshengd',
    ];

    /**
     * 业务返回码
     * 举例：100000  请求成功
     *      100001  用户认证失败
     *      100002  非法的授权信息
     *      999999  请求出错
     *
     * @var int
     */
    private $business_code = 100000;

    /**
     * 业务返回消息
     *
     * @var string
     */
    private $business_msg = 'ok';

    /**
     * @Inject
     * @var ResponseInterface
     */
    private $response;

    /**
     * 设置http状态码
     *
     * @param int $code
     *
     * @return $this
     */
    public function setHttpCode(int $code = 200): self
    {
        $this->http_code = $code;

        return $this;
    }

    /**
     * 设置http头部信息
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setHttpHeader(string $name, $value): self
    {
        $this->http_headers[$name] = (string)$value;

        return $this;
    }

    /**
     * 成功数据返回
     *
     * @param mixed $data 返回数据
     * @param int $business_code 业务返回码
     *
     * @return ResponseInterface|PsrResponseInterface
     */
    public function success($data=[], int $business_code = 10000)
    {
        $this->business_code = $business_code;

        return $this->response($data);
    }

    /**
     * 失败返回
     *
     * @param mixed $data 返回数据
     * @param int $code 返回数据
     * @param int $business_code 错误业务码
     *
     * @return ResponseInterface|PsrResponseInterface
     */
    public function fail(int $code,int $business_code = 999999,$data = null)
    {
        $this->business_code = $business_code;
        $this->http_code     = $code;

        $this->business_msg  = BusinessCode::getMessage($business_code) ?? 'fail';

        return $this->response($data);
    }

    /**
     * 返回数据
     *
     * @param $data
     *
     * @return ResponseInterface|PsrResponseInterface
     */
    private function response($data)
    {
        $this->response = $this->response->json($this->normalizeData($data))->withStatus($this->http_code);

        if (!empty($this->http_headers)) {
            foreach ($this->http_headers as $name => $value) {
                $this->response = $this->response->withHeader($name, $value);
            }
        }

        return $this->response;
    }

    /**
     * 标准化返回数据格式
     *
     * @param mixed $data 业务返回数据
     *
     * @return array
     */
    private function normalizeData($data): array
    {
        return [
            'code' => $this->business_code,
            'data' => $data,
            'message' => $this->business_msg,
            'timestamp' => time(),
        ];
    }
}
