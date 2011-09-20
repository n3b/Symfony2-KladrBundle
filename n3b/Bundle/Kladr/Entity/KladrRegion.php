<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="n3b\Bundle\Kladr\Entity\RegionRepository")
 * @ORM\Table(indexes={@ORM\Index(name="region_title_idx",columns={"title"})})
 */
Class KladrRegion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length="20")
     */
    private $socr;
    /**
     * @ORM\Column
     */
    private $title;
    /**
     * @ORM\ManyToOne(targetEntity="KladrRegion")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    /**
     * @ORM\Column(nullable="true")
     */
    private $fullParentTitle;
	/**
     * @ORM\Column(length="11", nullable="true")
     */
    private $parentCode;
    /**
     * @ORM\Column(type="smallint")
     */
    private $level;
    /**
     * @ORM\Column(nullable="true")
     */
    private $emsTo;
    /**
     * @ORM\Column(type="integer")
     */
    private $zip;
    /**
     * @ORM\Column(type="bigint")
     */
    private $ocatd;

    /**
     * Set id
     *
     * @param bigint $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return bigint $id
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
     * Set fullParentTitle
     *
     * @param string $fullParentTitle
     */
    public function setFullParentTitle($fullParentTitle)
    {
        $this->fullParentTitle = $fullParentTitle;
    }

    /**
     * Get fullParentTitle
     *
     * @return string $fullParentTitle
     */
    public function getFullParentTitle()
    {
        return $this->fullParentTitle;
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
     * @param bigint $ocatd
     */
    public function setOcatd($ocatd)
    {
        $this->ocatd = $ocatd;
    }

    /**
     * Get ocatd
     *
     * @return bigint $ocatd
     */
    public function getOcatd()
    {
        return $this->ocatd;
    }

    /**
     * Set parent
     *
     * @param n3b\Bundle\Kladr\Entity\KladrRegion $parent
     */
    public function setParent(\n3b\Bundle\Kladr\Entity\KladrRegion $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return n3b\Bundle\Kladr\Entity\KladrRegion $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
}