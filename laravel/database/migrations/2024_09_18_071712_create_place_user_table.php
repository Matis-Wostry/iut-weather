<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('place_user', function (Blueprint $table) {
        $table->foreignId('place_id')->constrained()->onDelete('cascade'); // Ref: place_user.place_id > places.id
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ref: place_user.user_id > users.id
        $table->boolean('is_favorite')->default(false); // is_favorite bool
        $table->boolean('send_forecast')->default(false); // send_forecast bool
        $table->primary(['place_id', 'user_id']); // place_id int [pk], user_id int [pk]
        $table->timestamps(); // created_at, updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_user');
    }
};
