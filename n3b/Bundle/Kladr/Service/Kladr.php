<?php

namespace n3b\Bundle\Kladr\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Kladr
{
    private $em;
    private $repositories;

    public function __construct($em)
    {
        $this->em = $em;
        $this->repo['region'] = $this->em->getRepository('n3b\Bundle\Kladr\Entity\Region');
        $this->repo['street'] = $this->em->getRepository('n3b\Bundle\Kladr\Entity\Street');
    }

    protected function getList($entity, $args)
    {
        return $this->repo[$entity]->findByLead($args);
    }

    public function getRegions($query)
    {
        $res = $this->getList('region', array('query' => $query));

        return new Response(\json_encode($res));
    }

    public function getLimitedRegions($query, $region)
    {
        $res = $this->getList('region', array('query' => $query, 'region' => $region));

        return new Response(\json_encode($res));
    }

    public function getStreets($query, $region)
    {
        $res = $this->getList('street', array('query' => $query, 'region' => $region));

        return new Response(\json_encode($res));
    }

    public function getEmsTo($region, $weight)
    {
        $region = $this->repo['region']->findOneBy(array('code' => $region));

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
        $street = $this->repo['street']->findOneBy(array('code' => $code));
        $region = $this->repo['region']->findOneBy(array('code' => substr($code, 0, -4)));
        if(!$street || !$region)
            return new Response(\json_encode(array('status' => 'err', 'err' => 'not found')));

        $addsStr = $region->getSocr().'. '.$region->getParentStr().', ул. '.$street->getTitle();
        
        return new Response(\json_encode(array('status' => 'ok', 'adds' => $addsStr, 'region' => array('code' => $region->getCode(), 'title' => $region->getTitle()), 'street' => array('code' => $street->getCode(), 'title' => $street->getTitle()))));
    }
}