<?php
namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Category;

class CategorySeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Disable triggers (equivalent to disabling foreign key checks)
        DB::statement('ALTER TABLE categories DISABLE TRIGGER ALL;');

        // Truncate the table
        DB::table('categories')->truncate();

        // Seed the data
        Category::categoryFactory()->count(10000)->create();
        Category::subcategoryFactory()->count(20000)->create();

        // Re-enable triggers (re-enabling foreign key checks)
        DB::statement('ALTER TABLE categories ENABLE TRIGGER ALL;');
    }
}
