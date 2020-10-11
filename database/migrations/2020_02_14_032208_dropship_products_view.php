<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropshipProductsView extends Migration
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
        VIEW `dropship_products` AS
            SELECT 
                `products`.`id` AS `id`,
                `products`.`sku` AS `sku`,
                `products`.`product_type` AS `product_type`,
                `products`.`affiliate_link` AS `affiliate_link`,
                `products`.`user_id` AS `user_id`,
                `products`.`category_id` AS `category_id`,
                `products`.`subcategory_id` AS `subcategory_id`,
                `products`.`childcategory_id` AS `childcategory_id`,
                `products`.`attributes` AS `attributes`,
                `products`.`name` AS `name`,
                `products`.`slug` AS `slug`,
                `products`.`photo` AS `photo`,
                `products`.`thumbnail` AS `thumbnail`,
                `products`.`file` AS `file`,
                `products`.`size` AS `size`,
                `products`.`size_qty` AS `size_qty`,
                `products`.`size_price` AS `size_price`,
                `products`.`color` AS `color`,
                `products`.`price` AS `price`,
                `products`.`publish_price` AS `publish_price`,
                `products`.`previous_price` AS `previous_price`,
                `products`.`publish_previous_price` AS `publish_previous_price`,
                `products`.`details` AS `details`,
                `products`.`stock` AS `stock`,
                `products`.`weight` AS `weight`,
                `products`.`policy` AS `policy`,
                `products`.`status` AS `status`,
                `products`.`views` AS `views`,
                `products`.`tags` AS `tags`,
                `products`.`features` AS `features`,
                `products`.`colors` AS `colors`,
                `products`.`product_condition` AS `product_condition`,
                `products`.`ship` AS `ship`,
                `products`.`is_meta` AS `is_meta`,
                `products`.`meta_tag` AS `meta_tag`,
                `products`.`meta_description` AS `meta_description`,
                `products`.`youtube` AS `youtube`,
                `products`.`type` AS `type`,
                `products`.`license` AS `license`,
                `products`.`license_qty` AS `license_qty`,
                `products`.`link` AS `link`,
                `products`.`platform` AS `platform`,
                `products`.`region` AS `region`,
                `products`.`licence_type` AS `licence_type`,
                `products`.`measure` AS `measure`,
                `products`.`featured` AS `featured`,
                `products`.`best` AS `best`,
                `products`.`top` AS `top`,
                `products`.`hot` AS `hot`,
                `products`.`latest` AS `latest`,
                `products`.`big` AS `big`,
                `products`.`trending` AS `trending`,
                `products`.`sale` AS `sale`,
                `products`.`created_at` AS `created_at`,
                `products`.`updated_at` AS `updated_at`,
                `products`.`is_discount` AS `is_discount`,
                `products`.`discount_date` AS `discount_date`,
                `products`.`whole_sell_qty` AS `whole_sell_qty`,
                `products`.`whole_sell_discount` AS `whole_sell_discount`,
                `products`.`is_catalog` AS `is_catalog`,
                `products`.`catalog_id` AS `catalog_id`,
                `products`.`status_verified` AS `status_verified`,
                `products`.`verified_by` AS `verified_by`,
                `products`.`verified_date` AS `verified_date`,
                `products`.`affiliate_product_id` AS `affiliate_product_id`,
                `products`.`is_highlight` AS `is_highlight`,
                `u_dropshipper`.`shop_name` AS `shop_name`,
                `u_dropshipper`.`id` AS `dropshipper_id`,
                `u_dropshipper`.`slug_shop_name` AS `slug_shop_name`,
                `u_vendor`.`shop_name` AS `vendor_shop_name`,
                `u_vendor`.`id` AS `vendor_id`,
                `u_vendor`.`slug_shop_name` AS `vendor_slug_shop_name`,
                `categories`.`rate_dropship` AS `rate_dropship`
            FROM
                ((((((`dropships` `a`
                JOIN `products` ON (`a`.`product_id` = `products`.`id`))
                JOIN `categories` ON (`products`.`category_id` = `categories`.`id`))
                JOIN `user_subscriptions` `dropshipper` ON (`a`.`user_id` = `dropshipper`.`user_id`
                    AND `dropshipper`.`status` = 1
                    AND `dropshipper`.`is_dropship` = '1'))
                JOIN `user_subscriptions` `vendor` ON (`a`.`vendor_id` = `vendor`.`user_id`))
                JOIN `users` `u_dropshipper` ON (`dropshipper`.`user_id` = `u_dropshipper`.`id`
                    AND `u_dropshipper`.`is_vendor` = 2))
                JOIN `users` `u_vendor` ON (`vendor`.`user_id` = `u_vendor`.`id`
                    AND `u_vendor`.`is_vendor` = 2))        
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
