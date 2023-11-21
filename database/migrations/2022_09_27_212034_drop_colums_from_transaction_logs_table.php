<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->dropColumn(['action_data', 'action_name', 'assets_ids', 'type']);
        });
    }
};
