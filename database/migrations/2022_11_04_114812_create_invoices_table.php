<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount');
            $table->string('status')
                ->default(InvoiceStatus::CREATED->value);
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('deferred_expires_at')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Merchant::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
