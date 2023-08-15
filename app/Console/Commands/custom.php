<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class custom extends Command
{
    // protected $signature = 'make:custom {className} --d={classDirectory}';

    protected $description = 'Create custom class based on template';

    public function __construct()
    {
        parent::__construct();
    }
    public function configure()
    {
        return $this->setName('make:custom')
            // ->setDescription('Making custom class based on template')
            ->addArgument('className', InputArgument::REQUIRED, 'class name to be created (basename)')
            ->addOption('d', null, InputOption::VALUE_OPTIONAL, 'class directory to be created (dirname)', '');
    }

    public function handle()
    {

        $classDirectory = $this->option('d') ? $this->option('d') : '';
        $classDirectory = trim($classDirectory, '/');
        $classDirectory = str_replace('/', '\\', $classDirectory);
        $className = $this->argument('className');
        $className = Str::studly($className);

        $this->info(sprintf("Class Directory : %s,Class name : %s", $classDirectory, $className));

        $err = false;

        $this->generateFile(
            'model',
            $err,
            $classDirectory,
            $className
        );

        $this->generateFile(
            'controller',
            $err,
            $classDirectory,
            $className
        );

        $this->generateFile(
            'service',
            $err,
            $classDirectory,
            $className
        );

        $this->generateFile(
            'repository',
            $err,
            $classDirectory,
            $className
        );

        $this->generateFile(
            'resource',
            $err,
            $classDirectory,
            $className
        );

        // $this->generateFile(
        //     'factory',
        //     $err,
        //     $classDirectory,
        //     $className
        // );

        // $this->generateFile(
        //     'seeder',
        //     $err,
        //     $classDirectory,
        //     $className,
        // );

        $this->generateFile(
            'migration',
            $err,
            $classDirectory,
            $className,
        );

        // $this->generateFile(
        //     'test',
        //     $err,
        //     $classDirectory,
        //     $className
        // );

        if (! $err) {
            $this->info("----------------------------------------\nSemua komponen dari {$className} telah berhasil dibuat!");
        }
    }

    public function generateFile(
        $templateFileName,
        &$err,
        $classDirectory,
        $className,
    ) {
        $templateFileNameTitleCase = Str::studly($templateFileName);
        $this->info("{$templateFileNameTitleCase} {$className} sedang dibuat...");

        switch ($templateFileName) {
            case 'model':
                $componentPath = 'App/Models';
                break;
            case 'controller':
                $componentPath = 'App/Http/Controllers';
                break;
            case 'service':
                $componentPath = 'App/Services';
                break;
            case 'repository':
                $componentPath = 'App/Repositories';
                break;
            case 'resource':
                $componentPath = 'App/Http/Resources';
                break;
            case 'factory':
                $componentPath = 'database/factories';
                break;
            case 'seeder':
                $componentPath = 'database/seeders';
                break;
            case 'migration':
                $componentPath = 'database/migrations';
                break;
            case 'test':
                $componentPath = 'tests/Unit';
                break;
        }

        $classNameSnakePlural = Str::snake(Str::plural($className));
        $classNameCamelCase = Str::camel($className);
        $classNameCamelCasePlural = Str::camel(Str::plural($className));

        switch ($templateFileName) {
            case 'model':
                $path = "{$componentPath}/{$classDirectory}/{$className}.php";
                break;
            case 'migration':
                $fileName = date('Y_m_d_His')."_create_{$classNameSnakePlural}_table";
                $path = "{$componentPath}/{$fileName}.php";
                break;
            default:
                $fileNameSuffix = Str::studly($templateFileNameTitleCase);
                $path = "{$componentPath}/{$classDirectory}/{$className}{$fileNameSuffix}.php";
                break;
        }

        if ($templateFileName === 'migration') {
            $migrationFiles = File::glob("{$componentPath}/*_create_{$classNameSnakePlural}_table.php");
            if (count($migrationFiles) > 0) {
                $err = true;
                $this->error("ERROR: [{$migrationFiles[0]}] telah tersedia.");
                $this->info("\n");

                return;
            }
        } elseif (File::exists($path)) {
            $err = true;
            $this->error("ERROR: [$path] telah tersedia.");
            $this->info("\n");

            return;
        }

        $template = File::get(base_path("app/Console/Commands/stubs/custom-{$templateFileName}.stub"));
        $content = str_replace(
            [
                '{{ classDirectory }}',
                '{{ class }}',
                '{{ classNameSnakePlural }}',
                '{{ classNameCamelCase }}',
                '{{ classNameCamelCasePlural }}',
            ],
            [
                $classDirectory == '' ? '':sprintf("\\%s",$classDirectory),
                $className,
                $classNameSnakePlural,
                $classNameCamelCase,
                $classNameCamelCasePlural,
            ],
            $template
        );

        try {
            File::put($path, $content);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'No such file or directory') !== false) {
                $this->error("ERROR: Direktori /{$componentPath}/{$classDirectory} tidak ditemukan.");
                $answer = $this->ask("Apakah Anda ingin membuat direktori {$classDirectory} di {$componentPath}? [y/n]");

                if ($answer === 'y') {
                    $this->info("Membuat direktori /{$componentPath}/{$classDirectory}...");
                    File::makeDirectory("{$componentPath}/{$classDirectory}", 0755, true);

                    $this->info("{$templateFileNameTitleCase} {$className} sedang dibuat...\n");
                    File::put($path, $content);
                } elseif ($answer === 'n') {
                    $err = true;
                } else {
                    $err = true;
                    $this->error('ERROR: Jawaban tidak valid.');
                    $this->info("\n");
                }
            } else {
                $err = true;
                $this->error("ERROR: [{$e->getMessage()}]");
                $this->info("\n");
            }

            return;
        }
    }
}
