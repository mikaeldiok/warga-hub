<?php

namespace Modules\Core\Imports;

use Modules\Core\Entities\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitsImport implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        return new Unit([
            'name'              => $row['name'],
            'contact_number'    => $row['contact_number'],
            'contact_email'     => $row['contact_email'],
        ]);
    }
}