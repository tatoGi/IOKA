<?php

namespace App\Console\Commands;

use Database\Seeders\BlogPostSeeder;
use Database\Seeders\DeveloperSeeder;
use Database\Seeders\RentalResaleSeeder;
use Database\Seeders\OffplanSeeder;
use Illuminate\Console\Command;

class BulkSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:seed
                            {type : Type of seeder (blog|developer|rental|offplan|all)}
                            {--count=10000 : Number of records to create}
                            {--remove : Remove all records of the specified type}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or remove large amounts of test data (Blog, Developer, Rental Resale, Offplan)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = strtolower($this->argument('type'));
        $count = (int) $this->option('count');
        $remove = $this->option('remove');
        $force = $this->option('force');

        if ($remove) {
            return $this->removeRecords($type, $force);
        }

        return $this->createRecords($type, $count, $force);
    }

    /**
     * Create records
     */
    private function createRecords(string $type, int $count, bool $force)
    {
        if (!$force) {
            if (!$this->confirm("This will create {$count} {$type} records. Are you sure?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info("Creating {$count} {$type} records...");

        try {
            switch ($type) {
                case 'blog':
                    $seeder = new BlogPostSeeder();
                    $seeder->setCount($count);
                    $seeder->run();
                    break;

                case 'developer':
                    $seeder = new DeveloperSeeder();
                    $seeder->setCount($count);
                    $seeder->run();
                    break;

                case 'rental':
                    $seeder = new RentalResaleSeeder();
                    $seeder->setCount($count);
                    $seeder->run();
                    break;

                case 'offplan':
                    $seeder = new OffplanSeeder();
                    $seeder->setCount($count);
                    $seeder->run();
                    break;

                case 'all':
                    $this->createAllRecords($count);
                    break;

                default:
                    $this->error("Invalid type. Use: blog, developer, rental, offplan, or all");
                    return 1;
            }

            $this->info("✅ Successfully created {$count} {$type} records!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error creating records: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Create all types of records
     */
    private function createAllRecords(int $count)
    {
        $types = ['blog', 'developer', 'rental', 'offplan'];

        foreach ($types as $type) {
            $this->info("Creating {$count} {$type} records...");

            switch ($type) {
                case 'blog':
                    $seeder = new BlogPostSeeder();
                    break;
                case 'developer':
                    $seeder = new DeveloperSeeder();
                    break;
                case 'rental':
                    $seeder = new RentalResaleSeeder();
                    break;
                case 'offplan':
                    $seeder = new OffplanSeeder();
                    break;
            }

            $seeder->setCount($count);
            $seeder->run();

            $this->info("✅ Created {$count} {$type} records!");
        }
    }

    /**
     * Remove records
     */
    private function removeRecords(string $type, bool $force)
    {
        if (!$force) {
            if (!$this->confirm("This will remove ALL {$type} records. Are you sure?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info("Removing all {$type} records...");

        try {
            switch ($type) {
                case 'blog':
                    $seeder = new BlogPostSeeder();
                    $seeder->removeSeederRecords();
                    break;

                case 'developer':
                    $seeder = new DeveloperSeeder();
                    $seeder->removeSeederRecords();
                    break;

                case 'rental':
                    $seeder = new RentalResaleSeeder();
                    $seeder->removeSeederRecords();
                    break;

                case 'offplan':
                    $seeder = new OffplanSeeder();
                    $seeder->removeSeederRecords();
                    break;

                case 'all':
                    $this->removeAllRecords();
                    break;

                default:
                    $this->error("Invalid type. Use: blog, developer, rental, offplan, or all");
                    return 1;
            }

            $this->info("✅ Successfully removed all {$type} records!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error removing records: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Remove all types of records
     */
    private function removeAllRecords()
    {
        $types = ['blog', 'developer', 'rental', 'offplan'];

        foreach ($types as $type) {
            $this->info("Removing all {$type} records...");

            switch ($type) {
                case 'blog':
                    $seeder = new BlogPostSeeder();
                    break;
                case 'developer':
                    $seeder = new DeveloperSeeder();
                    break;
                case 'rental':
                    $seeder = new RentalResaleSeeder();
                    break;
                case 'offplan':
                    $seeder = new OffplanSeeder();
                    break;
            }

            $seeder->removeSeederRecords();
            $this->info("✅ Removed all {$type} records!");
        }
    }
}
