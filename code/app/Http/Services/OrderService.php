<?php

namespace App\Http\Services;

use App\http\Models\Order; //Model
use App\http\Models\Distance; //Model
use App\Validators\MapCoordinates;
use Illuminate\Http\JsonResponse;
use App\Helpers\MapsHelper;
use App\http\Requests\CreateOrderRequest;

class OrderService
{
    /**
     * @var null|string
     */
    public $error = null;

    /**
     * @var int
     */
    public $errorCode;

    /**
     * @var MapCoordinates
     */
    protected $MapCoordinates;

    /**
     * @var DistanceHelper
     */
    protected $mapHelper;

    /**
     * @param MapCoordinates $MapCoordinates
     * @param DistanceHelper    $mapHelper
     */
    public function __construct(MapCoordinates $MapCoordinates,MapsHelper $mapHelper)
    {
        $this->MapCoordinates = $MapCoordinates;
        $this->distanceHelper = $mapHelper;
    }

    /**
     * Create a order based on geo location provided in requestData param
     *
     * @param OrderCreateRequest $request
     *
     * @return Order|false
     */
    public function createOrder($request)
    {
        $sourceLat       = $request->origin[0];
        $sourceLong      = $request->origin[1];
        $destinationLat  = $request->destination[0];
        $destinationLong = $request->destination[1];

        //Validating source and dest lat long
        $validLatLong = $this->MapCoordinates->validate($sourceLat,$sourceLong,$destinationLat,$destinationLong);

        if (!$validLatLong) {
            $this->error     = $this->MapCoordinates->getError();
            $this->errorCode = JsonResponse::HTTP_NOT_ACCEPTABLE;
            return false;
        }

        $distance = $this->takeDistanceValue($sourceLat,$sourceLong,$destinationLat,$destinationLong);

        if (!$distance instanceof \App\Http\Models\Distance) {
            $this->error     = $distance;
            $this->errorCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            return false;
        }

        //Create new record
        $order                 = new Order();
        $order->status         = Order::UNASSIGNED_ORDER_STATUS;
        $order->distance_id    = $distance->id;
        $order->distance       = $distance->distance;
        $order->save();

        return $order;
    }

    /**
     * @param float $sourceLat
     * @param float $sourceLong
     * @param float $destinationLat
     * @param float $destinationLong
     *
     * @return int
     */
    public function takeDistanceValue($sourceLat,$sourceLong,$destinationLat,$destinationLong)
     {
        $distanceObj = new Distance;

        //check if distance is already available for given coordinates
        $distanceExist = $distanceObj->getAvailableDistance($sourceLat,$sourceLong,$destinationLat,$destinationLong);

        if (!empty($distanceExist)) {
            return $distanceExist;
        }

        //Create new record for distance if distance is not available
        $source          = $sourceLat.",".$sourceLong;
        $destination     = $destinationLat.",".$destinationLong;


        $distanceBetween = $this->distanceHelper->getDistanceFromMapHelper($source,$destination);

        if (!is_int($distanceBetween)) {
            return $distanceBetween;
        }

        return $distanceObj->saveDistance(
            $sourceLat,
            $sourceLong,
            $destinationLat,
            $destinationLong,
            $distanceBetween
        );
    }

    /**
     * Fetches list of order in system using given limit and page variable
     *
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getOrderList($page, $limit)
    {
        $page   = (int) $page;
        $limit  = (int) $limit;
        $orders = [];

        if ($page > 0 && $limit > 0) {
            $offset   = ($page - 1) * $limit;
            $orders = Order::take($limit)->skip($offset)->get();
        }
        if (count($orders) == 0) {
          $this->error     = "NO_DATA_FOUND";
          $this->errorCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
          return false;
       }

        return $orders;
    }

    /**
     * Fetches Order model based on primary key provided
     *
     * @param int $id
     *
     * @return Order
     */
    public function getId($id)
    {
        $order = new Order();
        return $order->getOrderById($id);
    }

    /**
     * Mark an order as TAKEN, if not already
     *
     * @param int $orderId
     *
     * @return bool
     */
    public function updateOrder($orderId)
    {
        $order = new Order();
        return $order->updateOrderStatus($orderId);
    }
}
