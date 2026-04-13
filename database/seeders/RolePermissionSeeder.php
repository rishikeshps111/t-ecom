<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Example permissions (you can adjust later)
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


        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(
                    ['name' => $perm, 'guard_name' => 'web'],
                    ['group_name' => $group]
                );
            }
        }


        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $managementStaff = Role::firstOrCreate(['name' => 'Management Staff']);
        $productionStaff = Role::firstOrCreate(['name' => 'Production Staff']);
        $planner = Role::firstOrCreate(['name' => 'Planner']);
        $customer = Role::firstOrCreate(['name' => 'Customer']);



        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::all());
        $managementStaff->givePermissionTo([]);
        $productionStaff->givePermissionTo([]);
        $planner->givePermissionTo([]);
        $customer->givePermissionTo([]);
    }
}
