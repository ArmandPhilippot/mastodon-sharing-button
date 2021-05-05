<?php
/**
 * Provide a public-facing view to select the Mastodon instance.
 *
 * @package Mastodon_Sharing_Button
 * @link    https://github.com/ArmandPhilippot/mastodon-sharing-button
 * @author  Armand Philippot <contact@armandphilippot.com>
 *
 * @copyright 2021 Armand Philippot
 * @license   MIT
 * @since     1.0.0
 */

// Load config and functions.
require './config.php';
require './includes/i18n.php';
require './includes/mastodon-instances.php';
require './includes/utils.php';

// Set translation.
$msb_locale           = msb_define_locale($msb_default_locale);
$msb_language         = msb_get_language($msb_locale);
$msb_formatted_locale = msb_get_formatted_locale($msb_locale);

msb_set_locale($msb_formatted_locale);

// Get Mastodon instances.
$msb_instances = array();

if (empty($msb_token_key)) {
    echo htmlspecialchars(_('Mastodon Sharing Button is not configured.'));
} else {
    $msb_instances = msb_get_instances($msb_token_key, $msb_instances_path);
}

$msb_instance_example = strip_tags($msb_instances[ array_rand($msb_instances) ]);

// Handle redirection.
$msb_share_url    = ( ! empty($_GET['url']) ) ? htmlspecialchars($_GET['url']) : '';
$msb_share_title  = ( ! empty($_GET['title']) ) ? htmlspecialchars($_GET['title']) : '';
$msb_instance_url = isset($_POST['instance-url']) ? htmlspecialchars($_POST['instance-url']) : '';

if (msb_is_form_sent() && msb_is_instance_set($msb_instances)) {
    $msb_redirect_to = 'https://' . $msb_instance_url . '/share?url=' . $msb_share_url . '&title=' . $msb_share_title;

    header('Location:' . $msb_redirect_to);
}
?>
<!DOCTYPE html>
<html lang="<?php echo strip_tags($msb_language); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(_('Share on Mastodon')); ?></title>
    <link rel="shortcut icon" href="./assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/style.css">
    <meta name="robots" content="noindex">
</head>
<body>
    <div class="wrapper">
        <header class="msb__header">
            <div class="header__container container">
                <img src="./assets/images/mastodon-logo.svg" alt="Mastodon" class="header__logo">
                <h1 class="header__title">
                    <?php echo htmlspecialchars(_('Share on Mastodon')); ?>
                </h1>
            </div>
        </header>
        <main class="msb__main">
            <div class="main__container container">
                <div class="main__shared-content">
                    <h2 class="shared-content__heading">
                        <?php echo htmlspecialchars(_('Shared content:')); ?>
                    </h2>
                    <div class="shared-content__url">
                        <?php
                        printf(
                            // TRANSLATORS: %1$s: opening HTML wrapper. %2$s: closing HTML wrapper. %3$s Shared URL.
                            _('%1$sURL:%2$s %3$s'),
                            '<span class="shared-content__label">',
                            '</span>',
                            htmlspecialchars(urldecode($msb_share_url))
                        );
                        ?>
                    </div>
                    <div class="shared-content__title">
                        <?php
                        printf(
                            // TRANSLATORS: %1$s: Opening HTML wrapper. %2$s: Closing HTML wrapper. %3$s: Page title.
                            _('%1$sTitle:%2$s %3$s'),
                            '<span class="shared-content__label">',
                            '</span>',
                            htmlspecialchars(urldecode($msb_share_title))
                        );
                        ?>
                    </div>
                </div>
                <form action="#" method="POST" class="main__form">
                    <?php if (msb_is_form_sent() && ! msb_is_instance_set($msb_instances)) { ?>
                        <div class="form__error">
                            <?php
                            printf(
                                // TRANSLATORS: %s: a HTML wrapper.
                                _('%sError:%s You must define an instance URL before sharing.'),
                                '<span class="error">',
                                '</span>'
                            );
                            echo "\n" . _('Either the field is empty or the URL provided is not a Mastodon instance.');
                            ?>
                        </div>
                    <?php } ?>
                    <fieldset class="main__fieldset">
                        <legend class="main__legend">
                            <?php echo htmlspecialchars(_('Select your instance.')); ?>
                        </legend>
                        <label for="instance-url" class="main__label">
                            <?php echo htmlspecialchars(_("Enter your instance's address:")); ?>
                        </label>
                        <span class="main__span">https://</span>
                        <input
                            type="text"
                            name="instance-url"
                            id="instance-url"
                            list="available-instances-url"
                            class="main__input"
                            placeholder="<?php printf(_('Example: %s'), $msb_instance_example); ?>"
                            value="<?php echo strip_tags($msb_instance_url); ?>"
                        />
                        <button type="submit" name="submit-instance" class="main__submit">
                            <?php echo htmlspecialchars(_('Share')); ?>
                        </button>
                        <input type="hidden" name="instance-sent" value="sent">
                    </fieldset>
                    <?php if (! empty($msb_instances)) { ?>
                    <datalist id="available-instances-url">
                        <?php
                        foreach ($msb_instances as $instance) {
                            ?>
                            <option value="<?php echo $instance; ?>"></option>
                            <?php
                        }
                        ?>
                    </datalist>
                    <?php } ?>
                </form>
            </div>
        </main>
        <footer class="msb__footer container">
            <?php
            printf(
                // TRANSLATORS: %s: the project link.
                _('Mastodon Sharing Button. See project on %sGithub%s.'),
                '<a href="https://github.com/ArmandPhilippot/mastodon-sharing-button">',
                '</a>',
            );
            ?>
        </footer>
    </div>
</body>
</html>
