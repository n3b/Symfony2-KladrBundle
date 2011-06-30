<?php

namespace n3b\Bundle\Kladr\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class KladrManager extends Controller
{
    private $em;
    private $repositories;

    public function __construct($em)
    {
        $this->em = $em;
        $this->setRepository('Region');
        $this->setRepository('Street');
    }

    protected function setRepository($entity)
    {
        $this->repositories[$entity] = $this->em->getRepository('n3b\Bundle\Kladr\Entity\\'.$entity);
    }
    
    protected function getRepository($entity)
    {
        if(\array_key_exists($entity, $this->repositories))
            return $this->repositories[$entity];
    }

    protected function getList($entity, $args)
    {
        if(isset($this->repositories[$entity]))
            return $this->repositories[$entity]->findByLead($args);
    }

    public function getRegions($query)
    {
        $res = $this->getList('Region', array('query' => $query));

        return new Response(\json_encode($res));
    }

    public function getLimitedRegions($query, $region)
    {
        $res = $this->getList('Region', array('query' => $query, 'region' => $region));

        return new Response(\json_encode($res));
    }

    public function getStreets($query, $region)
    {
        $res = $this->getList('Street', array('query' => $query, 'region' => $region));

        return new Response(\json_encode($res));
    }

    public function getEmsTo($region, $weight)
    {
        $region = $this->getRepository('Region')->findOneBy(array('code' => $region));

        while(!$region->getEmsTo()) {
            $region = $region->getParent();
            if(!$region)
                return new Response(\json_encode(array('emsTo' => 'not found')));
        }
        $url = 'http://emspost.ru/api/rest?method=ems.calculate&from=city--moskva&to='.$region->getEmsTo().'&weight='.$weight;

        return new Response(\file_get_contents($url));
    }
    
    public function getAddsByCode($code)
    {
        $street = $this->getRepository('Street')->findOneBy(array('code' => $code));
        $region = $this->getRepository('Region')->findOneBy(array('code' => substr($code, 0, -4)));
        if(!$street || !$region)
            return new Response(\json_encode(array('status' => 'err', 'err' => 'not found')));

        $addsStr = $region->getSocr().'. '.$region->getParentStr().', ул. '.$street->getTitle();
        
        return new Response(\json_encode(array('status' => 'ok', 'adds' => $addsStr, 'region' => array('code' => $region->getCode(), 'title' => $region->getTitle()), 'street' => array('code' => $street->getCode(), 'title' => $street->getTitle()))));
    }
}