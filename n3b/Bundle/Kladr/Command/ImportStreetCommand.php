<?php

namespace n3b\Bundle\Kladr\Command;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use n3b\Bundle\Kladr\Entity\KladrStreet;

class ImportStreetCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('kladr:import:street')
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
        $em = $this->em = $this->getEntityManager('default');

        $this->truncate();

        $db_path = __DIR__ . '/../Resources/KLADR/STREET.DBF';
        $db = dbase_open($db_path, 0) or die("Error! Could not open dbase database file '$db_path'.");
        $record_numbers = dbase_numrecords($db);

        $batchSize = $input->getOption('batch');
        for ($i = 1; $i <= $record_numbers; $i++) {
            $row = dbase_get_record_with_names($db, $i);

            $street = new KladrStreet();
            $street->setTitle(trim(iconv('cp866', 'utf8', $row['NAME'])));

            $code = trim($row['CODE']);
            if(substr($code, -2) != '00')
                continue;

            $code = substr($code, 0, -2);
            $street->setId($code);
            $street->setParentCode(intval(str_pad(substr($code, 0, -4), 11, '0', STR_PAD_RIGHT)));

            $street->setZip(trim($row['INDEX']));
            $street->setOcatd(trim($row['OCATD']));
            $street->setSocr(trim(iconv('cp866', 'utf8', $row['SOCR'])));

            $em->persist($street);

            if (($i % $batchSize) == 0) {
                $em->flush();
                $em->clear();
                echo 'Inserted ', $i, ' records',"\n";
            }
        }

        echo 'Success', "\n";

        echo 'Assign parents', "\n";
        $this->deleteNotLinkedElements();
        $this->updateParents();
        echo 'Success', "\n";
    }

    public function truncate()
    {
        $sql = "TRUNCATE TABLE KladrStreet";
        $stmt = $this->em->getConnection()->prepare($sql);

        return $stmt->execute();
    }

    public function updateParents()
    {
        $sql = "
            UPDATE KladrStreet s
            SET s.parent_id = s.parentCode";
        $stmt = $this->em->getConnection()->prepare($sql);

        return $stmt->execute();
    }

    public function deleteNotLinkedElements()
    {
        $sql = "
            DELETE FROM KladrStreet s
            WHERE s.parentCode NOT IN
                (SELECT id FROM KladrRegion)";
        $stmt = $this->em->getConnection()->prepare($sql);

        return $stmt->execute();
    }
}
