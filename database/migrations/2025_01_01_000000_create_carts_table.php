<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('carts', function(Blueprint $t){
            $t->id();
            $t->string('session_id')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('carts'); }
};

