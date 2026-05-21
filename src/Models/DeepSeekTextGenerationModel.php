<?php
/**
 * DeepSeek text generation model.
 *
 * @package WordPress\DeepSeekAiProvider\Models
 */

declare(strict_types=1);

namespace WordPress\DeepSeekAiProvider\Models;

use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use WordPress\DeepSeekAiProvider\Provider\DeepSeekProvider;

/**
 * Text generation through DeepSeek's OpenAI-compatible chat completions endpoint.
 */
final class DeepSeekTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    /**
     * {@inheritDoc}
     */
    protected function createRequest(HttpMethodEnum $method, string $path, array $headers = array(), $data = null): Request
    {
        return new Request(
            $method,
            DeepSeekProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}