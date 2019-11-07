<?php

namespace HDInnovations\XbtitFmToUnit3d\Commands;

use ErrorException;
use App\Models\User;
use App\Models\Torrent;
use App\Models\Category;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\ConnectionInterface;
use HDInnovations\XbtitFmToUnit3d\Functionality\Imports;

class FromXbtitFm extends Command
{
    /** @var string The name and signature of the console command */
    protected $signature = 'unit3d:from-xbtitfm
                            {--driver=mysql : The driver type to use (mysql, sqlsrv, etc.)}
                            {--host=localhost : The hostname or IP}
                            {--database= : The database to select from}
                            {--username= : The database user}
                            {--password= : The database password}
                            {--prefix= : The database hostname or IP}
                            {--ignore-users : Ignore the users table when importing}
                            {--ignore-categories : Ignore the categories table when importing}
                            {--ignore-torrents : Ignore the torrents table when importing}';

    /** @var string The console command description */
    protected $description = 'Import data from an XbtitFM instance to UNIT3D. You xbtitFM tables must be using the default xbtit table prefix. Like xbtit_users.';

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws ErrorException
     */
    public function handle(): void
    {
        $this->checkRequired($this->options());

        config([
            'database.connections.imports' => [
                'driver' => $this->option('driver'),
                'host' => $this->option('host'),
                'database' => $this->option('database'),
                'username' => $this->option('username'),
                'password' => $this->option('password'),
                'prefix' => $this->option('prefix'),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
        ]);

        $database = DB::connection('imports');

        $this->importUsers($database);
        $this->importCategories($database);
        $this->importTorrents($database);
    }

    private function checkRequired(array $options): void
    {
        $requiredOptions = [
            'database',
            'username',
            'password',
        ];

        foreach ($requiredOptions as $option) {
            if (! array_key_exists($option, $options) || ! $options[$option]) {
                throw new InvalidArgumentException('Option `'.$option.'` not provided');
            }
        }
    }

    /**
     * @param  ConnectionInterface  $database
     * @throws ErrorException
     */
    private function importUsers(ConnectionInterface $database): void
    {
        if ($this->option('ignore-users')) {
            $this->output->note('Ignoring users table');

            return;
        }

        Imports::importTable($database, 'User', 'xbtit_users', User::class);
    }

    /**
     * @param  ConnectionInterface  $database
     * @throws ErrorException
     */
    private function importTorrents(ConnectionInterface $database): void
    {
        if ($this->option('ignore-torrents')) {
            $this->output->note('Ignoring torrents table');

            return;
        }

        Imports::importTable($database, 'Torrent', 'xbtit_files', Torrent::class);
    }

    /**
     * @param  ConnectionInterface  $database
     * @throws ErrorException
     */
    private function importCategories(ConnectionInterface $database): void
    {
        if ($this->option('ignore-categories')) {
            $this->output->note('Ignoring categories table');

            return;
        }

        Imports::importTable($database, 'Category', 'xbtit_categories', Category::class);
    }
}
