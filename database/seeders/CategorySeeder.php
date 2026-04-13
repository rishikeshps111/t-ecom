<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Automotive',
                'subcategories' => [
                    'Car Servicing',
                    'Motorcycle Servicing',
                    'Car Wash',
                    'Tyre & Battery',
                    'Auto Parts'
                ],
            ],
            [
                'name' => 'Health & Wellness',
                'subcategories' => [
                    'Clinic & Hospitals',
                    'Dental Services',
                    'Pharmacy',
                    'Fitness & Gym',
                    'Spa & Massage'
                ],
            ],
            [
                'name' => 'Education & Learning',
                'subcategories' => [
                    'Tuition & Private Classes',
                    'Kindergarten & Pre-school',
                    'Language Courses',
                    'Vocational Training',
                    'Online Courses'
                ],
            ],
            [
                'name' => 'Food & Beverage',
                'subcategories' => [
                    'Restaurants',
                    'Cafes',
                    'Food Delivery',
                    'Bakery & Confectionery',
                    'Catering Services'
                ],
            ],
            [
                'name' => 'IT & Technology',
                'subcategories' => [
                    'Computer Repair',
                    'Software Development',
                    'Web Design & Hosting',
                    'Mobile App Development',
                    'Networking & IT Support'
                ],
            ],
            [
                'name' => 'Home Services',
                'subcategories' => [
                    'Plumbing',
                    'Electrical Services',
                    'Cleaning Services',
                    'Pest Control',
                    'Interior Design'
                ],
            ],
            [
                'name' => 'Travel & Transport',
                'subcategories' => [
                    'Travel Agencies',
                    'Taxi & Ride-Hailing',
                    'Car Rental',
                    'Bus & Coach Services',
                    'Logistics & Courier'
                ],
            ],
            [
                'name' => 'Beauty & Personal Care',
                'subcategories' => [
                    'Hair Salons',
                    'Beauty Salons',
                    'Nail Salons',
                    'Skin Care',
                    'Makeup Services'
                ],
            ],
        ];

        foreach ($categories as $catIndex => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'code' => str_pad($catIndex + 1, 4, '0', STR_PAD_LEFT), 
            ]);

            foreach ($categoryData['subcategories'] as $subIndex => $subName) {
                SubCategory::create([
                    'name' => $subName,
                    'category_id' => $category->id,
                    'code' => str_pad(($catIndex + 1) * 100 + ($subIndex + 1), 4, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }
}
