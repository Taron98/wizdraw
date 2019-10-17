<?php

namespace Wizdraw\Console\Commands;

use Wizdraw\Mail\StorageAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StorageSize extends Command
{
    const LOCAL = 'C:\xampp\htdocs';

    const PROD = '/var/www';

    private $size = 0;

    private $space = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get and sum entire folder and sub folder files size.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = scandir(storage_path());

        foreach ($files as $file) {

            $file = storage_path($file);

            if ($file != storage_path(DIRECTORY_SEPARATOR) && $file != storage_path('..') && $file != storage_path('.')) {
                is_dir($file) ? $this->scan($file) : $this->size += filesize($file);
            }
        }

        $disk = env('APP_ENV') !== 'production' ? static::LOCAL : static::PROD;

        $this->space['project'] = $this->size($this->size, storage_path());
        $this->space['server'] = $this->size(disk_free_space($disk), $disk);

        $this->dispatch();
    }

    protected function scan($dir)
    {
        $files = scandir($dir);

        foreach ($files as $file) {

            $file = $dir . DIRECTORY_SEPARATOR . $file;

            if ($file != $dir && $file != $dir . DIRECTORY_SEPARATOR . '..' && $file != $dir . DIRECTORY_SEPARATOR . '.') {
                is_dir($file) ? $this->scan($file) : $this->size += filesize($file);
            }
        }
    }

    protected function size($bytes, $folder_name)
    {
        $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
        $base = 1024;
        $class = min((int)log($bytes, $base), count($si_prefix) - 1);
        $this->info($folder_name . ' ' . sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class]);

        return ['size' => sprintf('%1.2f', $bytes / pow($base, $class)), 'unit' => $si_prefix[$class]];
    }

    protected function dispatch()
    {
        $units = ['GB', 'TB', 'EB', 'ZB', 'YB'];

        foreach ($this->space as $space) {

            if ($space['size'] >= 15 && in_array($space['unit'], $units)) {
                $cc = env('APP_ENV') !== 'production' ? StorageAlert::TEST : StorageAlert::PROD;
                Mail::to($cc)->queue(new StorageAlert($this->space));
                die(200);
            }
        }
    }
}
