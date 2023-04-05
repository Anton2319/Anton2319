<?php
namespace museshelf;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="playlists")
 */
class Playlist
{
    /**
     * @ODM\Id
     */
    private $_id;

    /**
     * @ODM\Field(type="string")
     */
    public $map;

    private $name;

    private $link;

    private $tags;

    public function toArray()
    {
        $this->name = json_decode($this->map, true)["name"];
        $this->link = json_decode($this->map, true)["link"];
        $this->tags = json_decode($this->map, true)["tags"];
        return [
            'id' => (string) $this->_id,
            'name' => $this->name,
            'link' => $this->link,
            'tags' => $this->tags
        ];
    }
}
