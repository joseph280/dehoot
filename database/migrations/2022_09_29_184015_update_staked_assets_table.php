<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staked_assets', function (Blueprint $table) {
            $table->string('land')->after('asset_id')->default('default');
            $table->unsignedInteger('position_x')->after('land')->default(0);
            $table->unsignedInteger('position_y')->after('position_x')->default(0);
            $table->unsignedInteger('rows')->after('position_y')->default(1);
            $table->unsignedInteger('columns')->after('rows')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
