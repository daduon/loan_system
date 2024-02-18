<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Borrow_Master;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Factory;


class BorrowerMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Borrow_Master::truncate();

        $faker = \Faker\Factory::create();

        for ($i =0 ; $i<10;$i++) {
            Borrow_Master::create([
                'customer_id' =>1,
                'borrowingTypeCode'=>'01',
                'currencyCode'=>'02',
                'loanAmount'=>1000,
                'loanBalance'=>1000,
                'maturityDate' => $faker->date,
                'applyInterestRate' => now(),
                'repayTaxAmount' => 0,
                'repayInterest' => 1,
                'borrowing_create_date'=> now(),
                'borrowing_create_by' => 'admin',
                'borrowing_modify_date'=> now(),
                'borrowing_modify_by' => 'admin',
            ]);
        }

    }
}
