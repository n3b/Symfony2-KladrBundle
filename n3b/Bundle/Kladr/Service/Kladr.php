<?php

namespace n3b\Bundle\Kladr\Service;

use Symfony\Component\HttpFoundation\Response;
use n3b\Bundle\Util\String;

class Kladr
{
    protected $em;
    protected $request;

    public function __construct($em, $request)
    {
        $this->em = $em;
        $this->request = $request;
        $this->repo['region'] = $this->em->getRepository('n3b\Bundle\Kladr\Entity\KladrRegion');
        $this->repo['street'] = $this->em->getRepository('n3b\Bundle\Kladr\Entity\KladrStreet');
    }

    public function getRegions($query)
    {
        try {
            if(!\strlen($query))
                throw new \Exception('the query is empty');
            
            $res = $this->repo['region']->getByQuery(array('query' => $query));
            $res['status'] = 'ok';
        } catch(\Exception $e) {
            $res['status'] = 'error';
            $res['err_message'] = $e->getMessage();
        }

        return new Response(String::jsonEncode($res));
    }

    public function getStreets($query, $region)
    {
        try {
            if(!\strlen($query))
                throw new \Exception('the query is empty');

            if(!strlen($region))
                throw new \Exception('the region is empty');

            $res = $this->repo['street']->getByQuery(array(
                'query' => $query,
                'region' => $region
                ));

            $res['status'] = 'ok';
        } catch(\Exception $e) {
            $res['status'] = 'error';
            $res['err_message'] = $e->getMessage();
        }

        return new Response(String::jsonEncode($res));
    }

    public function proceedAjax()
    {
        $params = $this->request->request->all();
        $types = array('region', 'street');

        try {
            if(!isset($params['type']))
                throw new \Exception('you must set the type');
            elseif(!\in_array($params['type'], $types))
                throw new \Exception('wrong type');
            if(!isset($params['args']['query']))
                throw new \Exception('you must set the query');

            $res = $this->repo[$params['type']]->getByQuery($params['args']);
            $res['status'] = 'ok';
        } catch(\Exception $e) {
            $res['status'] = 'error';
            $res['err_message'] = $e->getMessage();
        }

        return new Response(String::jsonEncode($res));
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
        
        return new Response(\n3b\Bundle\Util\String::jsonEncode(array('status' => 'ok', 'adds' => $addsStr, 'region' => array('code' => $region->getCode(), 'title' => $region->getTitle()), 'street' => array('code' => $street->getCode(), 'title' => $street->getTitle()))));
    }
}