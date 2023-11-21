<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->string('wallet_id')->after('player_id');
            $table->string('amount')->nullable()->after('wallet_id');
            $table->string('type')->after('status');
            $table->json('asset_ids')->after('type');
        });
    }
};
