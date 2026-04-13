<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'WO' => [
                'wo.view',
                'wo.edit',
                'wo.delete',
            ],

            'QT' => [
                'qt.view',
                'qt.edit',
                'qt.delete',
            ],

            'INV' => [
                'inv.view',
                'inv.edit',
                'inv.delete',
            ],

            'OR' => [
                'or.view',
                'or.edit',
                'or.delete',
            ],

            'CN' => [
                'cn.view',
                'cn.edit',
                'cn.delete',
            ],

            'DOCUMENT' => [
                'document.view',
                'document.edit',
                'document.delete',
            ],

            'MESSAGE' => [
                'message.view',
                'message.edit',
                'message.delete',
            ],

            'NOTES' => [
                'notes.view',
                'notes.edit',
                'notes.delete',
            ],

            'ANNOUNCEMENT' => [
                'announcement.view',
                'announcement.edit',
                'announcement.delete',
            ],

            'WO REPORT' => [
                'wo-report.view'
            ],

            'INVOICE REPORT' => [
                'invoice-report.view'
            ],

            'OR REPORT' => [
                'or-report.view'
            ],

            'CR REPORT' => [
                'cr-report.view'
            ],

            'PLANNER COMMISSION REPORT' => [
                'planner-commission-report.view'
            ],

            'PRODUCTION STAFF COMMISSION REPORT' => [
                'production-staff-commission-report.view'
            ],

            'TG REPORT' => [
                'tg-report.view'
            ],

            'CONSOLIDATION WO REPORT' => [
                'consolidation-wo-report.view'
            ],

            'MONTHLY REPORT' => [
                'monthly-report.view'
            ],
        ];

        $allPermissions = collect();

        // Create missing permissions
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                $permission = Permission::firstOrCreate(
                    ['name' => $perm, 'guard_name' => 'web'],
                    ['group_name' => $group]
                );

                $allPermissions->push($permission);
            }
        }

        // Create Super Admin role
        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web']
        );

        // Assign ALL permissions to Super Admin
        $superAdmin->syncPermissions($allPermissions);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $managementStaff = Role::firstOrCreate(['name' => 'Management Staff']);
        $productionStaff = Role::firstOrCreate(['name' => 'Production Staff']);
        $planner = Role::firstOrCreate(['name' => 'Planner']);
        $customer = Role::firstOrCreate(['name' => 'Customer']);

        $admin->syncPermissions([
            'wo.view',
            'wo.edit',
            'qt.view',
            'qt.edit',
            'inv.view',
            'inv.edit',
            'or.view',
            'or.edit',
            'cn.view',
            'cn.edit',
            'document.view',
            'document.edit',
            'message.view',
            'message.edit',
            'notes.view',
            'notes.edit',
            'announcement.view',
            'announcement.edit',
            'wo-report.view',
            'invoice-report.view',
            'or-report.view',
            'cr-report.view',
            'planner-commission-report.view',
            'production-staff-commission-report.view',
            'tg-report.view',
            'consolidation-wo-report.view',
            'monthly-report.view'
        ]);


        $managementStaff->syncPermissions([
            'wo.view',
            'wo.edit',
            'qt.view',
            'qt.edit',
            'inv.view',
            'inv.edit',
            'or.view',
            'or.edit',
            'cn.view',
            'cn.edit',
            'document.view',
            'document.edit',
            'message.view',
            'message.edit',
            'notes.view',
            'notes.edit',
            'announcement.view',
            'announcement.edit',
            'wo-report.view',
            'invoice-report.view',
            'or-report.view',
            'cr-report.view',
            'planner-commission-report.view',
            'production-staff-commission-report.view',
            'tg-report.view',
            'consolidation-wo-report.view',
            'monthly-report.view'
        ]);

        // Production Staff → Only Work Related
        $planner->syncPermissions([
            'wo.view',
            'qt.view',
            'inv.view',
            'or.view',
            'cn.view',
            'document.view',
            'document.edit',
            'message.view',
            'message.edit',
            'notes.view',
            'notes.edit',
            'announcement.view',
            'wo-report.view',
            'invoice-report.view',
            'or-report.view',
            'cr-report.view',
            'planner-commission-report.view',
            'tg-report.view',
        ]);

        $productionStaff->syncPermissions([
            'wo.view',
            'qt.view',
            'inv.view',
            'or.view',
            'cn.view',
            'document.view',
            'document.edit',
            'message.view',
            'message.edit',
            'notes.view',
            'notes.edit',
            'announcement.view',
            'wo-report.view',
            'invoice-report.view',
            'or-report.view',
            'cr-report.view',
            'production-staff-commission-report.view',
            'tg-report.view',
        ]);


        $customer->syncPermissions([
            'wo.view',
            'qt.view',
            'inv.view',
            'or.view',
            'cn.view',
            'document.view',
            'message.view',
            'notes.view',
            'announcement.view',
            'monthly-report.view'
        ]);

        // Clear cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
