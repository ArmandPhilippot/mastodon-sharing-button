<?php
/**
 * Functions to generate the Mastodon instances list.
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
 * Get instances list from `instances.social` API.
 *
 * @param string $token_key The bearer token used to authenticate the request.
 * @return array The instances list.
 */
function msb_call_api(string $token_key) : array
{
    $api_url = 'https://instances.social/api/1.0/instances/list?count=0&sort_by=name&include_down=false';
    $curl    = curl_init($api_url);
    $headers = array( 'Authorization: Bearer ' . $token_key );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);

    $instances_list = array();

    if ($result) {
        $result    = json_decode($result);
        $instances = $result->instances;

        foreach ($instances as $instance) {
                $instances_list[] = $instance->name;
        }
    }

    return $instances_list;
}

/**
 * Determine if cache is expired.
 *
 * @param string  $file_path The filename to test.
 * @param integer $cache_duration The cache duration.
 * @return boolean True if expired.
 */
function msb_is_cache_expired(string $file_path, int $cache_duration) : bool
{
    $last_update = file_exists($file_path) ? filemtime($file_path) : 0;
    return ( $last_update + $cache_duration ) < time();
}

/**
 * Write the instances in a file to cache them.
 *
 * @param array  $instances The instances list.
 * @param string $file_path The file to use.
 */
function msb_store_in(array $instances, string $file_path)
{
    if (!file_exists(dirname($file_path))) {
        return;
    }

    $handle = fopen($file_path, 'w');
    fwrite($handle, "<?php\n");
    fwrite($handle, 'return $instances = ' . var_export($instances, true) . ";\n");
    fclose($handle);
}

/**
 * Get the Mastodon instances list.
 *
 * @param string  $token_key The bearer token used to authenticate the request.
 * @param string  $instances_path The path of the file used to cache the request.
 * @param integer $cache_duration The duration of cache.
 * @return array The instances list.
 */
function msb_get_instances(string $token_key, $instances_path = 'mastodon-instances-list.php', int $cache_duration = 86400) : array
{
    if (! file_exists($instances_path) || msb_is_cache_expired($instances_path, $cache_duration)) {
        $instances_list = msb_call_api($token_key);
        msb_store_in($instances_list, $instances_path);
    } else {
        $instances_list = include $instances_path;
    }

    return $instances_list;
}
