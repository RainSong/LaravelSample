<?php

use Illuminate\Database\Seeder;

class InterviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data_interviews = array(array(
            'id_card' => '410901198205083567',
            'name' => '张丽',
            'sex' => 0,
            'brithday' => '1982-05-08',
            'interview_time' => '2017-10-30',
            'entry_time' => '2017-11-1',
            'mobile' => '19357858786',
            'address' => '河南省濮阳市华龙区岳村乡',
            'id_card_img1' => '',
            'id_card_img2' => ''
        ), array(
            'id_card' => '410901199110168848',
            'name' => '李强',
            'sex' => 0,
            'brithday' => '1991-10-16',
            'interview_time' => '2017-10-30',
            'entry_time' => '2017-11-1',
            'mobile' => '15277997797',
            'address' => '河南省濮阳市华龙区岳村乡',
            'id_card_img1' => '',
            'id_card_img2' => ''
        ));
        DB::table('interviews')->insert($data_interviews);
    }
}
