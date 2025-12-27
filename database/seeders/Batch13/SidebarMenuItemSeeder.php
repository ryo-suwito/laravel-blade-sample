<?php

namespace Database\Seeders\Batch13;

use App\Models\SidebarMenuItem;
use Illuminate\Database\Seeder;

class SidebarMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = SidebarMenuItem::updateOrCreate([
            'title' => 'Approval',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-checkbox-checked',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => [
                "MANAGE_APPROVAL.COMPANIES",
                "MANAGE_APPROVAL.MERCHANTS",
                "MANAGE_APPROVAL.MERCHANT_BRANCHES",
                "MANAGE_APPROVAL.BENEFICIARIES",
                "MANAGE_APPROVAL.PARTNERS",
                "MANAGE_APPROVAL.PARTNER_FEES",
                "MANAGE_APPROVAL.EVENTS",
            ],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Beneficiaries',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/beneficiaries',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-user-tie',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.BENEFICIARIES"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Companies',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/companies',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-office',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.COMPANIES"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Merchants',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/merchants',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-price-tag',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.MERCHANTS"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Merchant Branches',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/merchant_branches',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-price-tags',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.MERCHANT_BRANCHES"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Partners',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/partners',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-user-check',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.PARTNERS"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Partner Fees',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/partner_fees',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-credit-card',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.PARTNER_FEES"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Events',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/approvals/events',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-users',
            'route_type' => 'FULL_URL',
            'access_control' => ["MANAGE_APPROVAL.EVENTS"],
            'access_control_type' => 'AND',
        ]);
    }
}
