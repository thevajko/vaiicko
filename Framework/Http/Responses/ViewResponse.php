<?php

namespace Framework\Http\Responses;

use App\Configuration;
use Framework\Core\App;
use Framework\Support\View as ViewHelper;

/**
 * Class ViewResponse
 *
 * Represents an HTTP response that renders a view, typically in the context of a web application. This class is
 * responsible for generating the appropriate HTML output by combining a view file with optional layout and data.
 *
 * @package App\Core\Responses
 */
class ViewResponse extends Response
{
    /**
     * Instance of the application, used for accessing application-level services.
     *
     * @var App
     */
    private App $app;

    /**
     * The name of the view file to render.
     *
     * @var string
     */
    private string $viewName;

    /**
     * The data to be passed to the view for rendering.
     *
     * @var array
     */
    private array $data;

    /**
     * ViewResponse constructor.
     *
     * Initializes a new instance of ViewResponse with the specified application instance, view name, and data to be
     * passed to the view.
     *
     * @param App $app The application instance.
     * @param string $viewName The name of the view to render.
     * @param array $data The data to be passed to the view.
     */
    public function __construct(App $app, string $viewName, array $data)
    {
        $this->app = $app;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    /**
     * Generates and outputs the rendered view.
     *
     * This method captures the output of the view rendering process and handles the inclusion of layout files if
     * specified. It creates view helpers based on the application instance, which can be used within the views.
     *
     * @return void
     */
    protected function generate(): void
    {
        // View helpers available in all views
        $viewHelpers = [
            'user' => $this->app->getAppUser(),
            'link' => $this->app->getLinkGenerator(),
        ];

        // Selected layout is controlled by the helper via reference; default to root layout
        $selectedLayout = Configuration::ROOT_LAYOUT;
        $view = new ViewHelper($selectedLayout);

        // Render the main view; it may call $view->layout('name')
        $viewData = $viewHelpers + $this->data + ['view' => $view];

        ob_start();
        $this->renderView($viewData, $this->viewName . '.view.php');
        $contentHTML = ob_get_clean();

        if ($selectedLayout !== null) {
            $layoutData = $viewHelpers + ['contentHTML' => $contentHTML];
            $this->renderView($layoutData, $this->getLayoutFullName($selectedLayout));
        } else {
            echo $contentHTML;
        }
    }

    /**
     * Renders the specified view file with the given data.
     *
     * This method extracts the provided data as variables for use within the view and
     * includes the view file, making it part of the current output.
     *
     * @param array $data The data to be made available in the view.
     * @param string $viewPath The path to the view file to render.
     *
     * @return void
     */
    private function renderView(array $data, string $viewPath): void
    {
        $fullPath = $this->resolveViewPath($viewPath);
        $this->includeResolvedView($fullPath, $data);
    }

    /**
     * Determines the full path of the specified layout file.
     *
     * This method checks if the layout name ends with the expected extension, and appends it if necessary to ensure
     * the correct layout file is used.
     *
     * @param string $layoutName The base name of the layout.
     * @return string The full path of the layout file.
     */
    private function getLayoutFullName(string $layoutName): string
    {
        $file = str_ends_with($layoutName, '.layout.view.php') ? $layoutName : $layoutName . '.layout.view.php';
        return 'Layouts' . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Includes the view file located at the given relative path.
     *
     * This method resolves the full path of the view file using the application's view path resolution logic and
     * includes the file, making it part of the current output.
     *
     * @param string $relativePath The relative path of the view file to include.
     *
     * @return void
     */
    private function includeView(string $relativePath): void
    {
        $fullPath = $this->resolveViewPath($relativePath);
        require $fullPath;
    }

    /**
     * Resolves the full path of a view file given its relative path.
     *
     * This method normalizes the directory separators in the relative path, prepends the base views directory, and
     * checks for the existence of the file. It also attempts to resolve the path in a case-insensitive manner if
     * the file is not found.
     *
     * @param string $relativePath The relative path of the view file.
     * @return string The resolved full path of the view file.
     *
     * @throws \RuntimeException If the view file cannot be found.
     */
    private function resolveViewPath(string $relativePath): string
    {
        $base = $this->getViewsBasePath();
        $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        $candidate = $base . DIRECTORY_SEPARATOR . $normalized;

        if (is_file($candidate)) {
            return $candidate;
        }

        $resolved = $this->resolveCaseInsensitive($base, $normalized);
        if ($resolved !== null && is_file($resolved)) {
            return $resolved;
        }

        throw new \RuntimeException("View file {$relativePath} not found in {$base}");
    }

    /**
     * Gets the base path for the application's view files.
     *
     * This method returns the directory path where the view files are located, typically within the App directory
     * of the application structure.
     *
     * @return string The base path for view files.
     */
    private function getViewsBasePath(): string
    {
        return dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR . 'App'
            . DIRECTORY_SEPARATOR . 'Views';
    }

    /**
     * Resolves the given relative path to a file in a case-insensitive manner.
     *
     * This method attempts to find a file matching the given relative path by scanning the directory contents and
     * performing a case-insensitive comparison. It is useful for filesystems that are case-insensitive.
     *
     * @param string $baseDir The base directory to start the resolution from.
     * @param string $relativePath The relative path to resolve.
     * @return string|null The resolved path, or null if not found.
     */
    private function resolveCaseInsensitive(string $baseDir, string $relativePath): ?string
    {
        $segments = array_filter(explode(DIRECTORY_SEPARATOR, $relativePath), static fn($part) => $part !== '');
        $current = $baseDir;

        foreach ($segments as $segment) {
            $entries = @scandir($current);
            if ($entries === false) {
                return null;
            }

            $match = null;
            foreach ($entries as $entry) {
                if (strcasecmp($entry, $segment) === 0) {
                    $match = $entry;
                    break;
                }
            }

            if ($match === null) {
                return null;
            }

            $current .= DIRECTORY_SEPARATOR . $match;
        }

        return $current;
    }

    /**
     * Includes the resolved view file with the provided data.
     *
     * This method extracts the data array into variables and includes the specified view file,
     * allowing the view to access the data directly.
     *
     * @param string $fullPath The full path of the view file to include.
     * @param array $data The data to be made available in the view.
     *
     * @return void
     */
    private function includeResolvedView(string $fullPath, array $data): void
    {
        extract($data, EXTR_SKIP);
        require $fullPath;
    }
}
