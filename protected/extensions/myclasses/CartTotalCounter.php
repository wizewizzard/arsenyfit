<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.04.2016
 * Time: 19:44
 */
class CartTotalCounter
{
    public function countCartTotal()
    {
        if (isset(Yii::app()->session['cart_items'])) {
            $array = unserialize(Yii::app()->session['cart_items']);
            $item_totals = array();
            if (!empty($array)) {
                $ids = array();
                foreach ($array as $key => $value) $ids[] = $key;
                $criteria = new CDbCriteria;
                $criteria->addInCondition("id", $ids);
                $items = Item::model()->findAll($criteria);

                $total = 0.0;
                $total_weighted=0.0;
                $weighted_count = 0;
                $total_weight = 0.0;
                $total_under_offers = 0.0;
                $total_under_offers_weighted = 0.0;
                //$der = Yii::app()->config->get('GLOBAL.DOLLAR_EXCHANGE_RATE');
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $item_total = $item->price * $array[$item->id];
                        $item_totals[$item->id]['total'] = $item_total;
                        $total += $item_total;
                        if (isset($item->weight) && $item->weight > 0) {
                            $weighted_count++;
                            $total_weighted += $item_total;
                            $total_weight+=$item->weight;
                        }
                    }
                    //echo $total;
                    foreach ($items as $item) {
                        if (!empty($item->offers)) {
                            foreach ($item->offers as $offer) {
                                //echo $offer->isSuitable($item, $array[$item->id], $total);
                                $item_total = $offer->apply($item, $array[$item->id], $total);
                                $total_under_offers += $item_total;
                                $item_totals[$item->id]['total_under_offer'] = $item_total;
                                if(isset($item->weight) && $item->weight > 0) {
                                    $total_under_offers_weighted += $item_total;
                                }
                            }
                        } else {
                            $total_under_offers += $item->price * $array[$item->id];
                            if(isset($item->weight) && $item->weight > 0) {
                                $total_under_offers_weighted += $item->price * $array[$item->id];
                            }
                        }
                    }
                    return array('item_totals' => $item_totals,
                        'total' => $total,
                        'total_under_offers' => $total_under_offers,
                        'total_weighted' => $total_weighted,
                        'count_weighted' => $weighted_count,
                        'total_weight' => $total_weight,
                        'total_under_offers_weighted' => $total_under_offers_weighted
                        );
                } else return null;
            } else return null;
        }
        else return null;
    }

}