<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created new repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repositoryName = $this->argument("model");
        $directory = app_path("Repositories/{$repositoryName}");

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0775, true, true);
        }

        $repositoryTemplate = $this->getRepositoryTemplate($repositoryName);
        $repositoryInterfaceTemplate = $this->getRepositoryInterfaceTemplate($repositoryName);

        $repositoryFile = $directory . "/" . $repositoryName . "Repository.php";
        $repositoryInterfaceFile = $directory . "/" . $repositoryName . "RepositoryContract.php";

        if (File::exists($repositoryFile)) {
            $this->error("{$repositoryName}Repository.php существует!");
            return;
        }

        if (File::exists($repositoryInterfaceFile)) {
            $this->error("{$repositoryName}RepositoryInterface.php существует!");
            return;
        }

        File::put($repositoryInterfaceFile, $repositoryInterfaceTemplate);
        $this->info("Интерфейс класс {$repositoryName}InterfaceRepository.php успешно создан.");


        File::put($repositoryFile, $repositoryTemplate);
        $this->info("Репозиторий класс {$repositoryName}Repository.php успешно создан.");    
    }

    private function getRepositoryTemplate(string $name)
    {
        $template = <<<'EOD'
        <?php

        namespace App\Repositories\$name;

        use App\Models\$name;
        use App\Repositories\BaseRepository;

        class $nameRepository extends BaseRepository implements $nameRepositoryContract
        {
            public function __construct()
            {
                $class = $name::class;
                parent::__construct($class);
            }
        }
        EOD;

        $template = str_replace('$name', $name, $template);

        return $template;
    }

    private function getRepositoryInterfaceTemplate(string $name)
    {
        $template = <<<'EOD'
        <?php

        namespace App\Repositories\$name;

        interface $nameRepositoryContract
        {

        }
        EOD;

        $template = str_replace('$name', $name, $template);

        return $template;
    }
}
