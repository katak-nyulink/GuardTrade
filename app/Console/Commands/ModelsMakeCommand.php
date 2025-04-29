<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[AsCommand(name: 'make:models')]
class ModelsMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:models
                            {models* : The names of the models to create (space separated)} 
                            {--m|migration : Create a new migration file for each model}
                            {--f|factory : Create a new factory for each model}
                            {--s|seeder : Create a new seeder for each model}
                            {--c|controller : Create a new controller for each model}
                            {--policy : Create a new policy for each model}
                            {--p|pivot : Indicates if the generated model should be a custom intermediate table model}
                            {--d|softdelete : Add SoftDeletes trait and migration column}
                            {--all : Generate a migration, factory, seeder, and resource controller for each model}
                            {--force: Overwrite any existing file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model classes at once with optional SoftDelete functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // if (parent::handle() === false && ! $this->option('force')) {
        //     return false;
        // }

        $modelNames = $this->argument('models');

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('pivot', true);
            $this->input->setOption('policy', true);
            $this->input->setOption('resource', true);
        }

        foreach ($modelNames as $modelName) {
            $this->info("Creating model: {$modelName}");

            $command = "make:model {$modelName}";

            if ($this->option('migration')) {
                $command .= ' -m';
            }

            if ($this->option('factory')) {
                $command .= ' -f';
            }

            if ($this->option('seeder')) {
                $command .= ' -s';
            }

            if ($this->option('controller')) {
                $command .= ' -c';
            }

            if ($this->option('policy')) {
                $command .= null;
            }

            if ($this->option('pivot')) {
                $command .= ' -p';
            }

            Artisan::call($command, [], $this->getOutput());
            sleep(1);

            // Add SoftDelete functionality if requested
            if ($this->option('softdelete')) {
                $this->addSoftDeleteToModel($modelName);

                if ($this->option('migration')) {
                    $this->addSoftDeleteToMigration($modelName);
                }
            }
        }

        $this->info('All models created successfully!');

        return 0;
    }

    /**
     * Add SoftDeletes trait to the model
     *
     * @param string $modelName
     */
    protected function addSoftDeleteToModel($modelName)
    {
        $modelPath = app_path('Models/' . $modelName . '.php');

        if (!File::exists($modelPath)) {
            $modelPath = app_path($modelName . '.php');
        }

        if (File::exists($modelPath)) {
            $content = File::get($modelPath);

            // Add use statement if not already present
            if (strpos($content, 'use Illuminate\Database\Eloquent\SoftDeletes;') === false) {
                $content = str_replace(
                    'namespace App\Models;',
                    "namespace App\Models;\n\nuse Illuminate\Database\Eloquent\SoftDeletes;",
                    $content
                );
            }

            // Add trait if not already present
            if (strpos($content, 'use SoftDeletes;') === false) {
                // Find the class declaration
                if (preg_match('/class\s+' . $modelName . '\s+extends\s+Model\s*{/', $content, $matches, PREG_OFFSET_CAPTURE)) {
                    $classStart = $matches[0][1];
                    $contentBefore = substr($content, 0, $classStart + strlen($matches[0][0]));
                    $contentAfter = substr($content, $classStart + strlen($matches[0][0]));

                    $content = $contentBefore . "\n    use SoftDeletes;\n" . $contentAfter;
                }
            }

            File::put($modelPath, $content);
            $this->info("Added SoftDeletes trait to {$modelName} model");
        }
    }

    /**
     * Add deleted_at column to migration
     *
     * @param string $modelName
     */
    protected function addSoftDeleteToMigration($modelName)
    {
        $migrationPath = database_path('migrations');
        $migrationFiles = File::files($migrationPath);

        // Find the latest migration for this model
        $modelMigration = null;
        foreach ($migrationFiles as $file) {
            if (str_contains($file->getFilename(), 'create_' . strtolower(Str::plural(Str::snake($modelName))) . '_table')) {
                // if (str_contains($file->getFilename(), 'create_' . strtolower(str_plural(snake_case($modelName))) . '_table')) {
                $modelMigration = $file->getPathname();
            }
        }

        if ($modelMigration) {
            $content = File::get($modelMigration);

            // Add soft delete column if not already present
            if (strpos($content, '$table->softDeletes();') === false) {
                $content = str_replace(
                    '$table->timestamps();',
                    '$table->timestamps();' . "\n            \$table->softDeletes();",
                    $content
                );

                File::put($modelMigration, $content);
                $this->info("Added softDeletes to {$modelName} migration");
            }
        }
    }
}
