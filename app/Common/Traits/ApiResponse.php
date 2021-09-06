<?php
declare(strict_types=1);
namespace App\Common\Traits;

use App\Constants\BusinessCode;
use App\Constants\HttpCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait ApiResponse
 * @author Colorado
 * @package App\Traits
 */
trait ApiResponse
{
    private $httpCode = 200;
    private $errorCode = 100000;
    private $errorMsg = '系统错误';
    private $headers = [];

    protected $response;

    /**
     * 成功响应
     * @param mixed $data
     * @return ResponseInterface
     */
    public function success($data=[]): ResponseInterface
    {
        return $this->respond([
            'code' => BusinessCode::SUCCESS_REQUEST,
            'msg' => BusinessCode::getMessage(BusinessCode::SUCCESS_REQUEST),
            'data' => $data,
            'time' => date("Y-m-d H:i:s",time())
        ]);
    }

    /**
     * 错误返回
     * @param int    $err_code    错误业务码
     * @param string $err_msg     错误信息
     * @param array  $data        额外返回的数据
     * @return ResponseInterface
     */
    public function error(int $err_code = null,string $err_msg = null, array $data = []): ResponseInterface
    {
        if (!Context::has('httpCode')){
            Context::set('httpCode',400);
        }
        return $this->respond([
            'code' => $err_code ?? $this->errorCode,
            'msg' => $err_msg ?? (BusinessCode::getMessage($err_code) ?? ''),
            'data' => $data,
            'time' => date("Y-m-d H:i:s",time())
        ]);
    }

    /**
     * 设置http返回码
     * @param int $code    http返回码
     * @return $this
     */
    final public function setHttpCode(int $code = 200): self
    {
        Context::set('httpCode',$code);
        return $this;
    }

    /**
     * 设置返回头部header值
     * @param string $key
     * @param        $value
     * @return $this
     */
    public function addHttpHeader(string $key, $value): self
    {
        $this->headers += [$key => $value];
        return $this;
    }

    /**
     * 批量设置头部返回
     * @param array $headers    header数组：[key1 => value1, key2 => value2]
     * @return $this
     */
    public function addHttpHeaders(array $headers = []): self
    {
        $this->headers += $headers;
        return $this;
    }

    /**
     * @param null|array|Arrayable|Jsonable|string $response
     * @return ResponseInterface
     */
    private function respond($response): ResponseInterface
    {
        if (is_string($response)) {
            return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream($response));
        }

        if (is_array($response) || $response instanceof Arrayable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream(Json::encode($response)));
        }

        if ($response instanceof Jsonable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream((string)$response));
        }

        return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream((string)$response));
    }

    /**
     * 获取 Response 对象
     * @return mixed|ResponseInterface|null
     */
    protected function response(): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        foreach ($this->headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        $httpCode = Context::get('httpCode','200');
        $response = $response->withStatus($httpCode);

        return $response;
    }
}
