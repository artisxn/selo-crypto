<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ViewHighlightProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE        
        VIEW `highlight_product` AS
            SELECT 
                `b`.`id` AS `user_id`,
                `b`.`shop_name` AS `shop_name`,
                `c`.`allowed_products` AS `allowed_products`,
                (SELECT 
                        COUNT(`products`.`id`)
                    FROM
                        `products`
                    WHERE
                        `products`.`is_highlight` = '1'
                            AND `products`.`user_id` = `b`.`id`) AS `total_highlight_product`,
                `c`.`allowed_products` - (SELECT 
                        COUNT(`products`.`id`)
                    FROM
                        `products`
                    WHERE
                        `products`.`is_highlight` = '1'
                            AND `products`.`user_id` = `b`.`id`) AS `sisa_highlight_product`
            FROM
                ((`edcecommerce`.`users` `b`
                LEFT JOIN `edcecommerce`.`products` `a` ON ((`a`.`user_id` = `b`.`id`)))
                JOIN `edcecommerce`.`user_subscriptions` `c` ON ((`c`.`user_id` = `b`.`id`)))
            GROUP BY `b`.`id`        
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
