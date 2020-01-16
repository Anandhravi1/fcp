<?php

namespace FoxChapel\NewsFeed\Api\Data;

interface DataInterface
{

    const ID = 'id';
    const TITLE = 'title';
    const GUID = 'guid';
    const link = 'link';
    const SHORT_DESCRIPTION  = 'short_description';
    const IMAGE_URL = 'image_url';
    const PUBLISHED_DATE = 'published_date';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param $id
     * @return DataInterface
     */
    public function setId($id);

    /**
     * Get Data Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set Data Title
     *
     * @param $title
     * @return mixed
     */
    public function setTitle($title);

    public function getGuid();

    public function setGuid($guid);

    public function getLink();

    public function setLink($link);


    /**
     * Get Data Description
     *
     * @return mixed
     */
    public function getShortDescription();

    /**
     * Set Data Description
     *
     * @param $description
     * @return mixed
     */
    public function setShortDescription($description);

    /**
     * Get data image
     *
     * @return string
     */
    public function getImageUrl();

    /**
     * Set data image
     *
     * @param $imageUrl
     * @return $this
     */
    public function setImageUrl($imageUrl);

    /**
     * Get is active
     *
     * @return bool|int
     */
    public function getPublishedDate();

    /**
     * Set is active
     *
     * @param $isActive
     * @return DataInterface
     */
    public function setPublishedDate($date);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return DataInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return DataInterface
     */
    public function setUpdatedAt($updatedAt);
}
