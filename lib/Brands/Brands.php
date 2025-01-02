<?php
class Brands
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
    
    /**
     * Set friendly columns\' names to order tables\' entries
     */
    public function setOrderingValues()
    {
        $ordering = [
            'id' => 'ID',
            'brand_name' => 'brand_name',
            'created_at' => 'Created at'
        ];

        return $ordering;
    }
}
?>
