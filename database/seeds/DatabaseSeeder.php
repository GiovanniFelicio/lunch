<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            'id'        => 1,
            'local_id' => 1,
            'name'      => 'Giovanni',
            'email'     => 'giovanni.carvalho@fundetec.org.br',
            'password'  => bcrypt('11072001'),
            'level'     => 5,
            'matricula' => 171717,
        );
        DB::table('users')->insert($users);
        $locais = array(
            'id' => 1,
            'name' => 'Fundetec',
            'email' => 'fundetec@fundetec.org.br'
        );
        DB::table('locais')->insert($locais);
        $categoria = array(
            'id' => 1,
            'name' => 'C',
            'description' => 'Integral',
            'valor' => 5
        );
        DB::table('categorias')->insert($categoria);
    }
}
