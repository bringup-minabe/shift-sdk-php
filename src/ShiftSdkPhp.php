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
     * externalAppToken
     *
     * @var string
     */
    protected $externalAppToken;

    /**
     * customerApiToken
     *
     * @var string
     */
    protected $customerApiToken;

    /**
     * validateErrorMessages
     *
     * @var array
     */
    private $validateErrorMessages = [];

    /**
     * __construct
     *
     * @param string $apiBaseUrl
     */
    public function __construct(string $apiBaseUrl) {
        $this->apiBaseUrl = $this->__setApiBaseUrl($apiBaseUrl);
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
     * __setApiKey
     *
     * @param string $apiKey
     * @return string
     */
    public function __setApiKey(string $apiKey): string
    {
        return trim($apiKey);
    }

    /**
     * __setApiSecret
     *
     * @param string $apiKey
     * @return string
     */
    public function __setApiSecret(string $apiKey): string
    {
        return trim($apiKey);
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
     * getExternalAppToken
     *
     * @return string
     */
    public function getExternalAppToken(): string
    {
        return $this->externalAppToken;
    }

    /**
     * getCustomerApiToken
     *
     * @return string
     */
    public function getCustomerApiToken(): string
    {
        return $this->customerApiToken;
    }

    /**
     * createExternalAppToken
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @return void
     * 
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function createExternalAppToken(string $apiKey, string $apiSecret): void
    {
        $apiKey = $this->__setApiKey($apiKey);
        $apiSecret = $this->__setApiSecret($apiSecret);
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->post("{$this->apiBaseUrl}/" . self::EXTERNAL_API_PREFIX . "/create-token", [
            'key' => $apiKey,
            'password' => $apiSecret,
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
                $this->externalAppToken = $response->token;
            }
        }
        $curl->close();
    }

    /**
     * createCustomerApiToken
     *
     * @param string $username
     * @param string $password
     * @return void
     * 
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     * @throws Exception
     */
    public function createCustomerApiToken(string $username, string $password): void
    {
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->post("{$this->apiBaseUrl}/" . self::CUSTOMER_API_PREFIX . "/login", [
            'username' => $username,
            'password' => $password,
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
                $this->customerApiToken = $response->token;
            }
        }
        $curl->close();
    }

    /**
     * getValidateErrorMessages
     *
     * @return array
     */
    public function getValidateErrorMessages():array
    {
        return $this->validateErrorMessages;
    }

    /**
     * __postApi
     *
     * @param string $endPoint
     * @param array $data
     * @param string $prefix
     * @param string $token
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
    private function __postApi(string $endPoint, array $data, string $prefix, string $token)
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$token}");
        $curl->post("{$this->apiBaseUrl}/" . $prefix . "/{$endPoint}", $data);
        $response = json_decode($curl->response, true);

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
                    // set validate error messages
                    if (is_array($response) && isset($response['errors'])) {
                        $this->validateErrorMessages = $response;
                    }
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
            $response = $this->__postApi(
                $endPoint,
                $data,
                self::EXTERNAL_API_PREFIX,
                $this->externalAppToken
            );
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
            $response = $this->__postApi(
                $endPoint,
                $data,
                self::CUSTOMER_API_PREFIX,
                $this->customerApiToken
            );
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
     * @param string $token
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
    private function __getApi(string $endPoint, array $query = [], string $prefix, string $token)
    {
        if ($endPoint === '') {
            throw new ClientErrorException('end point empty', 9001);
        }

        $response = [];

        $endPoint = $this->__setEndPoint($endPoint);
        
        $curl = new \Curl\Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', "Bearer {$token}");
        $curl->get("{$this->apiBaseUrl}/" . $prefix . "/{$endPoint}", $query);
        $response = json_decode($curl->response, true);

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
    public function getExternalApp(string $endPoint, array $query = [])
    {
        try {
            $response = $this->__getApi(
                $endPoint,
                $query,
                self::EXTERNAL_API_PREFIX,
                $this->externalAppToken
            );
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
    public function getCustomerApi(string $endPoint, array $query = [])
    {
        try {
            $response = $this->__getApi(
                $endPoint,
                $query,
                self::CUSTOMER_API_PREFIX,
                $this->customerApiToken
            );
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
