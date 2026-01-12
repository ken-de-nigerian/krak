<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

/**
 * The Language class which gets and set the languages
 */
class Language
{
    /**
     * User selected language
     * @var string
     */
    public string $language;

    /**
     * Available languages
     * @var array
     */
    public array $languages;

    /**
     * Languages folder path
     * @var string
     */
    public string $folder;

    public function __construct()
    {
        $this->languages = $this->languages();
    }

    /**
     * Get all the available languages from the languages folder
     * @return    array
     */
    private function languages(): array
    {
        // Define the languages folder
        $this->folder = __DIR__ . '/../languages/';

        $languages = [];

        if ($handle = opendir($this->folder)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && str_ends_with($entry, '.php')) {
                    $name = pathinfo($entry);
                    $languages[] = $name['filename'];
                }
            }
            closedir($handle);
        }

        return $languages;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->language;
    }

    /**
     * Set and select the language file
     *
     * @param string|null $language The default site language
     * @return array|string|null
     */
    public function set(string $language = null): array|string|null
    {
        global $lang;
        if (isset($_COOKIE['lang'])) {
            // Verify if the selected language exists
            if (in_array($_COOKIE['lang'], $this->languages)) {
                $language = $_COOKIE['lang'];
            }
        } else {
            setcookie('lang', $language, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH); // Expire in one month
        }

        // Store the selected language
        $this->language = $language;

        // If the language exists, load and return its content
        if (in_array($language, $this->languages)) {
            require_once($this->folder . $language . '.php');
            return $lang;
        }else{
            return NULL; // Language file not found
        }
    }
}