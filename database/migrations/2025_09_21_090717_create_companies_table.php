<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->datetime('subscription_start')->nullable();
            $table->datetime('subscription_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('max_employees')->default(10);
            $table->integer('max_branches')->default(1);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
