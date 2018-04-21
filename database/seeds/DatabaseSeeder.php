<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AuthTableSeeder::class);
        $this->call(SchoolTableSeeder::class);
        $this->call(DisciplinesTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(StudentSchoolTableSeeder::class);

        Model::reguard();
    }
}
