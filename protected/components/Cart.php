<?php
class Cart extends CApplicationComponent
{
    public function init()
    {
       /* if (isset(Yii::app()->session['cart_items']) && count(Yii::app()->session->get('cart_items')) > 0){
            $cart_array = unserialize(Yii::app()->session['cart_items']);
        }*/
        parent::init();
    }
    public function get(){
        if(!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items')) == 0) return null;
        else return unserialize(Yii::app()->session['cart_items']);
    }
    public function clear(){
        if(isset(Yii::app()->session['cart_items'])) unset(Yii::app()->session['cart_items']);
    }
    public function add($id, $count =1){
        $criteria = new CDbCriteria;
        $criteria->condition = 'id=:id_i';
        $criteria->params=array(':id_i' => $id);
        $item = Item::model()->find($criteria);
        //while($sid-- > 0){
        if(!empty($item)) {
            if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items')) == 0) {
                $array[$id] = $count;
                Yii::app()->session->add("cart_items", serialize($array));
                return 1;
            } else {
                $array = unserialize(Yii::app()->session['cart_items']);
                if(isset($array[$id])) return 2;
                else{
                    $array[$id] = $count;
                    Yii::app()->session->add("cart_items", serialize($array));
                }
            }
        }
        else return 0;
    }
    public function remove($id){
        $id = Yii::app()->request->getPost('id');
        $array = array();
        $array = unserialize(Yii::app()->session['cart_items']);
        if(isset($array[$id])){
            unset($array[$id]);
            if(count($array) == 0) Yii::app()->session->remove('cart_items');
            else Yii::app()->session->add("cart_items", serialize($array));
            return 1;
        }
        else{
            return 0;
        }
    }
    public function update($id, $count){
        $criteria = new CDbCriteria;
        $criteria->condition = 'id=:id_i';
        $criteria->params=array(':id_i' => $id);
        $item = Item::model()->find($criteria);

        if($count < 0){ return 0;}
        if(!empty($item)){
            if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items'))==0)
            {
                return 0;
            }
            else {
                $array = array();

                $array = unserialize(Yii::app()->session['cart_items']);
                if(isset($array[$id])) { $array[$id]=$count; }
                else return 0;
                Yii::app()->session->add("cart_items", serialize($array));
            }
            return 1;
        }
        else{
            return 0;
        }
    }
    public function total()
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
    public function isEmpty(){
        if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items'))==0) return true;
        return false;
    }

}