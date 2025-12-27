<?php

namespace Commerce\Services;

use WC_Product;

class BadgeService {

    protected int $lowStockThreshold = 3;
    protected int $newProductDays = 15;

    /**
     * Main Entry Point
     */
    public function getBadgesForProduct(int $productId): array {
     $product = wc_get_product($productId);

     if(!$product){
        return [];
     }

     $badges = [];

     if($this->isOnSale($product)){
        $badges[] = $this->saleBadge();
     }

     if($this->isLowStock($product)){
        $badges[] = $this->lowStockBadge($product);
     }

     if($this->isNewProduct($product)){
       $badges[] = $this->newBadge();
     }


     return $this->sortBadges($badges);


    }


    /**
     * Badge Rules (logic)
     */
     Protected function isOnSale(WC_Product $product): bool{
      return $product->id_on_sale();  
     }

     protected function isLowStock(WC_Product $product): bool{

        if(!$product->managing_stock()){
            return false;
        }

      return $product->get_stock_quantity() <= $this->lowStockThreshold;
     }

     protected function isNewProduct(WC_Product $prodcut): bool{

        $created = $prodcut->get_date_created();
        
        if(!$created){
            return false;
        }

        $daysOld = (time() - $created->getTimestamp()) / DAY_IN_SECONDS;

        return $daysOld <= $this->newProductDays;
     }


     /**
      * Badge Definitions (data)
      */

      Protected function saleBadge(): array{
        return [
            'type' => 'sale',
            'label' => 'sale',
            'priority' => 10,
        ];
      }

      protected function lowStockBadge(WC_Product $product): array{
        return [
            'type' => 'low-stock',
            'lable' => 'Only ' . $product->get_stock_quantity() . 'left',
            'priority' => 20,
        ];
      }

      protected function newBadge():array{
        return [
            'type' => 'new',
            'label' => 'New',
            'priority' => 30,
        ];
      }


      /**
       * Utilites
       */

      Protected function sortBadges(array $badges): array{

        usort($badges, function($a, $b){
          return $a['priority'] <=> $b['priority'];
        }); 

        return $badges;

      }



}