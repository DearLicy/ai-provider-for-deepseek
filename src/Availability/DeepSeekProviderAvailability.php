<?php
/**
 * Provider availability for DeepSeek.
 *
 * @package WordPress\DeepSeekAiProvider\Availability
 */

declare(strict_types=1);

namespace WordPress\DeepSeekAiProvider\Availability;

use RuntimeException;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\Http\Contracts\WithRequestAuthenticationInterface;
use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;

/**
 * Treats a non-empty injected API key as configured without probing DeepSeek endpoints.
 */
final class DeepSeekProviderAvailability implements ProviderAvailabilityInterface, WithRequestAuthenticationInterface
{
    /**
     * @var RequestAuthenticationInterface|null Authentication injected by the AI Client registry.
     */
    private ?RequestAuthenticationInterface $requestAuthentication = null;

    /**
     * {@inheritDoc}
     */
    public function setRequestAuthentication(RequestAuthenticationInterface $authentication): void
    {
        $this->requestAuthentication = $authentication;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        if ($this->requestAuthentication === null) {
            throw new RuntimeException('DeepSeek 认证信息尚未设置。');
        }

        return $this->requestAuthentication;
    }

    /**
     * {@inheritDoc}
     */
    public function isConfigured(): bool
    {
        if ($this->requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return trim($this->requestAuthentication->getApiKey()) !== '';
        }

        return false;
    }
}