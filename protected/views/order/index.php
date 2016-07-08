<div class="page_title">your orders<div class="underline"></div></div>
<div id="logout_link"><?=CHtml::link('Logout', 'logout', array('class' => 'global_button')); ?></div>
<div id="orders_container">
    <?php
    switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
        case 'DOL': $currency = ' $'; break;
        case 'RUB': $currency = ' руб.'; break;
        case 'EUR': $currency = ' eur'; break;
        default: $currency = ' $'; break;
    }
    if(count($orders) > 0 ){
        foreach($orders as $order){?>
        <div class="order_container">
            <dl class="common_info">
                <div class="info_title">Common</div>
                <dt>Order ID</dt>
                <dd><?= $order->id; ?></dd>
                <dt>Date</dt>
                <dd><?= $order->date; ?></dd>
                <dt>First name</dt>
                <dd><?= $order->first_name; ?></dd>
                <dt>Last name</dt>
                <dd><?= $order->last_name; ?></dd>
                <dt>Comments</dt>
                <dd><?= $order->commentaries; ?></dd>
            </dl>
            <dl class="shipping_info">
                <div class="info_title">Shipping</div>
            <?php if($order->shipping_needed){?>
                <dt>Country</dt>
                <dd><?= $order->shipping->idCountry->country_name_en; ?></dd>
                <dt>Zip code</dt>
                <dd><?= $order->shipping->zip_code; ?></dd>
                <dt>City</dt>
                <dd><?= $order->shipping->city; ?></dd>
                <dt>State</dt>
                <dd><?= $order->shipping->state; ?></dd>
                <dt>Address</dt>
                <dd><?= $order->shipping->address; ?></dd>
                <dt>Cost</dt>
                <dd><?= $order->shipping->cost; ?><?= $currency ?></dd>
            <?php }
            else{ ?>
                <dt>Shipping</dt>
                <dd>Not required.</dd>
            <?php }?>
            </dl>
            <dl class="items_info">
                <div class="info_title">Items</div>
                <?php
                $items = $order->orderItems;
                foreach($items as $item){?>
                    <dt><?= $item->idItem->title ?></dt>
                    <dd>Count: <?= $item->count ?> Tot.: <?= $item->total ?><?= $currency ?></dd>
                <?php
                }
                ?>
            </dl>
            <dl class="status_info">
                <div class="info_title">Extra</div>
                <dt>Status</dt>
                <dd><?= $order->orderProgress->idStage->title ?></dd>
                <dt>Updated(utc)</dt>
                <dd><?= $order->orderProgress->changed; ?></dd>
                <dt>Comments</dt>
                <dd><?= (isset($order->orderProgress->commentary)) ? $order->orderProgress->commentary : 'none' ?></dd>
                <dt>Total cost</dt>
                <dd><?= $order->shipping->cost + $order->total ?><?= $currency ?></dd>
            </dl>
            <?php if($order->orderProgress->idStage->abbr == 'pending_payment'){?>
            <div class="delete_link_container">
                <?= CHtml::link('Delete', '#', array('submit'=>array('/order/deleteorder', "id"=>$order->id),'confirm' => 'Delete this order?', 'class'=>'global_button')); ?>
            </div>
                <?php } ?>
        </div>
        <?php
        }
    }
    else{?>
    <div class="error main_message">
        You have no orders.
    </div>
    <?php
    }
    ?>
</div>