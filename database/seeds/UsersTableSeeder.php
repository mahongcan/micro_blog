<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 数据填充
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user = User::find(1);
        $user->name = '马可';
        $user->email = '835215945@qq.com';
        $user->password = bcrypt('make123');
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
    }

}
