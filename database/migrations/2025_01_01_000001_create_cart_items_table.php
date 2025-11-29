<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('cart_items', function(Blueprint $t){
            $t->id();
            $t->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $t->string('product_id');
            $t->string('name')->nullable();
            $t->decimal('price',12,2)->default(0);
            $t->integer('qty')->default(1);
            $t->string('image')->nullable();
            $t->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('cart_items'); }
};

