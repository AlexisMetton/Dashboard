<?php
 namespace App\Entity;

 class PropertySearch{

    /**
     * @var int|null
     */

    private $maxPrice;

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return PropertySearch
     */

    public function setMaxPrice(int $maxPrice): PropertySearch{
        $this->maxPrice = $maxPrice;
        return $this;
    }

 }