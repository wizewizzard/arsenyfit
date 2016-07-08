<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 21.03.2016
 * Time: 15:10
 */
class GalleryController extends Controller
{

    public function actionIndex($page = 1)
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/jquery.mCustomScrollbar.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/gallery.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/gallery.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/yoxview/yoxview-init.js' );

        $gallery_images_per_page = Yii::app()->config->get('IMAGES.VERTNUM_GALLERYPAGE') * Yii::app()->config->get('IMAGES.HORIZNUM_GALLERYPAGE');

        $criteria = new CDbCriteria;
        $criteria->order = 't.date DESC';
        $criteria->limit = $gallery_images_per_page;
        $criteria->offset = ($page - 1) * $gallery_images_per_page;
        $models = GalleryImage::model()->findAll($criteria);
        $images_count = GalleryImage::model()->count();
        if ($images_count % $gallery_images_per_page != 0)
            $pages_num = 1 + intval(GalleryImage::model()->count() / $gallery_images_per_page);
        else
            $pages_num = GalleryImage::model()->count() / $gallery_images_per_page;
        if (!empty($models))
            $this->render('index', array(
                'images' => $models,
                'cur_page' => $page,
                'pages_num' => $pages_num

            ));
        else  throw new CHttpException(404, 'The requested page does not exist');
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}