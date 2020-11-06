<?php


namespace App\Helper;


use Symfony\Component\HttpFoundation\JsonResponse;

class Utils
{
    /**
     * Create a jsonResponse with the parameters and send
     * @param $success
     * @param $code
     * @param $data
     *
     * @return JsonResponse
     */
    public function setDataResponse($code, $data, $success = true)
    {
        $response = new JsonResponse();
        $contentType = $response->headers->get('Content-Type');

        $response->setStatusCode($code);

        $response->setData([
            'success'      => $success,
            'code'         => $response->getStatusCode(),
            'content-type' => $contentType,
            'data'         => $data
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    /**
     * Return a code and message pass in parameters
     * Renvoi un code et un message passer en parametre
     * @param $code
     * @param $message
     *
     * @return array
     */
    public function setMessage($code, $message)
    {
        $data = array(
            "code"    => $code,
            "message" => $message
        );

        return $data;
    }
}