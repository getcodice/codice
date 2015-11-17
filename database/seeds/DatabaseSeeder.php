<?php

use Codice\Note;
use Codice\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('test'),
        ]);

        Note::create([
            'user_id' => 1,
            'content' => '<p>Lorem ipsum <strong>dolor</strong> si amet.</p>',
        ]);

        Note::create([
            'user_id' => 1,
            'content' => '<p>A bit more of content here.</p>',
        ]);

        Model::reguard();
    }
}
