<?php
/**
 * Functions to perform some check on form submit.
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
 * Check if the form has been sent.
 *
 * @return boolean True if the form is sent.
 */
function msb_is_form_sent()
{
    return ( isset($_POST['instance-sent']) && 'sent' === $_POST['instance-sent'] && isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD'] );
}

/**
 * Check if the instance URL is defined.
 *
 * @param array $msb_instances The Mastodon instances.
 * @return boolean True if an instance URL is set.
 */
function msb_is_instance_set($msb_instances)
{
    return ( isset($_POST['instance-url']) && ! empty($_POST['instance-url']) && in_array($_POST['instance-url'], $msb_instances) );
}
