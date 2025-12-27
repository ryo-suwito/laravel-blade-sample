<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidebarMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidebar_menu_items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 191)->nullable()->comment("Title or Label to be shown");
            $table->string('type', 191)->default(\App\Models\SidebarMenuItem::TYPE_MENU)->nullable()->comment("Type MENU | SUBMENU");

            $table->string('target_type', 191)->nullable()->comment("Target Type YUKK_CO | COMPANY | MERCHANT | MERCHANT_BRANCH | PARTNER | CUSTOMER");

            $table->integer('parent_id')->index()->nullable()->unsigned()->comment("Parent ID to self table");
            $table->foreign('parent_id')->references('id')->on('sidebar_menu_items');

            $table->string('icon_class', 1000)->nullable()->comment("Icon Class for <i>");
            $table->text('route')->nullable()->comment("Route/URL in Full Path");
            $table->string('route_type', 191)->default(\App\Models\SidebarMenuItem::ROUTE_TYPE_FULL_URL)->nullable()->comment("Route Type FULL_URL | ROUTE_NAME");

            $table->text('access_control')->nullable()->comment("Access Control in JSON ARRAY Format");
            $table->string('access_control_type', 191)->default('AND')->nullable()->comment("Access Control Type AND | OR");

            $table->decimal('sort_number', 16, 2)->default(100)->nullable()->comment("Sort Number");

            //$table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->integer('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sidebar_menu_items');
    }
}
