<?php


namespace customCommandBundle\Command;

use Carbon\Carbon;
use League\Csv\Exception;
use League\Csv\UnavailableStream;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Mega;
use Pimcore\Model\DataObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Model\DataObject\ClassDefinition;
use League\Csv\Reader;
use Pimcore\Model\DataObject\Data\GeoCoordinates;


class CsvImportObjectsCommand extends  Command
{
    protected static $defaultName = 'custom:create-data-objects';

    protected function configure()
    {
        $this
            ->setDescription('Create Mega data objects from a CSV file')
            ->setHelp('This command reads a CSV file and creates Mega data objects from it.');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvPath = 'bundles/customCommandBundle/public/csv/test.csv';

        $csv = Reader::createFromPath($csvPath);
        $csv->setHeaderOffset(0);

        $parentObject = DataObject::getById(113);

        if (!$parentObject) {
            throw new \Exception('Parent object not found.');
        }

        foreach ($csv->getRecords() as $record) {
            $key = $record['key'];
            $dataObject = Mega::getByPath('/csv/' . $key);

            $data = [];

            foreach ($record as $field => $value) {
                $data[$field] = $this->setValue($field, $value);
//                echo ($field);
            }
            if ($dataObject) {
                $dataObject->setValues($data);
            } else {
                $dataObject = Mega::create($data);
            }

            $dataObject = Mega::create($data);

            $dataObject->setParentId($parentObject->getId());
            $dataObject->save();
        }

        $output->writeln('Data objects created successfully.');

        return 0;
    }

    /**
     * Dynamically cast values based on field types in Pimcore class
     *
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    private function setValue(string $field, mixed $value): mixed
    {

        if ($field === 'date') {
            return Carbon::parse($value);
        } elseif ($field === 'number') {
            return (float) $value;
        } elseif ($field === 'location') {
            $locationData = explode(',', $value);
            if (count($locationData) === 2) {
                $latitude = (float) $locationData[0];
                $longitude = (float) $locationData[1];
//                echo ("latititude:".$latitude);
                return new GeoCoordinates($latitude, $longitude);
            } else {
                return null;
            }
        }  else {
            return $value;
        }
    }

//    protected function execute(InputInterface $input, OutputInterface $output)
//    {
//        $csvPath = 'bundles/customCommandBundle/public/csv/test.csv'; // Path to the provided CSV file
//        $imageDirectory = '/bundles/customCommandBundle/public/images/'; // Directory containing images
//
//        // Parse the existing CSV file
//        $csv = Reader::createFromPath($csvPath, 'r');
//        $csv->setHeaderOffset(0);
//        $records = $csv->getRecords();
////        echo $csv;
//
//        foreach ($records as $record) {
//            // Create a new "Mega" data object
//            $megaObject = \Pimcore\Model\DataObject\Mega::create();
//
//            // Set attribute values from the CSV data
//            $megaObject->setText($record['name']);
//            $megaObject->setDesc($record['desc']);
//            $megaObject->setNumber((float) $record['price']);
//            $megaObject->setIschecked((bool) $record['ischecked']);
//
////             Handle the image attribute
//            $imageFilename = $record['image'];
//            $imagePath = $imageDirectory . $imageFilename;
//
////          Check if the image file exists
//            if (file_exists($imagePath)) {
//                // Create an asset from the image file
//                $asset = new \Pimcore\Model\Asset\Image();
//                $asset->setFilename($imageFilename);
//                $asset->setData(file_get_contents($imagePath));
//                $asset->save();
//
//                // Set the image attribute
//                $megaObject->setImage($asset);
//            }
//
////             Save the data object
//            $megaObject->save();
//            try {
//                $megaObject->save();
//            } catch (\Exception $e) {
//                // Handle the exception
//                echo 'Error: ' . $e->getMessage();
//                // Log the exception or take other appropriate actions
//            }
//
//        }
//
//        $output->writeln('Mega data objects created successfully.');
//    }`



}
