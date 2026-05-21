<?php
/**
 * Plugin Name: AI Provider for DeepSeek
 * Plugin URI: https://github.com/DearLicy/ai-provider-for-deepseek
 * Description: 为 WordPress AI Client 增加 DeepSeek 官方 OpenAI 兼容接口 Provider，用户只需在 Connectors 中填写 DeepSeek API Key。
 * Requires at least: 6.9
 * Requires PHP: 7.4
 * Version: 1.0.0
 * Author: 李初一
 * Author URI: https://github.com/DearLicy
 */

declare(strict_types=1);

namespace WordPress\DeepSeekAiProvider;

use WordPress\AiClient\AiClient;
use WordPress\DeepSeekAiProvider\Provider\DeepSeekProvider;

if (!defined('ABSPATH')) {
    return;
}

define('DEEPSEEK_AI_PROVIDER_FILE', __FILE__);
define('DEEPSEEK_AI_PROVIDER_DIR', plugin_dir_path(__FILE__));
define('DEEPSEEK_AI_PROVIDER_VERSION', '1.0.0');

require_once __DIR__ . '/src/autoload.php';

/**
 * Registers DeepSeek with the WordPress AI Client.
 */
function register_provider(): void
{
    if (!class_exists(AiClient::class)) {
        return;
    }

    $registry = AiClient::defaultRegistry();

    if ($registry->hasProvider(DeepSeekProvider::ID) || $registry->hasProvider(DeepSeekProvider::class)) {
        return;
    }

    $registry->registerProvider(DeepSeekProvider::class);
}
add_action('init', __NAMESPACE__ . '\\register_provider', 5);