<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 29-Jul-21
 * Time: 12:34
 */

namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class H {

    // Session Login Related
    public static function isLogin() {
        return S::isLogin();
    }




    // Flash Message Related
    public static function flashSuccess($message, $is_global = false) {
        S::flashSuccess($message, $is_global);
    }
    public static function getFlashSuccess($is_global = false) {
        return S::getFlashSuccess($is_global);
    }
    public static function flashFailed($message, $is_global = false) {
        S::flashFailed($message, $is_global);
    }
    public static function getFlashFailed($is_global = false) {
        return S::getFlashFailed($is_global);
    }

    public static function formatDateTime($date_time_string, $date_time_format = "d-M-Y H:i:s") {
        if ($date_time_string) {

            if (is_string($date_time_string)) {
                try {
                    return Carbon::createFromFormat("Y-m-d H:i:s", $date_time_string)->format($date_time_format);
                } catch (\Exception $e) {
                    return Carbon::parse($date_time_string)->format($date_time_format);
                }
            } else {
                try {
                    return $date_time_string->format($date_time_format);
                } catch (\Exception $e) { $e->getTrace(); }
                return $date_time_string;
            }
        } else {
            return "";
        }
    }

    public static function formatDateTimeWithoutTime($date_time_string, $date_time_format = "d-M-Y")
    {
        if ($date_time_string) {

            if (is_string($date_time_string)) {
                try {
                    return Carbon::createFromFormat("Y-m-d", $date_time_string)->format($date_time_format);
                } catch (\Exception $e) {
                    return Carbon::parse($date_time_string)->format($date_time_format);
                }
            } else {
                try {
                    return $date_time_string->format($date_time_format);
                } catch (\Exception $e) { $e->getTrace(); }
                return $date_time_string;
            }
        } else {
            return "";
        }
    }

    public static function formatNumber($number_string, $comma_count = 0) {
        $formatted = substr(number_format($number_string, $comma_count + 1, ',', '.'), 0, -1);
        // if the last character is comma, remove it
        if (substr($formatted, -1) == ",") {
            $formatted = substr($formatted, 0, -1);
        }
        return $formatted;
    }


    public static function generateRandomString($length = 64, $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $characters = $pool;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = self::penyebut($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = self::penyebut($nilai/10)." Puluh". self::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . self::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::penyebut($nilai/100) . " Ratus" . self::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . self::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::penyebut($nilai/1000) . " Ribu" . self::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::penyebut($nilai/1000000) . " Juta" . self::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::penyebut($nilai/1000000000) . " Milyar" . self::penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::penyebut($nilai/1000000000000) . " Trilyun" . self::penyebut(fmod($nilai,1000000000000));
        }
        return $temp;
    }

    public static function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(self::penyebut($nilai));
        } else {
            $hasil = trim(self::penyebut($nilai));
        }
        return $hasil;
    }

    public static function getStreamCsv($file_name, $column_headers, $data_arr, \Closure $data_processor = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = $column_headers;

        $callback = function() use ($data_arr, $columns, $data_processor)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($data_arr as $row) {
                if ($data_processor) {
                    fputcsv($file, $data_processor($row));
                } else {
                    $arr = [];
                    foreach ($row as $cell) {
                        $arr[] = $cell;
                    }
                    fputcsv($file, $arr);
                }
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public static function getStreamExcel($file_name, $data_arr) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data_arr, null, "A1");

        // redirect output to client browser
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
