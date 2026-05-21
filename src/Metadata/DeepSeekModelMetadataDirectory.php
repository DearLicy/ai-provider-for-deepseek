<?php
/**
 * DeepSeek model metadata directory.
 *
 * @package WordPress\DeepSeekAiProvider\Metadata
 */

declare(strict_types=1);

namespace WordPress\DeepSeekAiProvider\Metadata;

use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Http\Exception\ResponseException;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleModelMetadataDirectory;
use WordPress\DeepSeekAiProvider\Provider\DeepSeekProvider;

/**
 * Converts DeepSeek /models responses into WordPress AI model metadata.
 */
final class DeepSeekModelMetadataDirectory extends AbstractOpenAiCompatibleModelMetadataDirectory
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
            $data
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function parseResponseToModelMetadataList(Response $response): array
    {
        $responseData = $response->getData();
        if (!is_array($responseData) || empty($responseData['data']) || !is_array($responseData['data'])) {
            throw ResponseException::fromMissingData('DeepSeek', 'data');
        }

        $models = array();
        foreach ($responseData['data'] as $modelData) {
            if (!is_array($modelData) || empty($modelData['id']) || !is_string($modelData['id'])) {
                continue;
            }

            $modelId = sanitize_text_field($modelData['id']);
            if ($modelId === '') {
                continue;
            }

            $modelName = isset($modelData['name']) && is_string($modelData['name']) && $modelData['name'] !== ''
                ? sanitize_text_field($modelData['name'])
                : $modelId;

            $models[] = new ModelMetadata(
                $modelId,
                $modelName,
                $this->getTextCapabilities(),
                $this->getTextOptions()
            );
        }

        if (empty($models)) {
            throw ResponseException::fromMissingData('DeepSeek', 'data[].id');
        }

        usort($models, array($this, 'sortModels'));
        return $models;
    }

    /**
     * Returns capabilities for DeepSeek chat models.
     *
     * @return list<CapabilityEnum>
     */
    private function getTextCapabilities(): array
    {
        return array(
            CapabilityEnum::textGeneration(),
            CapabilityEnum::chatHistory(),
        );
    }

    /**
     * Returns options supported through DeepSeek's OpenAI-compatible chat completions API.
     *
     * @return list<SupportedOption>
     */
    private function getTextOptions(): array
    {
        return array(
            new SupportedOption(OptionEnum::systemInstruction()),
            new SupportedOption(OptionEnum::candidateCount()),
            new SupportedOption(OptionEnum::maxTokens()),
            new SupportedOption(OptionEnum::temperature()),
            new SupportedOption(OptionEnum::topP()),
            new SupportedOption(OptionEnum::stopSequences()),
            new SupportedOption(OptionEnum::presencePenalty()),
            new SupportedOption(OptionEnum::frequencyPenalty()),
            new SupportedOption(OptionEnum::outputMimeType(), array('text/plain', 'application/json')),
            new SupportedOption(OptionEnum::inputModalities(), array(array(ModalityEnum::text()))),
            new SupportedOption(OptionEnum::outputModalities(), array(array(ModalityEnum::text()))),
            new SupportedOption(OptionEnum::customOptions()),
        );
    }

    /**
     * Sorts common DeepSeek models first while preserving all discovered models.
     */
    private function sortModels(ModelMetadata $a, ModelMetadata $b): int
    {
        $priority = array(
            'deepseek-chat'     => 0,
            'deepseek-reasoner' => 1,
        );

        $aPriority = array_key_exists($a->getId(), $priority) ? $priority[$a->getId()] : 100;
        $bPriority = array_key_exists($b->getId(), $priority) ? $priority[$b->getId()] : 100;

        if ($aPriority !== $bPriority) {
            return $aPriority <=> $bPriority;
        }

        return strnatcasecmp($a->getId(), $b->getId());
    }
}