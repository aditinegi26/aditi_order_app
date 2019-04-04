<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     //Testing the GET(List Order) request
     public function testOrderListFeature()
     {
       $page  = 1;
       $limit = 10;
         $response = $this->json('GET', '/orders?page='.$page.'&limit='.$limit.'');
         $data = (array) $response->getData();
         $response->assertStatus(200);
         $this->assertLessThan(4, count($data));

         //Response data
       foreach ($data as $order) {
           $order = (array) $order;
           $this->assertArrayHasKey('id', $order);
           $this->assertArrayHasKey('distance', $order);
           $this->assertArrayHasKey('status', $order);
       }

//Testing the POST(Create Order) request
     public function testOrderCreateFeature()
  {
      $response = $this->json('POST', '/orders', [
          "origin" => [
              "28.704060",
              "77.102493"
          ],
          "destination" => [
              "28.535517",
              "77.391029"
          ]
      ]);
      $data = (array) $response->getData();
      $response->assertStatus(200);

      $this->assertArrayHasKey('id', $data);
      $this->assertArrayHasKey('status', $data);
      $this->assertArrayHasKey('distance', $data);
  }

  //Testing the Patch(Update Order) request

  public function testOrderUpdateFeature()
 {
     $response = $this->json('PATCH', '/orders/5',
     ['status' => 'TAKEN']
    );
      $data = (array) $response->getData();
      $response->assertStatus(200);
      $this->assertArrayHasKey('status', $data);

 }
}
