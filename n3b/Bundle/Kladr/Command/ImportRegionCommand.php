<?php

namespace n3b\Bundle\Kladr\Command;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use n3b\Bundle\Kladr\Entity\Region;

class ImportRegionCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('kladr:import:region')
            ->addOption('batch', null, InputOption::VALUE_OPTIONAL, 'The batch size to insert. This requires dbase.so extension.', 2000)
            ->setDescription('Imports KLADR data into mysql')
            ->setHelp(<<<EOT
nothing here
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'Started at ', date('H:i:s'), "\n";
        $em = $this->getEntityManager('default');

        $db_path = __DIR__ . '/../Resources/KLADR/KLADR.DBF';
        $db = dbase_open($db_path, 0) or die("Error! Could not open dbase database file '$db_path'.");
        $record_numbers = dbase_numrecords($db);

        $batchSize = $input->getOption('batch');
        for ($i = 1; $i <= $record_numbers; $i++) {
            $row = dbase_get_record_with_names($db, $i);
            $region = new Region();
            $region->setTitle(trim(iconv('cp866', 'utf8', $row['NAME'])));

            $code = trim($row['CODE']);

            if(substr($code, -2) != '00')
                continue;
            
            $code = substr($code, 0, -2);
            $region->setCode(str_pad($code, 11, '0', STR_PAD_LEFT));

            if(substr($code, 8) != '000')
                $region->setLevel(4);
            elseif(substr($code, 5, 3) != '000')
                $region->setLevel(3);
            elseif(substr($code, 2, 3) != '000')
                $region->setLevel(2);
            else
                $region->setLevel(1);

            $parentCode = substr($code, 0, 0 - 3 * (5 - $region->getLevel()));

            $region->setParentCode(strlen($parentCode) ? str_pad($parentCode, 11, '0', STR_PAD_RIGHT) : null);

            $region->setZip(trim($row['INDEX']));
            $region->setOcatd(trim($row['OCATD']));
            $region->setSocr(trim(iconv('cp866', 'utf8', $row['SOCR'])));

            $em->persist($region);

            if (($i % $batchSize) == 0) {
                $em->flush();
                $em->clear();
                echo 'Inserted ', $i, ' records',"\n";
            }
        }
        echo 'Success', "\n";

        echo 'Assign parents', "\n";
        $em->getRepository('n3b\Bundle\Kladr\Entity\Region')->setParents();
        echo 'Success', "\n";
    }
}
