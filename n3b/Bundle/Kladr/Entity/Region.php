<?php

namespace n3b\Bundle\Kladr\Entity;

/**
 * @orm:Entity(repositoryClass="n3b\Bundle\Kladr\Entity\RegionRepository")
 */
Class Region
{
    /**
     * @orm:Id
     * @orm:Column(type="integer")
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @orm:Column(type="string", length="20")
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
     * @orm:Column
     */
    private $parentStr = '';
    /**
     * @orm:Column(length="11", nullable="true")
     */
    private $code;
	/**
     * @orm:Column(length="11")
     */
    private $parentCode;
    /**
     * @orm:Column(type="smallint")
     */
    private $level;
    /**
     * @orm:Column
     */
    private $emsTo;
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
     * Set level
     *
     * @param smallint $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Get level
     *
     * @return smallint $level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string $code
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

    /**
     * Set parentStr
     *
     * @param string $parentStr
     */
    public function setParentStr($parentStr)
    {
        $this->parentStr = $parentStr;
    }

    /**
     * Get parentStr
     *
     * @return string $parentStr
     */
    public function getParentStr()
    {
        return $this->parentStr;
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
     * Set emsTo
     *
     * @param string $emsTo
     */
    public function setEmsTo($emsTo)
    {
        $this->emsTo = $emsTo;
    }

    /**
     * Get emsTo
     *
     * @return string $emsTo
     */
    public function getEmsTo()
    {
        return $this->emsTo;
    }
}
