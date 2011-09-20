<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="n3b\Bundle\Kladr\Entity\StreetRepository")
 * @ORM\Table(indexes={@ORM\Index(name="street_title_idx",columns={"title"})})
 */
Class KladrStreet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
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
     * @ORM\ManyToOne(targetEntity="KladrRegion")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
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