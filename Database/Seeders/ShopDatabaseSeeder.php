<?php

namespace Modules\Shop\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ShopDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(BankTransferPaymentSeeder::class);
        $this->call(CashOnDeliveryPaymentSeeder::class);
        $this->call(MyPosPaymentSeeder::class);
        $this->call(OwnDeliverySeeder::class);
        $this->call(ShopProductDatabaseSeeder::class);
        $this->call(ShopLawPagesSeeder::class);
        $this->call(ShopCountriesSeeder::class);
        $this->call(ShopStatesSeeder::class);
        $this->call(ShopCitiesSeeder::class);
    }
}
