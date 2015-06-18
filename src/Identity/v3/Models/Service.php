<?php

namespace OpenStack\Identity\v3\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * @property \OpenStack\Identity\v3\Api $api
 */
class Service extends AbstractResource implements Creatable, Listable, Retrievable, Updateable, Deletable
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var string */
    public $description;

    /** @var []Endpoint */
    public $endpoints;

    /** @var array */
    public $links;

    protected $resourceKey = 'service';

    public function create(array $data)
    {
        $response = $this->execute($this->api->postServices(), $data);
        return $this->populateFromResponse($response);
    }

    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getService());
        return $this->populateFromResponse($response);
    }

    public function update()
    {
        $response = $this->executeWithState($this->api->patchService());
        return $this->populateFromResponse($response);
    }

    public function delete()
    {
        $this->executeWithState($this->api->deleteService());
    }

    /**
     * @param $name
     * @param $type
     * @param $region
     *
     * @return string|false
     */
    public function getUrl($name, $type, $region)
    {
        if ($this->name !== $name || $this->type !== $type) {
            return false;
        }

        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->region == $region) {
                return $endpoint->url;
            }
        }

        return false;
    }
} 