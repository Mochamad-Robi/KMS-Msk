<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kpi_quant_details', function (Blueprint $table) {
            // Drop FK & kolom lama yang terikat ke master template
            $table->dropForeign(['kpi_quant_template_id']);
            $table->dropColumn('kpi_quant_template_id');

            // Tambah kolom input manual: nama indikator + bobot ditentukan kadept sendiri
            $table->string('indicator_name')->after('kpi_evaluation_id');
            $table->decimal('weight_percent', 5, 2)->after('indicator_name');
            $table->integer('order')->default(0)->after('weight_percent');
        });
    }

    public function down(): void
    {
        Schema::table('kpi_quant_details', function (Blueprint $table) {
            $table->dropColumn(['indicator_name', 'weight_percent', 'order']);
            $table->foreignId('kpi_quant_template_id')->nullable()->constrained('kpi_quant_templates')->onDelete('cascade');
        });
    }
};