<?php

namespace App\Imports;

use App\Models\Citizen;
use Illuminate\Support\Facades\Hash;
use Laragear\Rut\Rut;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class CitizensImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        if (empty($row['name']) && empty($row['email']) && empty($row['rut'])) {
            return null;
        }

        try {
            $rut = Rut::parse($row['rut']);

            $type = 'other';
            if ($rut->isPerson()) $type = 'person';
            elseif ($rut->isCompany()) $type = 'company';
            elseif ($rut->isTemporal()) $type = 'temporal';
            elseif ($rut->isInvestor()) $type = 'investor';

            return new Citizen([
                'name' => $row['name'],
                'email' => $row['email'],
                'rut_num' => $rut->num,
                'rut_vd' => $rut->vd,
                'rut_type' => $type,
            ]);
        } catch (\Exception $e) {
            $this->onError($e);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email',
            '*.rut' => 'required|rut',
        ];
    }
}
