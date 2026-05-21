<?php
/**
 * DeepSeek AI provider.
 *
 * @package WordPress\DeepSeekAiProvider\Provider
 */

declare(strict_types=1);

namespace WordPress\DeepSeekAiProvider\Provider;

use WordPress\AiClient\AiClient;
use WordPress\AiClient\Common\Exception\RuntimeException;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiProvider;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Http\Enums\RequestAuthenticationMethod;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\DeepSeekAiProvider\Availability\DeepSeekProviderAvailability;
use WordPress\DeepSeekAiProvider\Metadata\DeepSeekModelMetadataDirectory;
use WordPress\DeepSeekAiProvider\Models\DeepSeekTextGenerationModel;

/**
 * Registers DeepSeek as an OpenAI-compatible provider.
 */
final class DeepSeekProvider extends AbstractApiProvider
{
    public const ID = 'deepseek';

    /**
     * {@inheritDoc}
     */
    protected static function baseUrl(): string
    {
        return 'https://api.deepseek.com';
    }

    /**
     * {@inheritDoc}
     */
    protected static function createModel(ModelMetadata $modelMetadata, ProviderMetadata $providerMetadata): ModelInterface
    {
        foreach ($modelMetadata->getSupportedCapabilities() as $capability) {
            if ($capability->isTextGeneration()) {
                return new DeepSeekTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException('DeepSeek 暂不支持该模型能力。');
    }

    /**
     * {@inheritDoc}
     */
    protected static function createProviderMetadata(): ProviderMetadata
    {
        $args = array(
            self::ID,
            'DeepSeek',
            ProviderTypeEnum::cloud(),
            'https://platform.deepseek.com/api_keys',
            RequestAuthenticationMethod::apiKey(),
        );

        if (version_compare(AiClient::VERSION, '1.2.0', '>=')) {
            $args[] = function_exists('__')
                ? __('通过 DeepSeek 官方 OpenAI 兼容接口提供文本生成能力。', 'ai-provider-for-deepseek')
                : '通过 DeepSeek 官方 OpenAI 兼容接口提供文本生成能力。';
        }

        if (version_compare(AiClient::VERSION, '1.3.0', '>=')) {
            $args[] = \DEEPSEEK_AI_PROVIDER_DIR . 'assets/images/deepseek.svg';
        }

        return new ProviderMetadata(...$args);
    }

    /**
     * {@inheritDoc}
     */
    protected static function createProviderAvailability(): ProviderAvailabilityInterface
    {
        return new DeepSeekProviderAvailability();
    }

    /**
     * {@inheritDoc}
     */
    protected static function createModelMetadataDirectory(): ModelMetadataDirectoryInterface
    {
        return new DeepSeekModelMetadataDirectory();
    }
}