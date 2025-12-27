<?php

namespace Database\Seeders\Batch8;

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
            'title' => 'Master Data',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-server',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Companies',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-office',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/manage_company',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Merchants',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-price-tag',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/merchant',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Merchant Branches',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-price-tags',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/merchant_branch',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Beneficiary',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-user-tie',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/manage_customer',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Partners',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-user-check',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/partners',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Partner Fees',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-credit-card',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/partner_fees',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Events',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-users',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/events',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Data Verification',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-align-top',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/data_verification',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        $menu2 = SidebarMenuItem::updateOrCreate([
            'title' => 'QRIS Menu',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-qrcode',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'EDCs',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-file-text2',
            'parent_id' => $menu2->id,
            'route' => '/yukk_co/list_edcs',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Manage QRIS Settings',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-equalizer',
            'parent_id' => $menu2->id,
            'route' => '/yukk_co/qris-settings/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'QRIS (PTEN)',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-file-text',
            'parent_id' => $menu2->id,
            'route' => '/yukk_co/qris_pten_menu',
            'route_type' => 'FULL_URL',
            'access_control' => ["MERCHANT_ACTIVATION"],
            'access_control_type' => 'AND',
        ]);

        $menu3 = SidebarMenuItem::updateOrCreate([
            'title' => 'YUKK Merchant',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-price-tag',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["DATA_VERIFICATION.VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Manage Account Login',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-users',
            'parent_id' => $menu3->id,
            'route' => '/yukk_co/manage_partner_login',
            'route_type' => 'FULL_URL',
            'access_control' => ["DATA_VERIFICATION.VIEW"],
            'access_control_type' => 'AND',
        ]);
    }
}
