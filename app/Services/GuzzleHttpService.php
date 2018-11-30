<?php

namespace Wizdraw\Services;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Log;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpService extends AbstractService
{
    const WIZDRAW_CARD_SEND_SMS_ENDPOINT = '/cards/sendSmsVerificationForLoadUnload';

    const WIZDRAW_CARD_UNLOAD_MONEY_ENDPOINT = '/cards/unloadCardTransaction';

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * GuzzleHttpService constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function sendVerificationSMS(array $params): bool
    {
        $requestSent = false;
        try {
            $response = $this->sendRequest(
                'POST',
                config('prepaid.apiEndpoint') . self::WIZDRAW_CARD_SEND_SMS_ENDPOINT,
                $params
            );
            $decodedResponse = json_decode((string)$response->getBody(), true);
            if (isset($decodedResponse['errorCode']) && $decodedResponse['errorCode'] === 0) {
                $requestSent = true;
            }
        } catch (GuzzleException $exception) {
            $this->writeLog('Error from Pre paid server', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
        return $requestSent;
    }

    /**
     * @param array $params
     * @return array
     */
    public function verifySendAmount(array $params): array
    {
        $requestSent = ['sent' => false];
        try {
            $response = $this->sendRequest(
                'POST',
                config('prepaid.apiEndpoint') . self::WIZDRAW_CARD_UNLOAD_MONEY_ENDPOINT,
                $params
            );
            $decodedResponse = json_decode((string)$response->getBody(), true);
            if (isset($decodedResponse['errorCode']) && $decodedResponse['errorCode'] === 0) {
                $requestSent['sent'] = true;
            } else {
                $requestSent['message'] = $decodedResponse['errorDesc'];
            }
        } catch (GuzzleException $exception) {
            $this->writeLog('Error from Pre paid server', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
        return $requestSent;
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    private function writeLog(string $message = '', array $context = []): void
    {
        Log::error($message, $context);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $formParams
     * @return ResponseInterface
     */
    private function sendRequest(string $method = 'GET', string $url, array $formParams = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, ['form_params' => $formParams]);
    }
}