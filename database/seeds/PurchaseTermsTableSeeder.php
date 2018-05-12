<?php

use Illuminate\Database\Seeder;

class PurchaseTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentTerms = \App\Model\PurchaseTermsType::firstOrCreate([
            'name' => 'Payment Terms',
        ], [
            'name' => 'Payment Terms',
            'order' => 1,
        ]);

        \App\Model\PurchaseTerm::insert([
            [
                'type_id' => $paymentTerms->id,
                'value' => 'Upon Delivery',
            ],
            [
                'type_id' => $paymentTerms->id,
                'value' => 'Upon Delivery and Installation',
            ],
            [
                'type_id' => $paymentTerms->id,
                'value' => 'Advance Payment',
            ],
        ]);

        $deliveryTerms = \App\Model\PurchaseTermsType::firstOrCreate([
            'name' => 'Delivery Terms',
        ], [
            'name' => 'Delivery Terms',
            'order' => 2,
        ]);

        \App\Model\PurchaseTerm::insert([
            [
                'type_id' => $deliveryTerms->id,
                'value' => 'Partial delivery allowed',
            ],
            [
                'type_id' => $deliveryTerms->id,
                'value' => 'Delivery includes installation and programming',
            ],
            [
                'type_id' => $deliveryTerms->id,
                'value' => 'Delivery includes training',
            ],
        ]);

        $penaltyTerms = \App\Model\PurchaseTermsType::firstOrCreate([
            'name' => 'Penalties',
        ], [
            'name' => 'Penalties',
            'order' => 2,
        ]);

        \App\Model\PurchaseTerm::insert([
            [
                'type_id' => $penaltyTerms->id,
                'value' => 'Any delay of delivery of more than one week from the date of delivery is calculated by deducting 10% of the value of the P.O.',
            ],
        ]);
    }
}
