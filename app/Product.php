<?php

namespace App;

use App\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class Product extends Model
{
    // get all product
    public static function get_products($query = [])
    {
        $search = isset($query['search']) ? $query['search'] : '';
        $limit = isset($query['limit']) ? $query['limit'] : 20;
        $cat_id = isset($query['category']) ? $query['category'] : '';
        $status = isset($query['status']) ? $query['status'] : '';
        $products = DB::table('products')
            ->join('product_meta', 'product_meta.product_id', '=', 'products.id')
            ->join('relationships', 'relationships.object_id', '=', 'products.id')
            ->select('products.*')
            ->where(function ($select) use ($search) {
                if ($search) {
                    $select->orwhere('name', 'LIKE', "%{$search}%");
                    $select->orwhere('description', 'LIKE', "%{$search}%");
                    $select->orwhere('meta_key', '=', "sku")->where('meta_value', 'LIKE', "%{$search}%");
                }
            })
            ->where(function ($select) use ($cat_id) {
                if ($cat_id) {
                    $select->where('relationships.type', 'product_category_' . $cat_id);
                }
            })
            ->where(function ($select) use ($status) {
                if ($status) {
                    $select->where('status', $status);
                }
            })
            ->orderByDesc('updated_at')
            ->groupBy('products.id')
            ->paginate($limit);
        return $products;
    }

    // get publish product
    public static function get_products_publish($where = '')
    {
        $products = DB::table('products')->where('status', '=', 2)->orderByDesc('updated_at')->paginate(12);
        return $products;
    }

    // get publish product
    public static function remove_products_trash($where = '')
    {
        $products = DB::table('products')->where('status', 4)->delete();
        return $products;
    }


    // get all categories
    public static function get_product_categories($where = '')
    {
        $parent = (isset($_GET['parent'])) ? $_GET['parent'] : '';
        $search = (isset($_GET['search'])) ? $_GET['search'] : '';
        $limit = (isset($_GET['limit']) && $_GET['limit'] > 1) ? round($_GET['limit']) : 20;
        $products = DB::table('product_categories')
            ->where(function ($select) use ($search) {
                $select->orwhere('name', 'LIKE', "%{$search}%");
                $select->orwhere('slug', 'LIKE', "%{$search}%");
                $select->orwhere('description', 'LIKE', "%{$search}%");
                $select->orwhere('product_department', 'LIKE', "%{$search}%");
            })
            ->where(function ($select) use ($parent) {
                if ($parent) {
                    switch ($parent) {
                        case 'only_parent':
                            $select->where('parent_id', null);
                            break;
                        default:
                            $select->where('product_department', $parent);
                            break;
                    }
                }

            })
            ->orderBy('parent_id', 'desc')
            ->orderBy('loop', 'desc')
            ->paginate($limit);
        return $products;
    }

    // get all categories
    public static function get_product_categories_all($query = [])
    {
        $product_department = (isset($query['type'])) ? $query['type'] : '';
        $cats = (isset($query['cat'])) ? $query['cat'] : [];
        $products = DB::table('product_categories')
            ->where(function ($select) use ($product_department) {
                if ($product_department) $select->where('product_department', $product_department);
            })
            ->where(function ($select) use ($cats) {
                if (count($cats)) {
                    foreach ($cats as $cat) {
                        $select->orwhere('id', $cat);
                    }
                }
            })
            ->orderBy('loop', 'desc')->get();
        return $products;
    }


    // get all categories
    public static function get_product_categories_parent($query = [])
    {
        $parent_id = isset($query['parent_id']) ? $query['parent_id'] : null;
        $products = DB::table('product_categories')
            ->where('parent_id', $parent_id)->get();
        return $products;
    }

    // get categories top 4
    public static function get_product_categories_top($top = 4)
    {
        $product_categories = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->join('product_categories', 'product_categories.id', '=', 'relationships.term_id')
            ->select(DB::raw("term_id, product_categories.name,product_categories.slug, product_categories.description,   COUNT(object_id) as number_products "))
            ->where('type', 'like', 'product_category_%')
            ->where('status', 2)
            ->groupBy('term_id')
            ->orderBy('number_products', 'desc')
            ->limit($top)
            ->get();
        return $product_categories;
    }

    // Sort categories
    public static function get_sort_categories($categories)
    {
        $data = [];
        if (count($categories) > 1) {
            foreach ($categories as $category) {
                if (isset($category->parent_id)) {
                    if (!isset($data[$category->parent_id])) $data[$category->parent_id] = (array)self::get_product_categories_detail($category->parent_id);
                    $data[$category->parent_id]['child'][$category->id] = (array)$category;
                } else {
                    if (!isset($data[$category->id])) $data[$category->id] = (array)$category;
                }
            }
        } else {
            foreach ($categories as $category) {
                if (!isset($data[$category->id])) $data[$category->id] = (array)$category;
            }
        }
        return $data;
    }

    // get all categories
    public static function get_product_categories_detail($id)
    {
        $product_categories = DB::table('product_categories')->where('id', $id)->first();
        return $product_categories;
    }

    // get categories by slug
    public static function get_product_categories_bylug($slug)
    {
        $product_categories = DB::table('product_categories')->where('slug', $slug)->first();
        if ($product_categories) return $product_categories;
        return $product_categories;
    }

    // remove category
    public static function remove_category($request)
    {
        DB::table('product_categories')
            ->where(function ($query) use ($request) {
                $query->where('id', '=', $request['id']);
            })
            ->delete();
        DB::table('product_categories')->Where('parent_id', '=', $request['id'])->update(['parent_id' => null]);
        DB::table('relationships')
            ->where('type', 'product_category_' . $request['id'])
            ->delete();
    }

// get attribute by slug
    public static function get_product_attributes_bylug($slug)
    {
        $product_attribute = DB::table('product_attributes')->where('slug', $slug)->first();
        if ($product_attribute) return $product_attribute->id;
        return $product_attribute;
    }

    // get attribute brand by slug
    public static function get_product_brand_bylug($slug)
    {
        $product_attribute = DB::table('product_attributes')->where('slug', $slug)->first();
        return $product_attribute;
    }

    // get attribute by data type
    public static function get_product_attributes_by_datatype($data_type, $parent_id)
    {
        $product_attribute = DB::table('product_attributes')
            ->where('parent_id', $parent_id)
            ->where('data_type', $data_type)->first();
        if ($product_attribute) return $product_attribute;
        return $product_attribute;
    }

    // get id child attribute by id parent
    public static function get_id_child_attributes_byparent($parent_id, $query)
    {
        $product_attribute = DB::table('product_attributes')
            ->where('parent_id', $parent_id)
            ->where(function ($select) use ($query) {
                foreach ($query as $value) {
                    $select->orwhere('id', $value);
                }
            })
            ->first();
        if ($product_attribute) return $product_attribute->id;
        return null;
    }

    // create attribute
    public static function create_product_attribute_child($data_type)
    {
        $data['slug'] = check_field_table($data_type, 'slug', 'product_attributes');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
    }


    // get all attributes
    public static function get_product_attributes($where = '')
    {
        $products = DB::table('product_attributes')->where('parent_id', null)->orderByDesc('loop')->paginate(12);
        return $products;
    }

    // get attributes detail all of id
    public static function get_product_attributes_detail($id = '')
    {
        $detail = DB::table('product_attributes')->where('id', $id)->first();
        $list_attributes = DB::table('product_attributes')->where('parent_id', $id)->get();
        if (count($list_attributes)) $detail->child = $list_attributes;
        return $detail;
    }

    // get attributes detail single
    public static function get_product_attributes_detail_single($id = '')
    {
        $detail = DB::table('product_attributes')->where('id', $id)->first();
        return $detail;
    }

    // get attributes by title and type
    public static function get_product_attributes_by_title_type($title,$type=0)
    {
        $detail = DB::table('product_attributes')->where('name', $title)->where('type',$type)->first();
        return $detail;
    }

    // get attributes detail all of id
    public static function get_product_attributes_detail_filter($id)
    {
        $detail = DB::table('product_attributes')->where('id', $id)->first();
        $list_attributes = DB::table('product_attributes')
//            ->join('relationships','relationships.term_id','product_attributes.id')
            ->select('product_attributes.*')
            ->where('product_attributes.parent_id', $id)
            ->get();
        $detail->child = [];
        if (count($list_attributes)) $detail->child = $list_attributes;
        return $detail;
    }

    // get attributes detail pagition
    public static function get_product_attributes_detail_man($id, $limit = 20)
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $detail = DB::table('product_attributes')->where('id', $id)->first();
        $list_attributes = DB::table('product_attributes')
            ->where('parent_id', $id)
            ->where(function ($select) use ($search) {
                $select->orwhere('name', 'LIKE', "%{$search}%");
                $select->orwhere('data_type', 'LIKE', "%{$search}%");
            })
            ->paginate($limit);
        $detail->child = $list_attributes;
        return $detail;
    }

    // add attributes
    public static function add_attribute($data)
    {
        $id = DB::table('product_attributes')->insertGetId($data);
        return $id;
    }

    // update attributes
    public static function update_attribute($data)
    {
        DB::table('product_attributes')->where('id', $data['id'])->update($data);
    }

    // get product by id
    public static function get_product($product_id)
    {
        $product = DB::table('products')->where('id', $product_id)->first();
        return $product;
    }

    // get product by slug
    public static function get_product_bySlug($slug = '')
    {
        $product = DB::table('products')->where('slug', $slug)->first();
        return $product;
    }

    // get product by slug
    public static function get_product_relation($product_id, $term_id)
    {
        $products = DB::table('products')
            ->join('relationships', 'products.id', '=', 'relationships.object_id')
            ->select(['products.*'])
            ->where('relationships.term_id', $term_id)
            ->where('relationships.type', 'Like', 'product_category%')
            ->where('id', '<>', $product_id)->where('status', '=', 2)->paginate(12);
        return $products;
    }

    // status product
    public static function product_status()
    {
        $product_status = [
            1 => 'Draft',
            2 => 'Published',
            3 => 'Hidden',
            4 => 'Trash',
            5 => 'New',
        ];
        return $product_status;
    }

    public static function product_name_line()
    {
        $plate = [
            '1' => '1 Line Engraving',
            '2_even' => '2 Line Engraving (Even Lines)',
            '2_r_small' => '2 Line Engraving (Reg./Small)',
            '3_even' => '3 Line Engraving (Even Lines)',
            '3_r_small' => '3 Line Engraving (Reg./Small)',
        ];
        return $plate;
    }

    // status product
    public static function product_type()
    {
        $product_type = [
            0 => 'Simple',
            1 => 'Variations',
        ];
        return $product_type;
    }

    // status departments
    public static function product_departments()
    {
        $product_type = [
            'apparel' => 'Apparel',
            'outer_wear' => 'Outerwear',
            'gear_accessories' => 'Gear/Accessories',
            'caps_hats' => 'Caps & Hats',
            'footwear' => 'Footwear',
            'ppe' => 'PPE',
            'department' => 'Department',
            'specials' => 'Specials',
        ];
        return $product_type;
    }

    // status product
    public static function attribute_type()
    {
        $attribute_type = [
            ['title' => 'Color', 'icon' => 'fas fa-palette', 'type' => 'color'],
            ['title' => 'Image', 'icon' => 'fas fa-images', 'type' => 'hidden'],
            ['title' => 'Text', 'icon' => 'fas fa-file-alt', 'type' => 'text'],
            ['title' => 'Number', 'icon' => 'fas fa-list-ol', 'type' => 'number'],
        ];
        return $attribute_type;
    }

    //  product attributes
    public static function product_attributes()
    {
        $product_attributes = self::get_product_attributes()->items();
        $Attributes = [];
        foreach ($product_attributes as $value) {
            $Child = self::get_product_attributes_detail($value->id);
            $Attributes[$value->id]['title'] = $value->name;
            $Attributes[$value->id]['value'] = [];
            if (isset($Child->child)) {
                foreach ($Child->child as $item) {
                    $Attributes[$value->id]['value'][$item->id] = $item->name;
                }
            }

        }
        return $Attributes;
    }

    // get meta product
    public static function get_meta_product($product_id, $meta_key, $re = '')
    {
        $product_meta = DB::table('product_meta')->where('product_id', $product_id)->where('meta_key', $meta_key)->first();
        if ($product_meta) return $product_meta->meta_value;
        return $re;
    }

    // update meta product
    public static function update_meta_product($product_id, $meta_key, $meta_value)
    {
        $product_meta = self::get_meta_product($product_id, $meta_key);
        if ($product_meta != null) {
            DB::table('product_meta')->where('product_id', $product_id)->where('meta_key', $meta_key)->update(['meta_value' => $meta_value]);
        } else {
            $product_meta = self::add_meta_product($product_id, $meta_key, $meta_value);
        }
        return $product_meta;
    }

    // get meta product
    public static function add_meta_product($product_id, $meta_key, $meta_value)
    {
        $product_meta = self::get_meta_product($product_id, $meta_key);
        if (!$product_meta) {
            $data = array(
                'product_id' => $product_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $meta_id = DB::table('product_meta')->insertGetId($data);
            return $meta_id;
        }
        return false;
    }

    // delete meta product
    public static function delete_meta_product($product_id, $meta_key, $meta_value = '')
    {
        $product_meta = self::get_meta_product($product_id, $meta_key);
        if ($product_meta != null) {
            DB::table('product_meta')->where('product_id', $product_id)->where('meta_key', $meta_key)->delete();
        }
        return $product_meta;
    }

    // get all product
    public static function get_filter_products($query = array())
    {
        $type_product = isset($query['type']) ? $query['type'] : '';
        $page = isset($query['page']) ? $query['page'] : 1;
        $search = isset($query['search']) ? $query['search'] : '';
        $categories = isset($query['product']) ? $query['product'] : [];
        $attributes = isset($query['product_attribute']) ? $query['product_attribute'] : [];
        $sort = isset($query['sort']) ? $query['sort'] : '';
        $orderby = ['type' => 'char'];
        switch ($sort) {
            case 'newest':
                $orderby['name'] = 'products.updated_at';
                $orderby['value'] = 'DESC';
                break;
            case 'price_desc':
                $orderby['name'] = 'price';
                $orderby['value'] = 'DESC';
                $orderby['type'] = 'number';
                break;
            case 'price_asc':
                $orderby['name'] = 'price';
                $orderby['value'] = 'ASC';
                $orderby['type'] = 'number';
                break;

            case 'top_sell_year':
            case 'top_sell_month':
            case 'top_sell_week':
                $orderby['name'] = $sort;
                $orderby['value'] = 'DESC';
                $orderby['type'] = 'number';
                break;

            default;
                $orderby['name'] = 'sku';
                $orderby['value'] = 'ASC';
                break;
        }
        $Attribute_inner = "";
        $Attribute_where = "";
        foreach ($attributes as $table => $value) {
            $Attribute_inner .= 'inner join `relationships` as `Attribute_' . $table . '` on `products`.`id` = `Attribute_' . $table . '`.`object_id` ';
            $Attribute_where .= " ( ";
            foreach ($value['data'] as $check => $type) {
                $re = '';
                if ($check > 0) $re = 'OR';
                $Attribute_where .= $re . ' `Attribute_' . $table . '`.`type`' . " = 'product_attribute_" . $type . "' ";
            }
            $Attribute_where .= ") and";
        }
        $products = DB::table(DB::raw('products ' . $Attribute_inner))
            ->join('relationships as Categories', 'products.id', '=', 'Categories.object_id')
            ->join('product_meta', 'products.id', '=', 'product_meta.product_id')
            ->join('product_categories', 'Categories.term_id', '=', 'product_categories.id')
            ->join('product_meta as table_sku', 'products.id', '=', 'table_sku.product_id')
            ->select(['products.*','product_categories.loop as loop_cat', 'table_sku.meta_value as sku_search', ($orderby['type'] == 'number') ? DB::raw('FORMAT(product_meta.meta_value,0) as ' . $orderby['name']) : 'product_meta.meta_value as ' . $orderby['name']])
            ->where('products.status', 2)
            ->where('table_sku.meta_key', 'sku')
            ->where(function ($select) use ($search) {
                $select->orwhere('products.name', 'LIKE', '%' . $search . '%');
                $select->orwhere('table_sku.meta_value', 'LIKE', '%' . $search . '%');
            })
            ->where(function ($products) use ($orderby) {
                if ($orderby['name'] != 'products.updated_at') $products->where('product_meta.meta_key', $orderby['name']);
            })
            ->where('Categories.type','LIKE','%product_category_%')
            ->where(
                function ($products) use ($categories) {
                    if (count($categories) > 0) {
                        foreach ($categories as $value) {
                            $cat = (is_object($value)) ? (array)$value : ['id' => $value];
                            //you can use orWhere the first time, doesn't need to be ->where
                            $products->orWhere('Categories.type', 'product_category_' . $cat['id']);
                        }
                    }
                }
            )
            ->where(DB::raw($Attribute_where . " 1"), '1')
            ->orderBy('loop_cat', 'DESC')
            ->orderBy(($orderby['type'] == 'number') ? DB::raw($orderby['name'] . '+ 0') : $orderby['name'], $orderby['value'])
            ->groupBy('products.id')
            ->paginate(12, ['*'], 'page', $page);
//        ->toSql();
//        dd($products);
        return $products;
    }

    // get product by categories
    public static function get_product_byTax($term_id, $type = 'product_category')
    {
        $products = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->where('type', $type . '_' . $term_id)
            ->where('status', 2)
            ->groupBy('products.id')
            ->get();
        return $products;
    }

    // get product by categories
    public static function get_product_byTax_array($query = [], $type = 'product_category')
    {
        $cat_return = [];
        $query['cat'] = isset($query['cat']) ? $query['cat'] : [];
        $query['search'] = isset($query['search']) ? $query['search'] : '';
        $products = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->join('product_meta', 'products.id', '=', 'product_meta.product_id')
            ->select(DB::raw('term_id, Count(object_id) as products'))
            ->where('meta_key', 'sku')
            ->where(function ($select) use ($query, $type) {
                if (!empty($query['cat'])) foreach ($query['cat'] as $term_id) {
                    if ($term_id) $select->orwhere('type', $type . '_' . $term_id);
                }
            })
            ->where(function ($select) use ($query) {
                $select->orwhere('name', 'LIKE', "%{$query['search']}%");
                $select->orwhere('product_meta.meta_value', 'LIKE', "%{$query['search']}%");
            })
            ->where('status', 2)
            ->groupBy('term_id')
            ->get();
        foreach ($products as $cat) {
            $cat_return[$cat->term_id] = $cat->products;
        }
        return $cat_return;
    }

    // get count product by categories & attribute
    public static function get_product_byTax_cat($term_id, $cats = [])
    {
        $products = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->join('relationships as cat', 'products.id', '=', 'cat.object_id')
            ->where('relationships.type', 'product_attribute_' . $term_id)
            ->where(function ($select) use ($cats) {
                if (count($cats) > 0) {
                    foreach ($cats as $cat) {
                        $select->orwhere('cat.type', 'product_category_' . $cat);
                    }
                } else {
                    $select->where(DB::raw('1=2'));
                }
            })
            ->where('status', 2)
            ->groupBy('relationships.object_id')
            ->get();
        return $products;
    }

    // get count product by categories & attribute
    public static function get_product_byTax_cat_array($query = [])
    {
        $attr_return = [];
        $query['cat'] = isset($query['cat']) ? $query['cat'] : [];
        $query['attr'] = isset($query['attr']) ? $query['attr'] : [];
        $query['search'] = isset($query['search']) ? $query['search'] : '';
        $products = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->join('relationships as cat', 'products.id', '=', 'cat.object_id')
            ->join('product_meta', 'products.id', '=', 'product_meta.product_id')
            ->select(DB::raw('relationships.*'))
            ->where('meta_key', 'sku')
            ->where(function ($select) use ($query) {
                if (count($query['attr']) > 0) {
                    foreach ($query['attr'] as $term_id) {
                        $select->orwhere('relationships.type', 'product_attribute_' . $term_id);
                    }
                }
            })
            ->where(function ($select) use ($query) {
                if (count($query['cat']) > 0) {
                    foreach ($query['cat'] as $cat) {
                        $select->orwhere('cat.type', 'product_category_' . $cat);
                    }
                } else {
                    $select->where(DB::raw('1=2'));
                }
            })
            ->where('status', 2)
            ->where(function ($select) use ($query) {
                $select->orwhere('name', 'LIKE', "%{$query['search']}%");
                $select->orwhere('product_meta.meta_value', 'LIKE', "%{$query['search']}%");
            })
            ->groupBy('relationships.re_id')
            ->limit(1000)
            ->get();
        foreach ($products as $attr) {
            if (isset($attr_return[$attr->term_id])) {
                $attr_return[$attr->term_id]++;
            } else {
                $attr_return[$attr->term_id] = 1;
            }
        }
        return $attr_return;
    }

    // get count product by categories & attribute
    public static function get_product_byTax_attribute_array($query = [],$select_table='cat')
    {
        $attr_return = [];
        $query['cat'] = isset($query['cat']) ? $query['cat'] : [];
        $query['attr'] = isset($query['attr']) ? $query['attr'] : [];
        $query['search'] = isset($query['search']) ? $query['search'] : '';
        $products = DB::table('relationships')
            ->join('products', 'products.id', '=', 'relationships.object_id')
            ->join('relationships as cat', 'relationships.object_id', '=', 'cat.object_id')
            ->join('product_meta', 'products.id', '=', 'product_meta.product_id')
            ->select(DB::raw($select_table.'.*'))
            ->where('meta_key', 'sku')
            ->where(function ($select) use ($query) {
                if (count($query['attr']) > 0) {
                    foreach ($query['attr'] as $term_id) {
                        $select->orwhere('relationships.type', 'product_attribute_' . $term_id);
                    }
                }
                if (count($query['cat']) > 0) {
                    foreach ($query['cat'] as $cat) {
                        $select->orwhere('relationships.type', 'product_category_' . $cat);
                    }
                }
            })
//            ->where(function ($select) use ($query) {
//
//            })
            ->where('status', 2)
            ->where(function ($select) use ($query) {
                $select->orwhere('name', 'LIKE', "%{$query['search']}%");
                $select->orwhere('product_meta.meta_value', 'LIKE', "%{$query['search']}%");
            })
            ->groupBy('cat.re_id')
            ->get();
        foreach ($products as $attr) {
            if (isset($attr_return[$attr->type])) {
                $attr_return[$attr->type]++;
            } else {
                $attr_return[$attr->type] = 1;
            }
        }
        return $attr_return;
    }


    // get all product
    public static function get_search_products($query)
    {
        $products = DB::table('products')
            ->join('relationships as Categories', 'products.id', '=', 'Categories.object_id')
            ->join('product_meta', 'product_meta.product_id', '=', 'products.id')
            ->select(DB::raw('products.*,meta_value as sku'))
            ->where('product_meta.meta_key', 'sku')
            ->where(function ($select) use ($query) {
                $select->orwhere('name', 'LIKE', "%{$query['search']}%");
                $select->orwhere('product_meta.meta_value', 'LIKE', "%{$query['search']}%");
            })
            ->where('status', 2)
            ->where(function ($select) use ($query) {
                if ($query['cat']) {
                    foreach ($query['cat'] as $cat) {
                        $select->orwhere('type', 'product_category_' . $cat);
                    }
                }
            })
            ->orderByDesc('products.updated_at')
            ->groupBy('products.id')
            ->paginate(5);
        return $products;
    }

    // check category by title
    public static function check_categories($title, $table = 'product_categories', $data_attr = array())
    {
        $product_categories = DB::table($table)->where('name', $title)->first();
        if ($product_categories) return $product_categories->id;
        $data['slug'] = check_field_table($title, 'slug', $table);
        $data['name'] = $title;
        if ($table == 'product_attributes') {
            $data['parent_id'] = $data_attr['parent_id'];
            $data['data_type'] = $data_attr['data_type'];
            $data['type'] = $data_attr['type'];
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $product_categories_id = DB::table($table)->insertGetId($data);
        return $product_categories_id;
    }


    public static function add_product_excel($data)
    {
        $data['slug'] = check_field_table($data['name'], 'slug', 'products');
        $data['status'] = 5;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['featured_image'] = Media::create_media_from_path($data['path'] . '/' . $data['featured_image'], $data['featured_image']);
        $price = (isset($data['price'])) ? $data['price'] : null;
        $sku = (isset($data['sku'])) ? $data['sku'] : null;
        $weight = (isset($data['weight'])) ? $data['weight'] : null;
        $gallery = [];
        $list_category = explode(',', $data['category']);
        if ($data['gallery']) {
            $galleries = get_images_import($data['gallery']);
            foreach ($galleries as $image) {
                $id = Media::create_media_from_path($data['path'] . '/' . $image, $image);
                if($id)$gallery[$id] = $id;
            }
            $gallery = \GuzzleHttp\json_encode($gallery);
        }
        if ($data['attr']) {
            foreach ($data['attr'] as $key_attr => $data_attr) {
                $id = \App\Product::get_product_attributes_bylug($key_attr);
                $data_row = show_size_import($data_attr);
                $value_attr = [];
                $item = [];
                foreach ($data_row as $item_row) {
                    $attr = self::get_product_attributes_by_datatype(strtolower($item_row), $id);
                    if ($attr) {
                        $item['value'] = $attr->id;
                        $item['title'] = $attr->name;
                        $value_attr[] = $item;
                    }
                }
                if ($id && count($value_attr) > 0) $attributes[] = $id;
                if (count($value_attr) > 0) $all_attributes[$id] = ['value' => $value_attr, 'display' => 'true'];
            }
        }


        if (isset($data['variations'])) {
            $thumbnail_color= [];
            $thumbnail_attribute = [];
            foreach ($data['variations'] as  $attr) {
                if(!$attr['type']){
                    $attr_id = self::get_product_attributes_by_title_type($attr['attribute'],$attr['type']);
                    if($attr['thumbnail'] && $attr_id)$thumbnail_color[$attr_id->id] = \App\Media::get_media_detail_by_title( $attr['thumbnail'] );
                    if($attr['gallery'] && $attr_id){
                        foreach(explode(',',$attr['gallery']) as $image ){
                            $id_img = \App\Media::get_media_detail_by_title( $image );
                            if($id_img)$thumbnail_attribute[$attr_id->id][$id_img] = $id_img;
                        }
                    }
                }

            }
        }


        $data['author'] = Auth::id();
        unset($data['category']);
        unset($data['gallery']);
        unset($data['id']);
        unset($data['price']);
        unset($data['color']);
        unset($data['size']);
        unset($data['number_size']);
        unset($data['sku']);
        unset($data['path']);
        unset($data['weight']);
        unset($data['attr']);
        unset($data['variations']);
        if ($product_id = self::check_product_bysku($sku)) {
            unset($data['slug']);
            unset($data['status']);
            DB::table('products')->where('id', $product_id)->update($data);
        } else {
            $product_id = DB::table('products')->insertGetId($data);
        }

        // save category
        if (isset($list_category)) {
            Relationships::delete_relationship($product_id, 'product_category_');
            foreach ($list_category as $item) {
                $category = self::check_categories($item);
                if (isset($category)) Relationships::save_relationships($product_id, $category, 'product_category_');
            }
        }


        if ($price) Product::update_meta_product($product_id, 'price', $price);
        if ($sku) Product::update_meta_product($product_id, 'sku', $sku);
        if ($gallery) Product::update_meta_product($product_id, 'gallery', $gallery);
        if (isset($attributes)) Product::update_meta_product($product_id, 'attributes', \GuzzleHttp\json_encode($attributes));
        if (isset($all_attributes)) Product::update_meta_product($product_id, 'all_attributes', \GuzzleHttp\json_encode($all_attributes));
        if($thumbnail_color)Product::update_meta_product($product_id, 'thumbnail_color', \GuzzleHttp\json_encode($thumbnail_color));
        if($thumbnail_attribute)Product::update_meta_product($product_id, 'thumbnail_attribute', \GuzzleHttp\json_encode($thumbnail_attribute));

        // save attributes
        if ($product_id && isset($all_attributes)) {
            $attributes = [];
            Relationships::delete_relationship($product_id, 'product_attribute');
            foreach ($all_attributes as $key => $value) {
                $attributes[] = $key;
                if ($value['value']) {
                    foreach ($value['value'] as $item) {
                        Relationships::insert_relationships_attr($product_id, $item['value'], 'product_attribute_');
                    }
                }
            }

        }
        // end save attributes

        return $product_id;

    }


    /// check sku product return id
    public static function check_product_bysku($sku)
    {
        $product = DB::table('products')
            ->join('product_meta', 'product_meta.product_id', '=', 'products.id')
            ->select('products.id')
            ->where('meta_key', "sku")
            ->where('meta_value', $sku)
            ->first();
        return $product ? $product->id : false;
    }

    /// update_product
    public static function update_product($id, $data)
    {
        DB::table('products')
            ->where('id', $id)
            ->update($data);
    }


    // Update category product
    public static function update_category($id, $data)
    {
        DB::table('product_categories')->where('id', $id)->update($data);
    }

    // get all categories
    public static function get_product_categories_byType($type = '')
    {
        $product_categories = DB::table('product_categories')
            ->where(function ($select) use ($type) {
                if ($type) $select->where('product_department', $type);
            })
            ->orderBy('loop', 'desc')
            ->get();
        return $product_categories;
    }

    public static function insert_best_sell_product($product_id)
    {
        $array_sell = ['top_sell_week', 'top_sell_month', 'top_sell_year'];
        foreach ($array_sell as $key) {
            $data = DB::table('product_meta')
                ->where('product_id', $product_id)
                ->where('meta_key', $key)
                ->first();
            if (!$data) self::update_meta_product($product_id, $key, 0);
        }

    }


    // check category by title
    public static function check_attributes($data_attr, $table = 'product_attributes')
    {
        $product_attributes = DB::table($table)
            ->where('name', $data_attr['name'])
            ->where('parent_id', $data_attr['parent_id'])
            ->first();
        $data_attr['updated_at'] = date('Y-m-d H:i:s');
        if ($product_attributes) {
            DB::table($table)->where('id', $product_attributes->id)->update($data_attr);
            return $product_attributes->id;
        }
        $data_attr['created_at'] = date('Y-m-d H:i:s');
        $product_attributes_id = DB::table($table)->insertGetId($data_attr);
        return $product_attributes_id;
    }


}
