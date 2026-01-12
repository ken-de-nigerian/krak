<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

/**
 * The view class which generates the views
 */
class View
{
    /**
     * The current URL path (route) arrays to be passed to the controllers
     * @var array
     */
    public array $url;

    /**
     * The site metadata
     * @var array   An array containing various metadata attributes
     *              Array Map: Metadata => Mixed(Values)
     */
    public array $metadata;

    /**
     * The site settings from the `settings` DB table
     * @var array
     */
    protected array $settings;

    /**
     * The livechat from the `extensions` DB table
     * @var array
     */
    protected array $extensions;

    /**
     * The whatsapp from the `extensions` DB table
     * @var array
     */
    protected array $whatsapp;

    /**
     * Constructor for View class.
     *
     * @param array $settings The settings array.
     * @param array $extensions The extension array.
     * @param array $url The URL.
     */
    public function __construct(array $settings, array $extensions, array $whatsapp, array $url)
    {
        $this->settings = $settings;
        $this->extensions = $extensions;
        $this->whatsapp = $whatsapp;
        $this->url = $url;
    }

    /**
     * Render a view template.
     *
     * @param array|null $data The data to be passed to the view template.
     * @param string|null $view The file path / name of the view.
     * @return string The rendered view content.
     */
    public function render(array $data = null, string $view = null): string
    {
        ob_start();
        require sprintf('%s/../../%s/%s/views/%s.php', __DIR__, PUBLIC_PATH, THEME_PATH, $view);
        return ob_get_clean();
    }

    /**
     * Retrieve and render any messages stored in the session.
     *
     * @return string|null The rendered messages or null if no message is found.
     */
    public function message(): ?string
    {
        $messages = null;
        if (isset($_SESSION['message'])) {
            foreach ($_SESSION['message'] as $value) {
                $data['message'] = ['type' => $value[0], 'content' => $value[1]];
                $messages .= $this->render($data, 'shared/message');
            }
        }
        unset($_SESSION['message']);
        return $messages;
    }

    /**
     * Generate a unique token ID and render a token input field.
     *
     * @return string The rendered token input field.
     */
    public function token(): string
    {
        $uniqueTokenId = uniqid('token_', true);
        $data['token_id'] = $_SESSION['token_id'];
        $data['uniqueTokenId'] = $uniqueTokenId;
        return $this->render($data, 'shared/token');
    }

    /**
     * Returns the base URL of the site.
     *
     * @return string The base URL of the site.
     */
    public function siteUrl(): string
    {
        return URL_PATH;
    }

    /**
     * Returns the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    public function themePath(): string
    {
        return THEME_PATH;
    }

    /**
     * @param string $key The key of the cookie to retrieve
     * @return mixed|null Returns the value of the cookie if it exists, or null otherwise
     */
    public function cookie(string $key): mixed
    {
        // Check if the cookie with the specified key exists
        return $_COOKIE[$key] ?? null;
    }

    /**
     * @param string $key The key to access in the $settings array
     * @return mixed|null Returns the value corresponding to the key, or null if the key doesn't exist
     */
    public function siteSettings(string $key): mixed
    {
        // Check if the key exists in the $settings array
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        } else {
            // Key doesn't exist, return null or handle it appropriately
            return null;
        }
    }

    /**
     * @param string $key The key to access in the $extensions array
     * @return mixed|null Returns the value corresponding to the key, or null if the key doesn't exist
     */
    public function liveChat(string $key): mixed
    {
        // Check if the key exists in the $extensions array
        if (array_key_exists($key, $this->extensions)) {
            return $this->extensions[$key];
        } else {
            // Key doesn't exist, return null or handle it appropriately
            return null;
        }
    }

    /**
     * @param string $key The key to access in the $whatsapp array
     * @return mixed|null Returns the value corresponding to the key, or null if the key doesn't exist
     */
    public function whatsApp(string $key): mixed
    {
        // Check if the key exists in the $whatsapp array
        if (array_key_exists($key, $this->whatsapp)) {
            return $this->whatsapp[$key];
        } else {
            // Key doesn't exist, return null or handle it appropriately
            return null;
        }
    }
}