<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.

     *

     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.

     *

     * @var string
     */
    protected $description = 'Backup database';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Determine the database connection

        $connection = config('database.default');

        // Get the database name

        $database = config("database.connections.{$connection}.database");

        // Generate the backup filename

        $backupFilename = "backup_{$database}_".Carbon::now()->format('Y-m-d_H-i-s').'.sql';

        // Determine the appropriate database backup command based on the connection type

        $command = $connection === 'mysql' ? 'mariadb-dump' : 'pg_dump';

        // Execute the database backup command

        exec("$command --user=".config("database.connections.$connection.username").

            ' --password='.config("database.connections.$connection.password").

            ' --host='.config("database.connections.$connection.host").

            ' --port='.config("database.connections.$connection.port").

            " $database > storage/app/$backupFilename");

        $this->info("Database backup created: $backupFilename");
    }
}
