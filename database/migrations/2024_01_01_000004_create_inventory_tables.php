<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('uoms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('uom_id')->constrained();
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // IN, OUT, TRANSFER
            $table->foreignId('item_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses');
            $table->decimal('quantity', 10, 2);
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['item_id', 'warehouse_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_stocks');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('items');
        Schema::dropIfExists('uoms');
        Schema::dropIfExists('warehouses');
    }
};