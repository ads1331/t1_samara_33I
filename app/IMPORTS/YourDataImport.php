<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class YourDataImport implements ToArray
{
    public function array(array $rows)
    {
        return $rows;
    }
}
