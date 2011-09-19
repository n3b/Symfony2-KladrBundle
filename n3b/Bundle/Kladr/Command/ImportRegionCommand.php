<?php

namespace n3b\Bundle\Kladr\Command;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use n3b\Bundle\Kladr\Entity\KladrRegion;

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
        $em = $this->em = $this->getEntityManager('default');

        $this->truncate();

        $db_path = __DIR__ . '/../Resources/KLADR/KLADR.DBF';
        $db = dbase_open($db_path, 0) or die("Error! Could not open dbase database file '$db_path'.");
        $record_numbers = dbase_numrecords($db);

        $batchSize = $input->getOption('batch');
        for ($i = 1; $i <= $record_numbers; $i++) {
            $row = dbase_get_record_with_names($db, $i);
            $region = new KladrRegion();
            $region->setTitle(trim(iconv('cp866', 'utf8', $row['NAME'])));

            $code = trim($row['CODE']);

            if(substr($code, -2) != '00')
                continue;
            
            $code = substr($code, 0, -2);
            $region->setId($code);

            if(substr($code, 8) != '000')
                $region->setLevel(4);
            elseif(substr($code, 5, 3) != '000')
                $region->setLevel(3);
            elseif(substr($code, 2, 3) != '000')
                $region->setLevel(2);
            else
                $region->setLevel(1);

            $parentCode = substr($code, 0, 0 - 3 * (5 - $region->getLevel()));

            $region->setParentCode(strlen($parentCode) ? intval(str_pad($parentCode, 11, '0', STR_PAD_RIGHT)) : null);

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
        $em->flush();
        echo 'Inserted ', $i, ' records',"\n";
        echo 'Success', "\n";

        echo 'Deleting dead links', "\n";
        $this->deleteNotLinkedElements();
        echo 'Assigning parents', "\n";
        $this->updateParents();
        echo 'Setting full parent title', "\n";
        $this->setFullParentTitle();
        echo 'Success', "\n";
    }

    public function truncate()
    {
        $sql = "TRUNCATE TABLE KladrRegion";
        $stmt = $this->em->getConnection()->prepare($sql);
        
        return $stmt->execute();
    }

    public function updateParents()
    {
        $sql = "
            UPDATE KladrRegion r
            SET r.parent_id = r.parentCode";
        $stmt = $this->em->getConnection()->prepare($sql);

        return $stmt->execute();
    }

    public function setFullParentTitle()
    {
        for($i = 1; $i <= 3; $i++) {
            $sql = "
                UPDATE KladrRegion r
                INNER JOIN KladrRegion p ON p.id = r.parent_id AND p.level = $i
                SET r.fullParentTitle = CONCAT(', ', p.title, ' ', LOWER(p.socr)";
                $sql .=  ($i > 1 ? ", p.fullParentTitle)" : ")");

            $stmt = $this->em->getConnection()->prepare($sql);

            $stmt->execute();
        }
    }

    public function deleteNotLinkedElements()
    {
        $sql = "
            DELETE FROM KladrRegion WHERE id IN (
                SELECT * FROM (
                    SELECT id FROM KladrRegion
                    WHERE parentCode NOT IN (
                        SELECT id FROM KladrRegion
                    )
                ) AS t
            )";
        $stmt = $this->em->getConnection()->prepare($sql);

        return $stmt->execute();
    }
}
