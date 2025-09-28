<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('piecework_rates', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->decimal('base_rate', 10, 2);
            $table->decimal('quality_bonus', 10, 2)->default(0);
            $table->decimal('speed_bonus', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('piecework_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('piecework_rate_id')->constrained();
            $table->date('date');
            $table->integer('quantity_good');
            $table->integer('quantity_defect')->default(0);
            $table->decimal('total_earnings', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piecework_entries');
        Schema::dropIfExists('piecework_rates');
    }
};