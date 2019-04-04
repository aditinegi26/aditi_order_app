<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrderTest extends TestCase
{

//Testing the GET(List Order) request

      public function testOrderList()
      {
        $page  = 1;
        $limit = 10;
          $response = $this->json('GET', '/orders?page='.$page.'&limit='.$limit.'');
          $response->assertStatus(200);
      }
      public function testOrdersWithInvalidParameters()
      {
        $page  = "xyz123";
        $limit = 0;
           $response = $this->json('GET', '/orders?page='.$page.'&limit='.$limit.'');
           $response->assertStatus(406);
      }
      public function testOrdersWithDataNotFound()
      {
        $page  = 9999999999;
        $limit = 50;
            $response = $this->json('GET', '/orders?page='.$page.'&limit='.$limit.'');
            $response->assertStatus(204);
      }


//Testing the POST(Create Order) request


          public function testOrderCreate()
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
           $response->assertStatus(200);
       }
       public function testCreateWithBlankData()
    {
        $response = $this->json('POST', '/orders', [
            "origin" => [
                "",
                ""
            ],
            "destination" => [
                "",
                ""
            ]
        ]);
        $response->assertStatus(406);
    }
        public function testCreateWithBlankOrigin()
     {
         $response = $this->json('POST', '/orders', [
             "origin" => [
                 "",
                 ""
             ],
             "destination" => [
               "28.535517",
               "77.391029"
             ]
         ]);
         $response->assertStatus(406);
     }

     public function testCreateWithBlankDestination()
    {
      $response = $this->json('POST', '/orders', [
        "origin" => [
            "28.704060",
            "77.102493"
        ],
        "destination" => [
            "",
            ""
        ]
      ]);
      $response->assertStatus(406);
    }


    //Testing the Patch(Update Order) request


    public function testOrderUpdate()
   {
       $response = $this->json('PATCH', '/orders/5',
       ['status' => 'TAKEN']
      );

       $response->assertStatus(200);
   }

   public function testOrderUpdateWithAlreadyTaken()
  {
      $response = $this->json('PATCH', '/orders/5', [
          'status' => 'TAKEN'
      ]);
      $response->assertStatus(409);
  }

  public function testOrderUpdateWithBlankArray()
 {
     $response = $this->json('PATCH', '/orders/10', [
             []
     ]);
     $response->assertStatus(406);
 }


}
