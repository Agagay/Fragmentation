<?php
declare(strict_types=1);

namespace ChuEv;


class Fragmentation
{
    private $number;
    private $count;

    public function __construct(int $number, int $count)
    {
        if ($number < $count && $count < 2)
            throw new \Exception('Incorrect input data');

        $this->number = $number;
        $this->count = $count;
    }

    public function complete(): array
    {
        $set = $this->init();
        $sets[0] = $set;

        while ($set = $this->getSets($set)) {
            $sets[] = $set;
        }

        return $sets;
    }

    private function init(): array
    {
        $set[0] = $this->number - $this->count + 1;
        for ($i = 1; $i < $this->count; $i++) {
            $set[$i] = 1;
        }

        return $set;
    }

    private function getSets(array $set): array
    {
        if ($set[1] >= $set[0] - 1) {
            try {
                $set = $this->getNewSet($set);
            } catch (EndException $e) {
                $set = [];
            }
        } else {
            $set = $this->modifySet($set);
        }
        return $set;
    }

    private function modifySet(array $set): array
    {
        $set[0] = $set[0] - 1;
        $set[1] = $set[1] + 1;

        return $set;
    }

    private function getNewSet(array $set): array
    {
        $adjustmentIndex = 2;
        $newFirstValue = $set[0] + $set[1] - 1;

        while ($set[$adjustmentIndex] >= $set[0] - 1) {
            $newFirstValue = $newFirstValue + $set[$adjustmentIndex];
            $adjustmentIndex++;
        }

        if ($adjustmentIndex > $this->count - 1) {
            throw new EndException();
        } else {
            $increaseAdjustmentValue = $set[$adjustmentIndex] + 1;
            $set[$adjustmentIndex] = $increaseAdjustmentValue;
            $adjustmentIndex = $adjustmentIndex - 1;
        }

        while ($adjustmentIndex > 0) {
            $set[$adjustmentIndex] = $increaseAdjustmentValue;
            $newFirstValue = $newFirstValue - $increaseAdjustmentValue;
            $adjustmentIndex = $adjustmentIndex - 1;
        }
        $set[0] = $newFirstValue;

        return $set;
    }
}