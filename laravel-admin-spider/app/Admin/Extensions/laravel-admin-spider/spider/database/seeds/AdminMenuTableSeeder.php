<?php

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //用于一些数据迁移
        $menuTableModel = config('admin.database.menu_model');
        if ($menuTableModel) {

        }
    }
}
