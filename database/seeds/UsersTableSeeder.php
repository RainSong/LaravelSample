<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $users = [
            [
                'user_name'=>'znl',
                'sex'=>'0',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'张娜拉',
                'phone'=>'17812345678',
                'email'=>'17812345678@yahoo.com',
                'brithday'=>'1977-12-1',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'2016-12-3'
            ],
            [
                'user_name'=>'wbq',
                'sex'=>'1',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'王宝强',
                'phone'=>'17912345678',
                'email'=>'17912345678@yahoo.com',
                'brithday'=>'1985-3-3',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'2015-8-8'
            ],
            [
                'user_name'=>'sjlyhx',
                'sex'=>'0',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'斯嘉丽约翰逊',
                'phone'=>'18012345678',
                'email'=>'18012345678@yahoo.com',
                'brithday'=>'1975-6-8',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'207-1-1'
            ],
            [
                'user_name'=>'zgr',
                'sex'=>'1',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'张国荣',
                'phone'=>'18112345678',
                'email'=>'18112345678@gmail.com',
                'brithday'=>'1977-1-1',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'2015-12-12'
            ],
            [
                'user_name'=>'myf',
                'sex'=>'0',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'梅艳芳',
                'phone'=>'18212345678',
                'email'=>'18212345678@qq.com',

                'brithday'=>'1982-5-5',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'2017-8-8'
            ],
            [
                'user_name'=>'cl',
                'sex'=>'1',
                'password'=>password_hash('1234',PASSWORD_DEFAULT),
                'name'=>'成龙',
                'phone'=>'18312345678',
                'email'=>'18312345678@163.com',
                'brithday'=>'1955-8-9',
                'address'=>'河南省濮阳市华龙区',
                'entry_time'=>'2017-8-8'
            ]
        ];
        DB::table('users')->insert($users);
    }
}
