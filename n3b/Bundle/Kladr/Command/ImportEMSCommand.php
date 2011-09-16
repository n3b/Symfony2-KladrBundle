<?php

namespace n3b\Bundle\Kladr\Command;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use n3b\Bundle\Kladr\Entity\Region;
use Doctrine\ORM\Query\ResultSetMapping;

class ImportEMSCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('kladr:import:ems')
            ->setDescription('Imports EMS data into KLADR tables')
            ->setHelp(<<<EOT
nothing here
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'Started at ', date('H:i:s'), "\n";
        $em = $this->getEntityManager('default');

        $result = \json_decode(\file_get_contents('http://emspost.ru/api/rest/?method=ems.get.locations&type=cities'));

        foreach($result->rsp->locations as $city) {
            $sql = "
            UPDATE Region r
            SET r.emsTo = '".$city->value."'
            WHERE r.socr LIKE 'г' AND r.title LIKE '".$city->name."'
            ";

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
        }

        $result = \json_decode(\file_get_contents('http://emspost.ru/api/rest/?method=ems.get.locations&type=regions'));
        foreach($result->rsp->locations as $region) {

            // небольшие фиксы для синхронизации отличающихся наименований в кладр и емс
            $region->name = trim(str_replace('Республика', '', $region->name));
            if($region->name == 'Сев.Осетия-Алания')
                $region->name = 'Алания';

            $sql = "
            UPDATE Region r
            SET r.emsTo = '".$region->value."'
            WHERE r.level = 1 AND r.title REGEXP '".implode('|', explode(' ', $region->name))."'
            ";

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
        }

        echo 'Success at ', date('H:i:s'), "\n";
    }
}
