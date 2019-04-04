<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Order;

class DistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            ["start_lat" => "28.704060", "start_long" => "77.102493", "end_lat" => "28.535517", "end_long" => "77.391029", "distance" => 46732
            ],
            ["start_lat" => "28.704060", "start_long" => "77.102493", "end_lat" => "28.535517", "end_long" => "77.391044", "distance" => 912242
            ],
            ["start_lat" => "28.704060", "start_long" => "77.102493", "end_lat" => "28.535517", "end_long" => "77.391028", "distance" => 46731
            ]
        ];
        foreach ($locations as $disData) {
            DB::table('distance')->insert([
                'start_lat' => $disData['start_lat'],
                'start_long' => $disData['start_long'],
                'end_lat' => $disData['end_lat'],
                'end_long' => $disData['end_long'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'distance' => $disData['distance']
            ]);
        }

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $lat1 = $faker->latitude();
            $lat2 = $faker->latitude();
            $lon1 = $faker->longitude();
            $lon2 = $faker->longitude();
            $distance = $this->getDistance($lat1, $lon1, $lat2, $lon2);

            DB::table('distance')->insert([
                'start_lat' => $lat1,
                'start_long' => $lon1,
                'end_lat' => $lat2,
                'end_long' => $lon2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'distance' => $distance
            ]);
        }

        $distances = DB::table('distance')->orderBy('id')->each(function ($response) {
            for ($i=0; $i < 5 ; $i++) {
                DB::table('orders')->insert([
                    'distance_id'    => $response->id,
                    'distance' => $response->distance,
                    'status'         => $i % 2 == 0 ? Order::UNASSIGNED_ORDER_STATUS : Order::ASSIGNED_ORDER_STATUS,
                    'created_at'     => date("Y-m-d H:i:s"),
                    'updated_at'     => date("Y-m-d H:i:s"),
                ]);
            }
        });
    }

    public function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $distanceInMetre = $dist * 60 * 1.1515 * 1.609344 * 1000;

        return $distanceInMetre;
    }
}
