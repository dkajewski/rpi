<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class GenerateVueTranslationFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-translations:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate js file containing translations from resources/lang';

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
     * @return int
     */
    public function handle()
    {
        $this->generate();

        return 0;
    }

    private function generate()
    {
        $translations = $this->getVueFormattedLaravelTranslations();
        $this->saveVueTranslationsInFile($translations);
    }

    private function getReplaceForMatches($matches)
    {
        foreach ($matches as &$word) {
            $word = str_replace(':', '{', $word).'}';
        }

        return $matches;
    }

    private function getVueFormattedTranslations($translation)
    {
        if (is_array($translation)) {
            $translations = [];
            foreach ($translation as $key => $text) {
                $translations[$key] = $this->getVueFormattedTranslations($text);
            }

            return $translations;
        } else {
            $toTranslate = [$translation];
        }

        foreach ($toTranslate as &$text) {
            $matches = [];
            preg_match_all('/:\w+/', $text, $matches);
            $matches = $matches[0];
            $replace = $this->getReplaceForMatches($matches);
            $text = str_replace($matches, $replace, $text);
        }

        if (count($toTranslate) === 1) {
            $translation = $toTranslate[0];
        } else {
            $translation = $toTranslate;
        }

        return $translation;
    }

    private function getVueFormattedLaravelTranslations()
    {
        //@todo: look inside nested folders :/
        $langPath = resource_path('lang');
        $filesystem = new Filesystem();
        $mainDirs = str_replace($langPath.'\\','', $filesystem->directories($langPath));
        $filesInDirs = [];
        foreach ($mainDirs as $dir) {
            $filesInDirs[$dir] = $filesystem->files($langPath.'\\'.$dir);
        }

        $translations = [];
        foreach ($filesInDirs as $dirName => $dirContent) {
            foreach ($dirContent as $id => $file) {
                $translations[$dirName][$file->getFilenameWithoutExtension()] = require($filesInDirs[$dirName][$id]->getPathname());
            }
        }

        foreach ($translations as &$languageVersion) {
            foreach ($languageVersion as &$fileContent) {
                foreach ($fileContent as &$translation) {
                    $translation = $this->getVueFormattedTranslations($translation);
                }
            }
        }

        return $translations;
    }

    private function saveVueTranslationsInFile($translations)
    {
        $resourceJsPath = resource_path('js');
        $data = 'export default '.json_encode($translations, JSON_PRETTY_PRINT);
        if (!File::exists($resourceJsPath)) {
            File::makeDirectory($resourceJsPath, 0755, true, false);
        }

        if (File::put($resourceJsPath.'/vue-i18n-locales.generated.js', $data)) {
            echo 'Generated successfully!';
        }
    }

}
