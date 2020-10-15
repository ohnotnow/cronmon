<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReformatRoutes extends Command
{
    protected $signature = 'route:reformat-l8 {--file=web} {--dry-run}';

    protected $description = 'Reformat routes file to laravel 8 format';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $fileName = base_path().'/routes/'.$this->option('file').'.php';
        $contents = file_get_contents($fileName);

        $newContents = collect(explode(PHP_EOL, $contents))->map(function ($line) {
            if (! str_contains($line, '@')) {
                return $line;
            }

            $controllerSection = [];
            if (preg_match('/, (\"|\')([A-Za-z0-9\\\\]+@[a-zA-Z]+)(\"|\')/', $line, $controllerSection) === 0) {
                return $line;
            }

            [$controllerName, $methodName] = explode('@', $controllerSection[2]);

            $classPrefix = 'App\\Http\\Controllers\\';
            if (str_contains($controllerName, $classPrefix)) {
                $classPrefix = '';
            }
            $newLine = str_replace(
                $controllerSection[0],
                ", [{$classPrefix}".$controllerName.'::class, '."'{$methodName}']",
                $line
            );

            return $newLine;
        });

        if ($this->option('dry-run')) {
            $this->info($newContents->implode(PHP_EOL));

            return;
        }

        file_put_contents($fileName, $newContents->implode(PHP_EOL));

        $this->info('Done. Remember to set the $namespace in RouteServiceProvider to null.');
    }
}
