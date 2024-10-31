<?php

namespace rentsyst\admin\components;

use rentsyst\includes\Rentsyst_CatalogFilter;
use WP_Widget;

class Rentsyst_Filter_Widget extends WP_Widget {

	function __construct() {
		// Запускаем родительский класс
		parent::__construct(
			'rentstyst_filter', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: my_widget
			'Rentsyst filter',
			array('description' => 'Display filter for catalog vehicles')
		);

	}

	// Вывод виджета
	function widget( $args, $instance ){
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if( $title )
			echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="rentsyst-wrap-filters rentsyst-widget">';
		echo   Rentsyst_CatalogFilter::display(true);
		echo '</div>';

		echo $args['after_widget'];
	}

	// Сохранение настроек виджета (очистка)
	function update( $new_instance, $old_instance ) {
	}

	// html форма настроек виджета в Админ-панели
	function form( $instance ) {
	}


}

