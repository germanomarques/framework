<?php

declare(strict_types=1);

namespace FondBot;

use SplFileInfo;
use ReflectionClass;
use Illuminate\Support\Str;
use FondBot\Conversation\Intent;
use Symfony\Component\Finder\Finder;

class FondBot
{
    /**
     * The registered intents.
     *
     * @var array
     */
    public static $intents = [];

    /**
     * Fallback intent.
     *
     * @var string
     */
    public static $fallbackIntent;

    /**
     * Get the current FondBot version.
     *
     * @return string
     */
    public static function version(): string
    {
        return '4.0.0';
    }

    /**
     * Register the given intents.
     *
     * @param  array $intents
     * @return static
     */
    public static function intents(array $intents)
    {
        static::$intents = array_merge(static::$intents, $intents);

        return new static;
    }

    /**
     * Register all of the intent classes in the given directory.
     *
     * @param  string $directory
     * @return void
     */
    public static function intentsIn(string $directory): void
    {
        $namespace = app()->getNamespace();

        $intents = [];

        /** @var SplFileInfo[] $files */
        $files = (new Finder)->in($directory)->files();

        foreach ($files as $file) {
            $file = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($file, Intent::class) && !(new ReflectionClass($file))->isAbstract()) {
                $intents[] = $file;
            }
        }

        static::intents($intents);
    }

    /**
     * Register the given fallback intent.
     *
     * @param  string $intent
     * @return void
     */
    public static function fallbackIntent(string $intent): void
    {
        static::$fallbackIntent = $intent;
    }
}
