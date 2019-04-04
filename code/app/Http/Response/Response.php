<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;
use App\Http\Models\Order;
use App\Helpers\MessageHelper;

class Response
{
    /**
     * @var \App\Helpers\MessageHelper
     */
    protected $responseMsgHelper;

    /**
     * @param \App\Helpers\MessageHelper $responseMsgHelper
     */
    public function __construct(MessageHelper $responseMsgHelper)
    {
        $this->responseMsgHelper = $responseMsgHelper;
    }

    /**
     * @param string  $message
     * @param int  $responseCode
     * @param boolean $translateMessage
     *
     * @return JsonResponse
     */
    public function reponseError(
        $message,
        $responseCode = JsonResponse::HTTP_BAD_REQUEST,
        $translateMessage = true
    ) {

        if (true === $translateMessage) {
            $message = $this->responseMsgHelper->getMessage($message) ?: $message;
        }

        $response = ['error' => $message];

        return response()->json($response, $responseCode);
    }

    /**
     * @param string  $message
     * @param int  $responseCode
     * @param boolean $translateMessage
     *
     * @return JsonResponse
     */
    public function responsePass($message, $responseCode = JsonResponse::HTTP_OK, $translateMessage = true)
    {
        if (true === $translateMessage) {
            $message = $this->responseMsgHelper->getMessage($message) ?: $message;
        }

        $response = ['status' => $message];

        return response()->json($response, $responseCode);
    }

    /**
     * @param $response
     *
     * @return JsonResponse
     */
    public function setResponse($response)
    {
        return response()->json($response, JsonResponse::HTTP_OK);
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function formatOrderAsResponse(Order $order)
    {
        return [
            'id' => $order->id,
            'distance' => $order->getDistanceValue(),
            'status' => $order->status
        ];
    }
}
