<?php
/* @var $this OrderController */
/* @var $model Order */
$shipping = $model->shipping;
$items = $model->orderItems;
$stage = $model->orderProgress;
switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
	case 'DOL': $currency = ' $'; break;
	case 'RUB': $currency = ' руб.'; break;
	case 'EUR': $currency = ' eur'; break;
	default: $currency = ' $'; break;
}
?>

<h1>View Order #<?php echo $model->id; ?></h1>
<div class="order_title">Общая информация</div>
<?php
$total_cost = floatval($model->total) + floatval($shipping->cost);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'date',
		array(
			'name'=>'total',
			//'type' => 'html',
			'value'=> $total_cost.$currency
		),
		'first_name',
		'last_name',
		'email',
		'shipping_needed',
		'commentaries',
		'key',
		'payment_status',
	),
)); ?>
<div class="order_title">Доставка</div>
<?php
if(isset($shipping)) {
	$this->widget('zii.widgets.CDetailView', array(
		'data' => $shipping,
		'attributes' => array(
			'id',
			'id_order',
			'address',
			'apt_num',
			'state',
			'zip_code',
			'city',
			array(
				'name' => 'country',
				'value' => $shipping->idCountry->country_name_en,
			),
			array(
				'name' => 'cost',
				'value' => $shipping->cost.$currency,
			),
		),
	));
}
else{ ?>
<div class="additional_info">Доставка не требуется.</div>
<?}
if(isset($items)) {
	$dataProvider = new CArrayDataProvider('OrderItem');
	$dataProvider->setData($items);?>
	<div class="order_title">Товары</div>
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'order-grid',
		'dataProvider' => $dataProvider,
		'columns' => array(
			'id',
			'id_order',
			array(
				'name' => 'Item',
				'value' => 'CHtml::link($data->idItem->title, array("/shop/item", "id" => $data->idItem->id))',
				'type' => 'raw'
			),
			'count',
			'total'
		),
	));
}
else{ ?>
	<div class="additional_info">Данные о заказанных товарах отстутсвуют.</div>
<?php
}
?>
<div class="order_title">Статус</div>
<?php
if(isset($stage)) {
	$this->widget('zii.widgets.CDetailView', array(
		'data' => $stage,
		'attributes' => array(
			'id',
			'id_order',
			array(
				'name' => 'status',
				'value' => $stage->idStage->title,
			),
			'changed',
			'commentary'
		),
	));
}
?>

