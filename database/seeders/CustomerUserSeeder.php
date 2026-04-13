<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'user_name' => 'customer1',
                'password' => 'Password123',
                'name' => 'Ahmad Faizal',
                'email' => 'ahmad.faizal@example.com',
                'phone' => '0123456789',
                'alternate_phone' => '0198765432',
                'whats_app' => '0123456789',
                'billing_address' => '123 Jalan Bukit Bintang, Kuala Lumpur',
                'description' => 'Regular customer from Kuala Lumpur',
                'country' => 'malaysia',
                'state_id' => 12, // Selangor
                'city_id' => 56,  // Shah Alam
                'gst' => '1234567890',
                'tax_id' => 'TAX001',
                'company_ids' => [1, 2],
            ],
            [
                'user_name' => 'customer2',
                'password' => 'Password123',
                'name' => 'Lim Wei Jie',
                'email' => 'lim.weijie@example.com',
                'phone' => '0139876543',
                'alternate_phone' => null,
                'whats_app' => '0139876543',
                'billing_address' => '45 Jalan Sultan, Penang',
                'description' => 'Penang based customer',
                'country' => 'malaysia',
                'state_id' => 9, // Penang
                'city_id' => 40, // George Town
                'gst' => '9876543210',
                'tax_id' => 'TAX002',
                'company_ids' => [1],
            ],
            [
                'user_name' => 'customer3',
                'password' => 'Password123',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'phone' => '0141234567',
                'alternate_phone' => '0147654321',
                'whats_app' => null,
                'billing_address' => '88 Jalan Kota Bharu, Kelantan',
                'description' => 'Kelantan customer',
                'country' => 'malaysia',
                'state_id' => 3, // Kelantan
                'city_id' => 13, // Kota Bharu
                'gst' => '1122334455',
                'tax_id' => 'TAX003',
                'company_ids' => [2, 3],
            ],
            [
                'user_name' => 'customer4',
                'password' => 'Password123',
                'name' => 'Rashid Abdullah',
                'email' => 'rashid.abdullah@example.com',
                'phone' => '0162345678',
                'alternate_phone' => null,
                'whats_app' => '0162345678',
                'billing_address' => '12 Jalan Kuantan, Pahang',
                'description' => 'Pahang customer',
                'country' => 'malaysia',
                'state_id' => 6, // Pahang
                'city_id' => 26, // Kuantan
                'gst' => '5566778899',
                'tax_id' => 'TAX004',
                'company_ids' => [1, 3],
            ],
            [
                'user_name' => 'customer5',
                'password' => 'Password123',
                'name' => 'Tan Mei Ling',
                'email' => 'tan.meiling@example.com',
                'phone' => '0179876543',
                'alternate_phone' => '0171234567',
                'whats_app' => '0179876543',
                'billing_address' => '99 Jalan Kuching, Sarawak',
                'description' => 'Sarawak customer',
                'country' => 'malaysia',
                'state_id' => 11, // Sarawak
                'city_id' => 50,  // Kuching
                'gst' => '9988776655',
                'tax_id' => 'TAX005',
                'company_ids' => [2],
            ],
        ];

        foreach ($customers as $cust) {
            $user = User::create([
                'user_name' => $cust['user_name'],
                'password' => $cust['password'],
                'name' => $cust['name'],
                'email' => $cust['email'],
                'phone' => $cust['phone'],
                'alternate_phone' => $cust['alternate_phone'],
                'whats_app' => $cust['whats_app'],
                'billing_address' => $cust['billing_address'],
                'description' => $cust['description'],
                'country' => $cust['country'],
                'state_id' => $cust['state_id'],
                'city_id' => $cust['city_id'],
                'gst' => $cust['gst'],
                'tax_id' => $cust['tax_id'],
            ]);

            // Assign companies
            if (!empty($cust['company_ids'])) {
                $user->companies()->sync($cust['company_ids']);
            }

            // Assign role
            $user->syncRoles(['Customer']);
        }
    }
}
