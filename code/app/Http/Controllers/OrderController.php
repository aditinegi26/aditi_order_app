<?php

namespace App\Http\Controllers;

use App\Http\Services\CreateOrderService;
use App\Http\Services\GetOrderService;
use App\Http\Services\UpdateOrderService;
use App\Http\Services\OrderService;

use App\Http\Response\Response;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\OrderListRequest;
use App\Http\Requests\UpdateStatusRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller
{
  /**
     * @var \App\Http\Services\OrderService
     */
    protected $orderService;

     protected $response;

     /**
          * Get the list of orders and send 406 code in there is invalid request
          *
          * @param OrderListRequest $request
          *
          * @return JsonResponse
          */
   public function __construct(
     Response $response,OrderService $orderService)
   {
    $this->orderService = $orderService;
    $this->response     = $response;

   }

    public function index(OrderListRequest $request)
    {
      try {
        $page  = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 1);

        $ordersList = $this->orderService->getOrderList($page, $limit);
        if (empty($ordersList)) {
            return $this->response->reponseError(
                'NO_DATA_FOUND',
                JsonResponse::HTTP_NO_CONTENT
            );
        }

        $orders = array();
        foreach ($ordersList as $orderItem) {
            $orders[] = $this->response->formatOrderAsResponse($orderItem);
        }
        //success case
        return $this->response->setResponse($orders);
    } catch (\Exception $exception) {
        return $this->response->reponseError(
            $exception->getMessage(),
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    }

    /**
   * @param OrderCreate $request
   *
   * @return JsonResponse
   */
  public function create(CreateOrderRequest $request)
  {
      try {
          $orderCreationRes = $this->orderService->createOrder($request);
          if ($orderCreationRes) {
              $orderResponse = $this->response->formatOrderAsResponse($orderCreationRes);
              return $this->response->setResponse($orderResponse);
          } else {
              $messages  = $this->orderService->error;
              $errorCode = $this->orderService->errorCode;
              return $this->response->reponseError(
                  $messages,
                  $errorCode
              );
          }
      } catch (\Exception $e) {
          return $this->response->reponseError(
              $e->getMessage(),
              JsonResponse::HTTP_INTERNAL_SERVER_ERROR
          );
      }
  }

  /**
 * Order updation with valid order id
 * If order is taken send 409 code and 417 code with wrong order id
 *
 * @param UpdateStatusRequest $request
 * @param int $id
 *
 * @return JsonResponse
 */
  public function update(UpdateStatusRequest $request, $id)
  {
      try {
          //check if order is already assign
          $this->orderService->getId($id);

          if (false === $this->orderService->updateOrder($id)) {
              return $this->response->reponseError(
                  'order_taken',
                  JsonResponse::HTTP_CONFLICT
              );
          }

          return $this->response->responsePass(
              'success',
              JsonResponse::HTTP_OK
          );
      } catch (\Exception $e) {
          return $this->response->reponseError(
              'invalid_id',
              JsonResponse::HTTP_NOT_FOUND
          );
      }
  }



}
