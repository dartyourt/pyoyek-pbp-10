<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->after('status');
            $table->string('shipping_city')->after('shipping_address');
            $table->string('shipping_postal_code')->after('shipping_city');
            $table->string('payment_proof_path')->nullable()->after('shipping_postal_code');
            
            $table->dropColumn('address_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'shipping_city', 'shipping_postal_code', 'payment_proof_path']);
            $table->text('address_text')->after('status');
        });
    }
};