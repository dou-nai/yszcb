<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class GoodscImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public $timestamps = false;
    public function model(array $row)
    {
        return new User([
            'goods_no' => $row[0],
            'goods_name'=> $row[1],
            'goods_price'=> $row[2],
            'press_id'   => $row[3],
            'bookbinding' => $row[4],
            'author'  => $row[5],
            'translate'=> $row[6],
            'classify_one'=> $row[7],
            'classify_two' => $row[8],
            'classify_three' => $row[9],
            'classify_top_ten'  => $row[10],
            'object_classify'=> $row[11],
            'booklist_id'=> $row[12],
            'publish_year' => $row[13],
            'publish_month' => $row[14],
            'edition'=> $row[15],
            'series_name'=> $row[16],
            'publish_state' => $row[17],
            'language' => $row[18],
            'pagination'=> $row[19],
            'cipno'=> $row[20],
            'marketing_classify_one' => $row[21],
            'marketing_classify_two' => $row[22],
            'reader_classify'=> $row[23],
            'catalogue' => $row[24],
            'wonderful' => $row[25],
            'serial'=> $row[26],
            'theme'=> $row[27],
            'goods_images' => $row[28],
            'goods_master_image' => $row[29],
            //'password' => Hash::make($row[2]),
         ]);
        //return $row;
    }
}
