<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::factory()->create(['name' => 'Super Admin', 'email' => 'admin@example.com']);

        Staff::factory()->create(['name' => 'Demo Staff', 'email' => 'staff@example.com']);
        Staff::factory()->inactive()->create(['name' => 'Inactive Staff', 'email' => 'inactive@example.com']);
        Staff::factory()->unverified()->create(['name' => 'Unverified Staff', 'email' => 'unverified@example.com']);
        Staff::factory()->count(6)->create();

        $eligible = Staff::where('is_active', true)->whereNotNull('email_verified_at')->get();

        Project::factory()->count(3)->create(['admin_id' => $admin->id])->each(function ($project) use ($admin, $eligible) {
            Task::factory()->count(5)->state(fn () => [
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'assigned_to' => $eligible->random()->id,
            ])->create();
        });
    }
}
