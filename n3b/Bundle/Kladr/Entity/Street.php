<?php

namespace n3b\Bundle\Kladr\Entity;

/**
 * @orm:Entity(repositoryClass="n3b\Bundle\Kladr\Entity\StreetRepository")
 */
Class Street
{
    /**
     * @orm:Id
     * @orm:Column(type="integer")
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @orm:Column
     */
    private $socr;
    /**
     * @orm:Column
     */
    private $title;
    /**
     * @orm:ManyToOne(targetEntity="Region")
     */
    private $parent;
    /**
     * @orm:Column(length="15", unique="true")
     */
    private $code;
	/**
     * @orm:Column(length="11")
     */
    private $parentCode;
    /**
     * @orm:Column(type="integer")
     */
    private $zip;
    /**
     * @orm:Column(type="bigint")
     */
    private $ocatd;

    /**
     * Get id
     *
     * @return integer $id
     */

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set socr
     *
     * @param string $socr
     */
    public function setSocr($socr)
    {
        $this->socr = $socr;
    }

    /**
     * Get socr
     *
     * @return string $socr
     */
    public function getSocr()
    {
        return $this->socr;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set code
     *
     * @param bigint $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return bigint $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set parentCode
     *
     * @param string $parentCode
     */
    public function setParentCode($parentCode)
    {
        $this->parentCode = $parentCode;
    }

    /**
     * Get parentCode
     *
     * @return string $parentCode
     */
    public function getParentCode()
    {
        return $this->parentCode;
    }

    /**
     * Set parent
     *
     * @param n3b\Bundle\Kladr\Entity\Region $parent
     */
    public function setParent(\n3b\Bundle\Kladr\Entity\Region $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return n3b\Bundle\Kladr\Entity\Region $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Get zip
     *
     * @return integer $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set ocatd
     *
     * @param integer $ocatd
     */
    public function setOcatd($ocatd)
    {
        $this->ocatd = $ocatd;
    }

    /**
     * Get ocatd
     *
     * @return integer $ocatd
     */
    public function getOcatd()
    {
        return $this->ocatd;
    }
}
