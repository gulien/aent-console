<?php

namespace TheAentMachine\Aent\Registry;

use GuzzleHttp\Client;
use TheAentMachine\Aent\Registry\Exception\ColonyRegistryException;

final class ColonyRegistry
{
    private const ORCHESTRATOR = 'orchestrator';
    private const CI = 'ci';

    /** @var AentItemRegistry[] */
    private $aents;

    /**
     * @return ColonyRegistry
     * @throws ColonyRegistryException
     */
    public static function orchestratorRegistry(): self
    {
        $self = new ColonyRegistry();
        $self->fetch(self::ORCHESTRATOR);
        return $self;
    }

    /**
     * @return ColonyRegistry
     * @throws ColonyRegistryException
     */
    public static function CIRegistry(): self
    {
        $self = new ColonyRegistry();
        $self->fetch(self::CI);
        return $self;
    }

    /**
     * @param string $category
     * @return void
     * @throws ColonyRegistryException
     */
    private function fetch(string $category): void
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://raw.githubusercontent.com/theaentmachine/colony-registry/master/$category.json");
            $body = \GuzzleHttp\json_decode($response->getBody(), true);
            $this->aents = [];
            foreach ($body as $item) {
                $this->aents[] = AentItemRegistry::fromArray($item);
            }
        } catch (\Exception $e) {
            throw new ColonyRegistryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return AentItemRegistry[]
     */
    public function getAents(): array
    {
        return $this->aents;
    }
}
