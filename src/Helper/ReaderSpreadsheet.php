<?php

namespace App\Helper;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ReaderSpreadsheet implements IReadFilter
{
    public static $SPREADSHEET_FILE_NAME = 'file.xlsx';
    public static $SPREADSHEET_COLUMNS = [
        'A' => '_codigo',
        'B' => '_nome',
        'C' => '_preco',
        'M' => '_margem_ebit', 
        'Z' => '_ev_ebit', 
        'AG' => '_liquidez'
    ];

    public function readCell($column, $row, $worksheetName = '')
    {
        // the first 2 lines is not meaningfull to the app
        return ($row > 3 && array_key_exists($column, ReaderSpreadsheet::$SPREADSHEET_COLUMNS)) ? true : false;
    }

    public function readSpreadsheet($file, string $dirUpload)
    {
        $file->move($dirUpload, ReaderSpreadsheet::$SPREADSHEET_FILE_NAME);
        $fileName = "$dirUpload/" . ReaderSpreadsheet::$SPREADSHEET_FILE_NAME;

        $reader = new Xlsx();
        $reader->setReadFilter($this);
        $worksheet = $reader->load($fileName)->getActiveSheet();

        $data = array();

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $dataRow = array();
            foreach ($cellIterator as $key => $cell) {
                if ($key === 'A' && is_null($cell->getValue())) {
                    break; // EOF
                } elseif (!is_null($cell->getValue())) {
                    $dataRow[ReaderSpreadsheet::$SPREADSHEET_COLUMNS[$key]] = match(true) {
                        is_float($cell->getValue()) => (float) $cell->getValue(),
                        $cell->getValue() !== 'NA' => (string) $cell->getValue(),
                        default => -1
                    };
                }
            }

            if (count($dataRow) > 0) {
                $data[] = $dataRow;
            }
        }

        unlink($fileName);

        return $data;
    } 
}