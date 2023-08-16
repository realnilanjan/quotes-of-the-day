<?php
/*
Plugin Name: Quotes of the Day
Description: Displays random quotes of the day.
Version: 1.0
Author: Nilanjan Roy
Author URI: https://penandparchment.space/author/nilanjan/
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: quotes-of-the-day
*/

// Array of quotes
$quotes = array(
    "The only way to do great work is to love what you do. - Steve Jobs",
    "Innovation distinguishes between a leader and a follower. - Steve Jobs",
    "The future depends on what you do today. - Mahatma Gandhi",
    "The best way to predict the future is to create it. - Peter Drucker",
    "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
    "The only limit to our realization of tomorrow will be our doubts of today. - Franklin D. Roosevelt",
    "The only thing standing between you and your dream is the will to try and the belief that it is actually possible. - Joel Brown",
    "The greatest glory in living lies not in never falling, but in rising every time we fall. - Nelson Mandela",
    "Your time is limited, don't waste it living someone else's life. - Steve Jobs",
    "Strive not to be a success, but rather to be of value. - Albert Einstein",
    "It does not matter how slowly you go as long as you do not stop. - Confucius",
    "The only thing necessary for the triumph of evil is for good men to do nothing. - Edmund Burke",
    "If you want to lift yourself up, lift up someone else. - Booker T. Washington",
    "The best revenge is massive success. - Frank Sinatra",
    "Believe you can and you're halfway there. - Theodore Roosevelt",
    "In three words I can sum up everything I've learned about life: it goes on. - Robert Frost",
    "Success is walking from failure to failure with no loss of enthusiasm. - Winston Churchill",
    "The only true wisdom is in knowing you know nothing. - Socrates",
    "The only thing we have to fear is fear itself. - Franklin D. Roosevelt",
    "A person who never made a mistake never tried anything new. - Albert Einstein",
    "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
    "The journey of a thousand miles begins with one step. - Lao Tzu",
    "To be yourself in a world that is constantly trying to make you something else is the greatest accomplishment. - Ralph Waldo Emerson",
    "The best time to plant a tree was 20 years ago. The second best time is now. - Chinese Proverb",
    "Life is really simple, but we insist on making it complicated. - Confucius",
);

// Register settings
function quotes_of_the_day_register_settings()
{
    register_setting('quotes-of-the-day-settings-group', 'quotes_of_the_day_font_color');
}

// Add settings link to plugin page
function quotes_of_the_day_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=quotes-of-the-day-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'quotes_of_the_day_settings_link');

// Display settings page
function quotes_of_the_day_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Quotes of the Day Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('quotes-of-the-day-settings-group'); ?>
            <?php do_settings_sections('quotes-of-the-day-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Font Color</th>
                    <td><input type="color" name="quotes_of_the_day_font_color"
                            value="<?php echo esc_attr(get_option('quotes_of_the_day_font_color', '#ffffff')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <h2>About the Author</h2>
        <p>This plugin was developed by Nilanjan Roy. You can find more about him on his website: <a
                href="https://penandparchment.space/author/nilanjan/">https://penandparchment.space/author/nilanjan/</a></p>
        <h2>About the Plugin</h2>
        <p>You can find more details on <a
                href="https://penandparchment.space/author/nilanjan/">https://penandparchment.space/author/nilanjan/</a></p>
    </div>
    <?php
}

// Display plugin content
function get_random_quote_with_author()
{
    global $quotes;
    $random_index = array_rand($quotes);
    $quote_with_author = $quotes[$random_index];
    list($quote, $author) = explode(" - ", $quote_with_author, 2);
    return array('quote' => $quote, 'author' => $author);
}

function quotes_of_the_day_shortcode()
{
    $quote_data = get_random_quote_with_author();
    $font_color = get_option('quotes_of_the_day_font_color', '#ffffff'); // Get font color from settings

    ob_start(); // Start output buffering
    ?>
    <div class="quotes-of-the-day-wrapper">
        <div class="quotes-of-the-day" style="color: <?php echo esc_attr($font_color); ?>">
            <h4>
                <?php echo esc_html($quote_data['quote']); ?>
            </h4>
            <div class="author">-
                <?php echo esc_html($quote_data['author']); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean(); // End output buffering and return content
}

// Add styles
function quotes_of_the_day_enqueue_styles()
{
    wp_enqueue_style('quotes-of-the-day-style', plugin_dir_url(__FILE__) . 'quotes-of-the-day-style.css');
}
add_action('wp_enqueue_scripts', 'quotes_of_the_day_enqueue_styles');

// Add settings page
function quotes_of_the_day_settings_menu()
{
    add_options_page('Quotes of the Day Settings', 'Quotes of the Day', 'manage_options', 'quotes-of-the-day-settings', 'quotes_of_the_day_settings_page');
}
add_action('admin_menu', 'quotes_of_the_day_settings_menu');

// Hook into the Settings API
add_action('admin_init', 'quotes_of_the_day_register_settings');

// Add shortcode
add_shortcode('quotes_of_the_day', 'quotes_of_the_day_shortcode');
?>