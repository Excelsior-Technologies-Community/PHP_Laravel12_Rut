<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laragear\Rut\Rut;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citizens', function (Blueprint $table) {
            $table->string('rut_type')->nullable()->after('rut_vd');
        });

        $citizens = \App\Models\Citizen::all();

        foreach ($citizens as $citizen) {
            try {
                $rut = Rut::create($citizen->rut_num, $citizen->rut_vd);
                $type = 'other';
                if ($rut->isPerson()) $type = 'person';
                elseif ($rut->isCompany()) $type = 'company';
                elseif ($rut->isTemporal()) $type = 'temporal';
                elseif ($rut->isInvestor()) $type = 'investor';

                $citizen->rut_type = $type;
                $citizen->save();
            } catch (\Exception $e) {
                $citizen->rut_type = 'other';
                $citizen->save();
            }
        }
    }

    public function down(): void
    {
        Schema::table('citizens', function (Blueprint $table) {
            $table->dropColumn('rut_type');
        });
    }
};
