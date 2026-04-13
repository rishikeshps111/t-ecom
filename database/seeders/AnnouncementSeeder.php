<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Public Announcements
        |--------------------------------------------------------------------------
        */
        Announcement::create([
            'subject'  => 'System Maintenance',
            'message'  => 'The system will be under maintenance tonight from 11 PM to 2 AM.',
            'type'     => 'public',
            'priority' => 'high',
        ]);

        Announcement::create([
            'subject'  => 'New Feature Update',
            'message'  => 'We have added new reporting features to the dashboard.',
            'type'     => 'public',
            'priority' => 'medium',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Private Announcement
        |--------------------------------------------------------------------------
        */
        $privateAnnouncement = Announcement::create([
            'subject'  => 'Contract Renewal Reminder',
            'message'  => 'Your contract is due for renewal this month. Please contact support.',
            'type'     => 'private',
            'priority' => 'low',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attach Companies to Private Announcement
        |--------------------------------------------------------------------------
        */
        // Example: attach to first 2 companies
        $companyIds = User::role('Customer')->pluck('id')->take(2)->toArray();

        $privateAnnouncement->users()->sync($companyIds);
    }
}
