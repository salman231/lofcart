<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_StorePickup
 */


namespace Amasty\StorePickup\Model;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Psr\Log\LoggerInterface;

class Sample
{
    const FIXTURES = [
        'methods' => 'Amasty_StorePickup::fixtures/methods.csv',
        'rates' => 'Amasty_StorePickup::fixtures/rates.csv'
    ];

    /**
     * @var \Magento\Framework\Setup\SampleData\FixtureManager
     */
    private $fixtureManager;

    /**
     * @var \Magento\Framework\File\Csv
     */
    private $csvReader;

    /**
     * @var \Amasty\StorePickup\Model\MethodFactory
     */
    private $methodFactory;

    /**
     * @var \Amasty\StorePickup\Model\RateFactory
     */
    private $rateFactory;

    /**
     * @var \Amasty\StorePickup\Model\ResourceModel\Method
     */
    private $methodResource;

    /**
     * @var \Amasty\StorePickup\Model\ResourceModel\Rate
     */
    private $rateResource;

    /**
     * @var \Magento\Framework\Filesystem\DriverInterface
     */
    private $driver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        SampleDataContext $sampleDataContext,
        MethodFactory $methodFactory,
        RateFactory $rateFactory,
        ResourceModel\Method $methodResource,
        ResourceModel\Rate $rateResource,
        \Magento\Framework\Filesystem\DriverInterface $driver,
        LoggerInterface $logger
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->methodFactory = $methodFactory;
        $this->rateFactory = $rateFactory;
        $this->methodResource = $methodResource;
        $this->rateResource = $rateResource;
        $this->driver = $driver;
        $this->logger = $logger;
    }

    public function install()
    {
        foreach (self::FIXTURES as $type => $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);

            try {
                $isFileExists = $this->driver->isExists($fileName);
            } catch (FileSystemException $e) {
                $this->logger->error($e);
                $isFileExists = false;
            }

            if (!$isFileExists) {
                continue;
            }

            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                if ($type === 'rates') {
                    $model = $this->rateFactory->create();
                    $resource = $this->rateResource;
                } elseif ($type === 'methods') {
                    $model = $this->methodFactory->create();
                    $resource = $this->methodResource;
                } else {
                    continue;
                }

                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }

                $model->addData($data);
                $resource->save($model);
            }
        }
    }
}
