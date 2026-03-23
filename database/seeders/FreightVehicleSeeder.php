<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreightVehicleSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => '6AtL2hz7Rj9YFJkYBVx2',
                'name' => 'Dost - Ashok Leyland',
                'image' => '',
                'description' => 'Goods Transport upto 1250 kg { 1.25 Ton }',
                'length' => '8',
                'width' => '5',
                'height' => '5.5',
                'weight' => '1500',
                'km_charge' => '40',
                'basic_fare_km' => '1',
                'basic_fare_charges' => '400',
                'holding_charge_minute' => '60',
                'holding_charges' => '150',
                'loading_unloading_charges' => '100',
                'enable' => true,
            ],
            [
                'id' => 'HoFTfy3dbh4ZgOE0KfhP',
                'name' => 'Pick up - 8 Ft',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2FvanCar_1701435644138.png?alt=media&token=f461f980-d6fd-48ae-a35f-2eeef2fac116',
                'description' => 'up to 1.3t - transports appliance,like a fridge, furniture or building material',
                'length' => '2.4',
                'width' => '1.4',
                'height' => '1.8',
                'weight' => '2000',
                'km_charge' => '35',
                'basic_fare_km' => '3',
                'basic_fare_charges' => '400',
                'holding_charge_minute' => '60',
                'holding_charges' => '150',
                'loading_unloading_charges' => '150',
                'enable' => true,
            ],
            [
                'id' => 'VSPfpRhcJ18eDRZFFPS7',
                'name' => 'Three Wheeler Tempo',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fcar_model_2_1722498680934.png?alt=media&token=d4cca61b-e6b5-4b27-85d2-7fb852dfaa76',
                'description' => 'Best for Sending\n1. FMCG Food Products\n2. Chemicals\n3. Fruits & Vegetable\n',
                'length' => '1.82',
                'width' => '1',
                'height' => '1.54',
                'weight' => '750',
                'km_charge' => '27',
                'basic_fare_km' => '1',
                'basic_fare_charges' => '220',
                'holding_charge_minute' => '30',
                'holding_charges' => '50',
                'loading_unloading_charges' => '50',
                'enable' => true,
            ],
            [
                'id' => 'YavYy5V0CXLP1cx05PO0',
                'name' => 'Tata Ace - 1 Ton',
                'image' => '',
                'description' => 'Goods Transport up to 1000 Kg { 1 Ton }',
                'length' => '8',
                'width' => '5',
                'height' => '5.5',
                'weight' => '25',
                'km_charge' => '40',
                'basic_fare_km' => '1',
                'basic_fare_charges' => '400',
                'holding_charge_minute' => '60',
                'holding_charges' => '150',
                'loading_unloading_charges' => '100',
                'enable' => true,
            ],
            [
                'id' => 'qlNsou88TCbFDfNnTrlQ',
                'name' => 'Tata Ace - Chota Hatti',
                'image' => '',
                'description' => 'loading any Goods',
                'length' => '1',
                'width' => '2.13',
                'height' => '1.82',
                'weight' => '44',
                'km_charge' => '30',
                'basic_fare_km' => '1',
                'basic_fare_charges' => '250',
                'holding_charge_minute' => '60',
                'holding_charges' => '100',
                'loading_unloading_charges' => '100',
                'enable' => true,
            ],
            [
                'id' => 'rrZthrWITei0vnAvj91p',
                'name' => 'Two - Wheelar',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/images%2Fth_1732280040105.jpg?alt=media&token=979d3b69-0e7e-48e2-964d-bb6678386bef',
                'description' => 'Base fare is inclusive of 1.0 km distance & 20 minutes of order time. Pricing may vary basis locality. Please note, road tax, parking fee, etc, will be applicable over and above ride fare.\nBe it a pen or product samples, send any consignment up to 20 kg anywhere within the city',
                'length' => '0.4',
                'width' => '0.4',
                'height' => '0.4',
                'weight' => '5',
                'km_charge' => '15',
                'basic_fare_km' => '5',
                'basic_fare_charges' => '100',
                'holding_charge_minute' => '0',
                'holding_charges' => '0',
                'loading_unloading_charges' => '0',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('freight_vehicles')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
