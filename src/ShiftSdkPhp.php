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

    const EXTERNAL_API_PREFIX = 'ex-app';

    const CUSTOMER_API_PREFIX = 'customer-api';

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
        $curl->post("{$this->apiBaseUrl}/" . self::EXTERNAL_API_PREFIX . "/create-token", [
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
     * __postApi
     *
     * @param string $endPoint
     * @param array $data
     * @param string $prefix
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
    private function __postApi(string $endPoint, array $data, string $prefix)
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$this->token}");
        $curl->post("{$this->apiBaseUrl}/" . $prefix . "/{$endPoint}", $data);

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
     * postExternalApp
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
    public function postExternalApp(string $endPoint, array $data)
    {
        try {
            $response = $this->__postApi($endPoint, $data, self::EXTERNAL_API_PREFIX);
        } catch (UnauthorizedException $th) {
            throw $th;
        } catch (RoleErrorException $th) {
            throw $th;
        } catch (NotFoundException $th) {
            throw $th;
        } catch (UnprocessableEntityException $th) {
            throw $th;
        } catch (InternalServerErrorException $th) {
            throw $th;
        } catch (Exception $th) {
            throw $th;
        }
        return $response;
    }

    /**
     * postCustomerApi
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
    public function postCustomerApi(string $endPoint, array $data)
    {
        try {
            $response = $this->__postApi($endPoint, $data, self::CUSTOMER_API_PREFIX);
        } catch (UnauthorizedException $th) {
            throw $th;
        } catch (RoleErrorException $th) {
            throw $th;
        } catch (NotFoundException $th) {
            throw $th;
        } catch (UnprocessableEntityException $th) {
            throw $th;
        } catch (InternalServerErrorException $th) {
            throw $th;
        } catch (Exception $th) {
            throw $th;
        }
        return $response;
    }

    /**
     * __getApi
     *
     * @param string $endPoint
     * @param array $query
     * @param string $prefix
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
    private function __getApi(string $endPoint, array $query = [], string $prefix)
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$this->token}");
        $curl->get("{$this->apiBaseUrl}/" . $prefix . "/{$endPoint}", $query);

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
     * getExternalApp
     *
     * @param string $endPoint
     * @param array $query
     * @return mixed
     * 
     * @throws ClientErrorException
     * @throws UnauthorizedException
     * @throws RoleErrorException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function getExternalApp(string $endPoint, array $query)
    {
        try {
            $response = $this->__getApi($endPoint, $query, self::EXTERNAL_API_PREFIX);
        } catch (UnauthorizedException $th) {
            throw $th;
        } catch (RoleErrorException $th) {
            throw $th;
        } catch (NotFoundException $th) {
            throw $th;
        } catch (UnprocessableEntityException $th) {
            throw $th;
        } catch (InternalServerErrorException $th) {
            throw $th;
        } catch (Exception $th) {
            throw $th;
        }
        return $response;
    }

    /**
     * getCustomerApi
     *
     * @param string $endPoint
     * @param array $query
     * @return mixed
     * 
     * @throws ClientErrorException
     * @throws UnauthorizedException
     * @throws RoleErrorException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function getCustomerApi(string $endPoint, array $query)
    {
        try {
            $response = $this->__getApi($endPoint, $query, self::CUSTOMER_API_PREFIX);
        } catch (UnauthorizedException $th) {
            throw $th;
        } catch (RoleErrorException $th) {
            throw $th;
        } catch (NotFoundException $th) {
            throw $th;
        } catch (UnprocessableEntityException $th) {
            throw $th;
        } catch (InternalServerErrorException $th) {
            throw $th;
        } catch (Exception $th) {
            throw $th;
        }
        return $response;
    }
}
