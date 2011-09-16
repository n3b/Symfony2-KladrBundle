<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="n3b\Bundle\Kladr\Entity\StreetRepository")
 */
Class Street
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column
     */
    private $socr;
    /**
     * @ORM\Column
     */
    private $title;
    /**
     * @ORM\ManyToOne(targetEntity="Region")
     */
    private $parent;
    /**
     * @ORM\Column(length="15", unique="true")
     */
    private $code;
	/**
     * @ORM\Column(length="11")
     */
    private $parentCode;
    /**
     * @ORM\Column(type="integer")
     */
    private $zip;
    /**
     * @ORM\Column(type="bigint")
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
