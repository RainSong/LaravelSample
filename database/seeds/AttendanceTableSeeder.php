<?php

use Illuminate\Database\Seeder;
use \App\Models\Attendance;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendances')->truncate();


        $attendance1 = new Attendance();
        $attendance1->department_id = 0;
        $attendance1->date = '2018-02-26';
        $attendance1->total = 6;
        $attendance1->attendance = 6;
        $attendance1->leave = 0;
        $attendance1->other = 0;
        $attendance1->report_user_id = 1;
        $attendance1->save();

        $attendance2 = new Attendance();
        $attendance2->department_id = 0;
        $attendance2->date = '2018-02-25';
        $attendance2->total = 6;
        $attendance2->attendance = 5;
        $attendance2->leave = 1;
        $attendance2->other = 0;
        $attendance2->report_user_id = 1;
        $attendance2->remark='1人请假';
        $attendance2->save();

        $attendance3 = new Attendance();
        $attendance3->department_id = 0;
        $attendance3->date = '2018-02-24';
        $attendance3->total = 6;
        $attendance3->attendance = 6;
        $attendance3->leave = 0;
        $attendance3->other = 0;
        $attendance3->report_user_id = 1;
        $attendance3->save();

        $attendance4 = new Attendance();
        $attendance4->department_id = 0;
        $attendance4->date = '2018-02-23';
        $attendance4->total = 6;
        $attendance4->attendance = 6;
        $attendance4->leave = 0;
        $attendance4->other = 0;
        $attendance4->report_user_id = 1;
        $attendance4->save();

        $attendance5 = new Attendance();
        $attendance5->department_id = 0;
        $attendance5->date = '2018-02-22';
        $attendance5->total = 6;
        $attendance5->attendance = 6;
        $attendance5->leave = 0;
        $attendance5->other = 0;
        $attendance5->report_user_id = 1;
        $attendance5->save();
        $attendance6 = new Attendance();

        $attendance6->department_id = 0;
        $attendance6->date = '2018-02-21';
        $attendance6->total = 6;
        $attendance6->attendance = 6;
        $attendance6->leave = 0;
        $attendance6->other = 0;
        $attendance6->report_user_id = 1;
        $attendance6->save();
    }
}
