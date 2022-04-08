<?php

namespace BringupMinabe\ShiftSdkPhp;

use BringupMinabe\ShiftSdkPhp\Exception\ClientErrorException;
use BringupMinabe\ShiftSdkPhp\Exception\InternalServerErrorException;
use BringupMinabe\ShiftSdkPhp\Exception\NotFoundException;
use BringupMinabe\ShiftSdkPhp\Exception\RoleErrorException;
use BringupMinabe\ShiftSdkPhp\Exception\UnauthorizedException;
use BringupMinabe\ShiftSdkPhp\Exception\UnprocessableEntityException;
use Exception;

class ShiftSdkPhp
{

    const API_PREFIX = 'ex-app';

    /**
     * apiBaseUrl
     *
     * @var string
     */
    protected $apiBaseUrl;

    /**
     * apiKey
     *
     * @var string
     */
    private $apiKey;

    /**
     * apiSecret
     *
     * @var string
     */
    private $apiSecret;

    /**
     * token
     *
     * @var string
     */
    protected $token;

    /**
     * __construct
     *
     * @param string $apiBaseUrl
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct(
        string $apiBaseUrl,
        string $apiKey,
        string $apiSecret
    ) {
        $this->apiBaseUrl = $this->__setApiBaseUrl($apiBaseUrl);
        $this->apiKey = trim($apiKey);
        $this->apiSecret = trim($apiSecret);
    }

    /**
     * __setApiBaseUrl
     *
     * @param string $apiBaseUrl
     * @return string
     */
    public function __setApiBaseUrl(string $apiBaseUrl): string
    {
        return rtrim(trim($apiBaseUrl), '/');
    }

    /**
     * __setEndPoint
     *
     * @param string $endPoint
     * @return string
     */
    public function __setEndPoint(string $endPoint): string
    {
        return ltrim(rtrim(trim($endPoint), '/'), '/');
    }

    /**
     * getApiBaseUrl
     *
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    /**
     * getApiKey
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * getApiSecret
     *
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * createToken
     *
     * @return void
     * 
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function createToken(): void
    {
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->post("{$this->apiBaseUrl}/" . self::API_PREFIX . "/create-token", [
            'key' => $this->apiKey,
            'password' => $this->apiSecret,
        ]);
        if ($curl->error) {
            switch ($curl->error_code) {
                case 401:
                    $curl->close();
                    throw new UnauthorizedException($curl->error_message, $curl->error_code);
                    break;

                case 404:
                    $curl->close();
                    throw new NotFoundException($curl->error_message, $curl->error_code);
                    break;

                case 500:
                    $curl->close();
                    throw new InternalServerErrorException($curl->error_message, $curl->error_code);
                    break;

                default:
                    $curl->close();
                    throw new Exception($curl->error_message, $curl->error_code);
                    break;
            }
        } else {
            $response = json_decode($curl->response);
            if (empty($response) || !isset($response->token)) {
                $curl->close();
                throw new Exception('create token error');
            } else {
                $this->token = $response->token;
            }
        }
        $curl->close();
    }

    /**
     * post
     *
     * @param string $endPoint
     * @param array $data
     * @return mixed
     * 
     * @throws ClientErrorException
     * @throws UnauthorizedException
     * @throws RoleErrorException
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function post(string $endPoint, array $data)
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$this->token}");
        $curl->post("{$this->apiBaseUrl}/" . self::API_PREFIX . "/{$endPoint}", $data);

        if ($curl->error) {
            switch ($curl->error_code) {
                case 401:
                    $curl->close();
                    throw new UnauthorizedException($curl->error_message, $curl->error_code);
                    break;

                case 403:
                    $curl->close();
                    throw new RoleErrorException($curl->error_message, $curl->error_code);
                    break;

                case 404:
                    $curl->close();
                    throw new NotFoundException($curl->error_message, $curl->error_code);
                    break;

                case 422:
                    $curl->close();
                    throw new UnprocessableEntityException($curl->error_message, $curl->error_code);
                    break;

                case 500:
                    $curl->close();
                    throw new InternalServerErrorException($curl->error_message, $curl->error_code);
                    break;

                default:
                    $curl->close();
                    throw new Exception($curl->error_message, $curl->error_code);
                    break;
            }
        } else {
            $response = json_decode($curl->response, true);
        }

        $curl->close();

        return $response;
    }

    /**
     * get
     *
     * @param string $endPoint
     * @param array $query
     * @return mixed
     * 
     * @throws ClientErrorException
     * @throws UnauthorizedException
     * @throws RoleErrorException
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function get(string $endPoint, array $query = [])
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$this->token}");
        $curl->get("{$this->apiBaseUrl}/" . self::API_PREFIX . "/{$endPoint}", $query);

        if ($curl->error) {
            switch ($curl->error_code) {
                case 401:
                    $curl->close();
                    throw new UnauthorizedException($curl->error_message, $curl->error_code);
                    break;

                case 403:
                    $curl->close();
                    throw new RoleErrorException($curl->error_message, $curl->error_code);
                    break;

                case 404:
                    $curl->close();
                    throw new NotFoundException($curl->error_message, $curl->error_code);
                    break;

                case 422:
                    $curl->close();
                    throw new UnprocessableEntityException($curl->error_message, $curl->error_code);
                    break;

                case 500:
                    $curl->close();
                    throw new InternalServerErrorException($curl->error_message, $curl->error_code);
                    break;

                default:
                    $curl->close();
                    throw new Exception($curl->error_message, $curl->error_code);
                    break;
            }
        } else {
            $response = json_decode($curl->response, true);
        }

        $curl->close();

        return $response;
    }
}
