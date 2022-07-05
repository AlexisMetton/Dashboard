<?php
 namespace App\Entity;

 class PropertySearch{

    /**
     * @var int|null
     */

    private $maxPrice;


        /**
     * @var int|null
     */

    private $minPrice;

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @return int|null
     */
    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return PropertySearch
     */

    public function setMaxPrice(int $maxPrice): PropertySearch{
        $this->maxPrice = $maxPrice;
        return $this;
    }


        /**
     * @param int|null $minPrice
     * @return PropertySearch
     */

    public function setMinPrice(int $minPrice): PropertySearch{
        $this->minPrice = $minPrice;
        return $this;
    }

 }