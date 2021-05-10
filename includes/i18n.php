<?php
/**
 * Functions to translate Mastodon Sharing Button.
 *
 * @package Mastodon_Sharing_Button
 * @link    https://github.com/ArmandPhilippot/mastodon-sharing-button
 * @author  Armand Philippot <contact@armandphilippot.com>
 *
 * @copyright 2021 Armand Philippot
 * @license   MIT
 * @since     1.0.0
 */

/**
 * Get a list of available translations.
 *
 * @return array The available languages.
 */
function msb_get_available_languages()
{
    $languages_dir = glob('languages' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
    $languages     = str_replace('languages' . DIRECTORY_SEPARATOR, '', $languages_dir);

    return $languages;
}

/**
 * Get accepted languages based on HTTP header.
 *
 * @return array The accepted languages.
 */
function msb_get_languages_from_http()
{
    $languages = '';

    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $languages = preg_split('/(,|;)/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], -1, PREG_SPLIT_NO_EMPTY);
        $languages = array_filter(
            $languages,
            function ($value) {
                return substr($value, 0, 2) !== 'q=';
            }
        );
    }

    return $languages;
}

/**
 * Check if a locale is a valid locale.
 *
 * @param string $locale The locale to test.
 * @param array  $valid_locales The valid locales.
 * @return boolean True if the locale exists.
 */
function msb_is_valid_locale(string $locale, array $valid_locales) : bool
{
    if (empty($locale)) {
        return false;
    }

    return in_array($locale, $valid_locales, true);
}

/**
 * Filter the accepted locales to determine the locale to use.
 *
 * @param array  $locales The locales to test.
 * @param string $default_locale The default locale.
 * @return string The first valid locale.
 */
function msb_get_valid_locale(array $locales, string $default_locale) : string
{
    $valid_locales = msb_get_available_languages();

    if (isset($default_locale) && msb_is_valid_locale($default_locale, $valid_locales)) {
        $default_locale = $default_locale;
    } else {
        $default_locale = 'en_US';
    }

    $possible_locales = array_intersect($locales, $valid_locales);
    $possible_locales = array_values($possible_locales);
    $locale           = '';

    if (count($possible_locales) > 0) {
        $locale = $possible_locales[0];
    } else {
        $locale = $default_locale;
    }

    return $locale;
}

/**
 * Define the locale to use.
 *
 * @param string $default_locale The default locale.
 * @return string The locale to use.
 */
function msb_define_locale(string $default_locale)
{
    $http_language = msb_get_languages_from_http();
    $http_locale   = str_replace('-', '_', $http_language);
    $http_locale = is_array($http_locale) ? $http_locale : array($http_locale);
    $locale        = msb_get_valid_locale($http_locale, $default_locale);

    return $locale;
}

/**
 * Get a list of possible format based on the locale to use.
 *
 * @param string $locale The locale to use.
 * @return array The locale in different formats to use.
 */
function msb_get_formatted_locale(string $locale)
{
    $formatted_locale   = array( $locale );
    $formatted_locale[] = $locale . '.utf8';
    $formatted_locale[] = $locale . '.UTF-8';
    $formatted_locale[] = strtok($locale, '_');

    return $formatted_locale;
}

/**
 * Transform a locale format (ex: `en_US`) to a language format (ex: `en-US`).
 *
 * @param string $locale The used locale.
 * @return string The language to use.
 */
function msb_get_language(string $locale)
{
    $language = str_replace('_', '-', $locale);

    return $language;
}

/**
 * Set the locale.
 *
 * @param array $formatted_locale The locale with its different formats.
 */
function msb_set_locale(array $formatted_locale)
{
    setlocale(LC_ALL, $formatted_locale);
    bindtextdomain('msbDomain', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'languages');
    bind_textdomain_codeset('msbDomain', 'UTF-8');
    textdomain('msbDomain');
}
