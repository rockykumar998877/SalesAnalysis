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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('Segment');
            $table->string('Country');
            $table->string('Product');
            $table->string('Discount_Band');
            $table->string('Units_Sold');
            $table->integer('Manufacturing_Price');
            $table->integer('Sale_Price');
            $table->integer('Gross_Sales'); 
            $table->integer('Discounts'); 
            $table->integer('Sales'); 
            $table->integer('COGS');
            $table->integer('Profit');
            $table->date('sale_date');
            $table->integer('Month_Number');
            $table->string('Month_Name');
            $table->integer('Year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
