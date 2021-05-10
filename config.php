<?php
/**
 * Mastodon Sharing Button config.
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
 * The Mastodon Sharing Button use the instances.social API. In order, to use
 * it, you need to generate a token.
 *
 * @see https://instances.social/api/token
 */
$msb_token_key = 'yourOwnTokenKey';


/**
 * The path where the cached instances list must be written. This path must
 * exist. Default is `../cache/instances-list.php`. The cache folder must be
 * created.
 */
$msb_instances_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'instances-list.php';

/**
 * The Mastodon Sharing Button will check if the HTTP accepted locales have a
 * matching translation. If not, the default locale is "en_US". If you want to
 * overwrite the default locale, use this variable. Make sure the translation
 * exists.
 */
$msb_default_locale = 'en_US';
