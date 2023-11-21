<?php

use Domain\Player\Models\Player;
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
        Schema::create('staked_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class);
            $table->string('asset_id');
            $table->json('data');
            $table->timestamp('staked_at');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staked_assets');
    }
};
