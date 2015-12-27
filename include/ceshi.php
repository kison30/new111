<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/14
 * Time: 10:47
 */

define('HN1', true);
require_once('../global.php');
define('SCRIPT_ROOT',  dirname(dirname(__FILE__)).'/');
require_once SCRIPT_ROOT.'admin/logic/shop_infoBean.php';
require_once SCRIPT_ROOT.'admin/logic/sys_areaBean.php';
require_once SCRIPT_ROOT.'admin/logic/sys_dictBean.php';
require_once SCRIPT_ROOT.'admin/logic/sys_userBean.php';
require_once SCRIPT_ROOT.'admin/logic/user_orderBean.php';
require_once SCRIPT_ROOT.'admin/logic/user_order_goodsBean.php';
require_once SCRIPT_ROOT.'admin/logic/user_commentBean.php';

$key 	= isset( $_REQUEST['key'] ) ? trim($_REQUEST['key'])   : '';
$act 	= isset( $_REQUEST['act'] ) ? trim($_REQUEST['act'])   : '';
$sid 	= isset( $_REQUEST['sid'] ) ? intval($_REQUEST['sid']) : '';
$result = "";


if ( $key == '' || $act == '' )
{
    $result = array(
        'success'   => false,
        'result'    => -1,
        'error_msg' => "添加失败，缺少所需参数！"
    );

    echo json_encode($result);
    exit;
}



if ( $key != 'e3dc653e2d68697346818dfc0b208322' )
{
    $result = array(
        'success'   => false,
        'result'    => -2,
        'error_msg' => "添加失败，标识码有误！"
    );

    echo json_encode($result);
    exit;
}

$shop_info 			= new shop_infoBean($db,'shop_info');
$sys_dict  			= new sys_dictBean($db,'sys_dict');
$sys_area 			= new sys_areaBean($db,'sys_area');
$sys_user  			= new sys_userBean($db,'sys_user');
$user_order			= new user_orderBean($db,'user_order');
$user_order_goods 	= new user_order_goodsBean($db,'user_order_goods');
$user_comment 		= new user_commentBean($db,'user_comment');

switch ( $act )
{
    case 'list':			//商家列表
        $result = get_list($db,$user_comment,$user_order,$shop_info);
        break;

    case 'info':			//商家信息
        $result = detail($db,$shop_info,$user_comment,$user_order);
        break;

    case 'order_list':		//获取商家订单列表
        $result = get_order_list($sid, $user_order, $sys_user, $sys_dict, $user_order_goods);
        break;

    case 'type':
        $result = get_type($db, $sys_dict);
        break;

    case 'trading_mode':
        $result = trading_mode($sid,$shop_info);//获取商家交易模式
        break;

    case 'search_shop':
        $result = search_shop($db,$user_comment,$user_order,$shop_info);//模糊查询店铺
        break;

    default:
        $result = array(
            'success'	=>	false,
            'result'	=>	-1,
            'error_msg'	=>	"传入参数有误！"
        );
        break;
}



/*
 * 功能：商家列表
 * */
function get_list($db,$user_comment,$user_order,$shop_info)
{
    $userLng 	= isset( $_REQUEST['user_lng'] ) 	? $_REQUEST['user_lng']   		: '116.641485';
    $userLat 	= isset( $_REQUEST['user_lat'] ) 	? $_REQUEST['user_lat']   		: '23.419546';
    $area 		= isset($_REQUEST['area']) 			? $_REQUEST['area']   			: '澄海区';
    $page 		= isset( $_REQUEST['page'] ) 	 	? intval($_REQUEST['page'])   	: '1';
    $sort 		= isset( $_REQUEST['sort'] )		? intval($_REQUEST['sort'])   	: '1'; //排序
    $classification = isset( $_REQUEST['classification'] ) ? intval($_REQUEST['classification'])   	: '0'; //分类
    if( $classification == 0)
    {
        $strSQL = "SELECT max_delivery_distance,id,shop_name,logo,bid_price,distribution_charge,average_service_time,delivery_time,longitude,latitude,sorting,area FROM `shop_info` WHERE NOT find_in_set (8,shop_category) and is_del=0 AND status=1";
    }
    else
    {
        $strSQL = "SELECT max_delivery_distance,id,shop_name,logo,bid_price,distribution_charge,average_service_time,delivery_time,longitude,latitude,sorting,area FROM `shop_info` WHERE find_in_set ({$classification},shop_category) AND is_del=0 AND status=1";
    }


    $rs = $db->get_results($strSQL);

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    $arrWhere = array(
        'name' => $area
    );
    $arrCol = array(
        'id'
    );
    $sys_area 			= new sys_areaBean($db,'sys_area');
    $area =  $sys_area->get_one($arrWhere, $arrCol);
    $areaNo = $area->id;

    foreach($rs as $key=>$rows)
    {
        $s = get_distance( $userLng,$userLat, $rows->longitude, $rows->latitude, $len_type = 2, $decimal = 2 );
        if($rows->sorting <=0 && $rows->area != $areaNo){
            if( $s > ($rows->max_delivery_distance))
            {
                unset($rs[$key]);
            }
        }
    }

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }



    foreach( $rs as $key=>$row )
    {
        $info[$key]['id']						= $row->id;
        $info[$key]['shop_name']				= $row->shop_name;
        $info[$key]['logo']						= $GLOBALS['IMAGE_URL']."shop_logo/".$row->logo;
        $info[$key]['bid_price']				= $row->bid_price;
        $info[$key]['distribution_charge']		= $row->distribution_charge;
        $info[$key]['average_service_time']		= $row->average_service_time;
        $info[$key]['shop_score']				= $user_comment->get_shop_score( $row->id );
        $info[$key]['order_num']				= $user_order->get_order_num( $row->id );
        $info[$key]['distance']					= get_distance( $userLng,$userLat, $row->longitude,$row->latitude,  $len_type = 2, $decimal = 2 );
        $info[$key]['comment_num']				= $user_comment->get_comment_num( $row->id );
        $info[$key]['is_work']					= is_work( $row->delivery_time );
        $info[$key]['max_delivery_distance']	= $row->max_delivery_distance;
        $info[$key]['sorting'] 					= $row->sorting;
        $info[$key]['is_choice'] 					= $row->sorting > 0 ? 1 : 0;
        $info[$key]['in_area']								= $row->area == $areaNo ? 1 : 0;
    }
    if(is_array($info))
    {
        switch($sort)
        {

            case 2:			//销量最高
                foreach( $info as $key=> $row )
                {
                    $order_num[$key] = $row['order_num'];
                    $is_work[$key]	= $row['is_work'];
                }
                array_multisort($is_work,SORT_DESC,$order_num,SORT_DESC,$info);
                break;

            case 3:			//距离最近
                foreach( $info as $key=> $row )
                {
                    $distance[$key] = $row['distance'];
                    $is_work[$key]	= $row['is_work'];
                }
                array_multisort($is_work,SORT_DESC,$distance,SORT_ASC,$info);
                break;

            case 4:			//评分最高
                foreach( $info as $key=> $row )
                {
                    $is_work[$key]	= $row['is_work'];
                    $shop_score[$key] = $row['shop_score'];
                }
                array_multisort($is_work,SORT_DESC,$shop_score,SORT_DESC,$info);
                break;

            case 5:			//起送价最低
                foreach( $info as $key=> $row )
                {
                    $is_work[$key]	= $row['is_work'];
                    $bid_price[$key] = $row['bid_price'];
                }
                array_multisort($is_work,SORT_DESC,$bid_price,SORT_ASC,$info);
                break;

            default:
                foreach( $info as $key=> $row )
                {
                    $distance[$key] = $row['distance'];
                    $is_work[$key]	= $row['is_work'];
                    $sorting[$key] = $row['sorting'];
                    $in_area[$key] = $row['in_area'];
                }
                array_multisort($in_area,SORT_DESC,$sorting,SORT_DESC,$is_work,SORT_DESC,$distance,SORT_ASC,$info);
                break;
        }
    }


    $recordCount = count($info);

    $rs = arrSort($info,$recordCount, $page, $pageSize = 15);

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    $result = array(
        'success'   => true,
        'result'    => $rs,
        'error_msg' => "获取成功！"
    );

    return $result;

}


/*
 * 功能：商家信息
 */
function detail($db,$shop_info,$user_comment,$user_order)
{
    $id = !isset($_REQUEST['id']) ? '' : trim($_REQUEST['id']);

    if ( $id == '' )
    {
        $result = array(
            'success'   => false,
            'result'    => -1,
            'error_msg' => "获取失败，缺少所需参数！"
        );

        return $result;
    }

    $arrWhere = array(
        'id' => $id
    );

    $arrCol = array(
        'shop_category',
        'shop_name',
        'logo',
        'contactor',
        'telephone',
        'mobile',
        'province',
        'city',
        'area',
        'address',
        'longitude',
        'latitude',
        //'start_delivery_time',
        //'end_delivery_time',
        'delivery_time',
        'main_type',
        'main_type',
        'average_service_time',
        'bid_price',
        'distribution_charge',
        'shop_info',
        'safe_level',
        'lunch_fee',
        'shop_advert'
    );

    $rs 		= $shop_info->get_list($arrWhere,$arrCol);

    if(is_array($rs))
    {
        $strDelivery = json_decode($rs[0]->delivery_time,true);

        $key = 0;
        foreach($strDelivery as $row)
        {
            $arr[$key]['start_time']	=	$row['start_time'];
            $arr[$key]['end_time']		=	$row['end_time'];
            $key++;
        }

        $rs[0]->delivery_time 	= $arr;
        $rs[0]->logo 			= $GLOBALS['IMAGE_URL']."shop_logo/".$rs[0]->logo;
        $rs[0]->shop_info		= strip_tags($rs[0]->shop_info);
    }

    if( count($rs) <= 0 )
    {
        $result = array(
            'success'   => false,
            'result'    => 0,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    $sys_area 	= new sys_areaBean($db,'sys_area');
    $area_list 	= $sys_area->get_address( $rs[0]->province, $rs[0]->city, $rs[0]->area, $rs[0]->address );

    $rs[0]->province 	= $area_list['province_info'];
    $rs[0]->city 		= $area_list['city_info'];
    $rs[0]->area 		= $area_list['area_info'];

    $sys_dict		= new sys_dictBean($db,'sys_dict');
    $arr_category 	= $sys_dict->get_shop_category($rs[0]->shop_category);

    $rs[0]->shop_category 		= $arr_category;
    $rs[0]->shop_score 			= $user_comment->get_shop_score( $id );
    $rs[0]->shop_comment_num 	= $user_comment->get_comment_num( $id );
    $rs[0]->shop_order_num		= $user_order->get_order_num( $id );

    if ( count($rs) > 0 )
    {
        $result = array(
            'success'   => true,
            'result'    => $rs[0],
            'error_msg' => "获取成功！"
        );
    }
    else
    {
        $result = array(
            'success'   => false,
            'result'    => 0,
            'error_msg' => "查找记录为空！"
        );
    }

    return $result;
}


/*
 * 功能：商家订单列表
 */
function get_order_list( $sid, $user_order, $sys_user, $sys_dict, $user_order_goods )
{
    $status = isset( $_REQUEST['status'] ) 	 ? intval($_REQUEST['status'])   : '0';
    $page 	= isset( $_REQUEST['page'] ) 	 ? intval($_REQUEST['page'])   	: '1';
    $num 	= 0;	// 订单商品数
    $arrCol	= array( 'id','user_id', 'order_status', 'order_amount'  );

    if ( $status != 0 )
    {
        $arrWhere = array(
            'order_status'  => $status,
            'shop_id' 		=> $sid
        );
    }
    else
    {
        $arrWhere = array(
            'shop_id' 		=> $sid
        );
    }

    $arrOrderList = $user_order->get_list( $arrWhere, $arrCol );

    if ( $arrOrderList == null )
    {
        $arrOrderList = array();
        $result = array(
            'success'   => true,
            'result'    => $arrOrderList,
            'error_msg' => "查询订单记录为空！"
        );

        return $result;
    }

    foreach( $arrOrderList as $key=>$order_list )
    {
        $arrWhere 	    = array( 'id'=>$order_list->user_id );
        $arrCol         = array('nikename');
        $arrSysUser    = $sys_user->get_list( $arrWhere, $arrCol );

        $order_status   = $sys_dict->get_order_status( $order_list->order_status );

        $arrCol 	    = array( 'product_img','product_name','product_price','product_rule_desc','nums' );
        $arrWhere 	    = array('order_id'=>$order_list->id);
        $arrOrderGoods  = $user_order_goods->get_list( $arrWhere, $arrCol );

        if( is_array($arrOrderGoods) )
        {
            foreach( $arrOrderGoods as $key=>$order_goods )
            {
                $num += $order_goods->nums;
                $arrGoodList[] = $order_goods;
                $arrOrderGoods[$key]->product_img 	= $GLOBALS['IMAGE_URL']."product/".$order_goods->product_img;
            }
        }

        $info[$key] = array(
            'nikename' 			=> $arrSysUser[0]->nikename,
            'order_status'		=> $order_status,
            'all_price'			=> $order_list->order_amount,
            'nums' 				=> $num,
            'goods_list'		=> $arrGoodList
        );

    }

    $recordCount = count($info);

    $rs = arrSort($info,$recordCount, $page, $pageSize = 5);

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    $result = array(
        'success'   => true,
        'result'    => $rs,
        'error_msg' => "获取订单成功！"
    );

    return $result;

}



/*
 * 功能：商家类型
 */
function get_type($db, $sys_dict)
{
    $arrCol 		 = array( 'id','name' );
    $arrWhere 		 = array( 'type'=>'restaurant_type' );
    $rs = $sys_dict->get_list( $arrWhere,$arrCol );

    $result = array(
        'success'   => true,
        'result'    => $rs,
        'error_msg' => "获取成功！"
    );

    return $result;
}


/*
 * 功能：获取商家支持的交易模式
 */
function trading_mode($sid,$shop_info)
{
    $arrWhere 	=	array( 'id' => $sid );
    $arrCol		=	array(	'trading_mode'	);

    $rs	= $shop_info->get_list( $arrWhere,$arrCol );

    if( count($rs) > 0 )
    {
        $result = array(
            'success'   => true,
            'result'    => explode(",",$rs[0]->trading_mode),
            'error_msg' => "获取成功！"
        );
    }
    else
    {
        $result = array(
            'success'   => false,
            'result'    => 0,
            'error_msg' => "获取失败！"
        );
    }
    return $result;
}



/*
 * 功能：根据店铺名进行模糊查询
 */
function search_shop($db,$user_comment,$user_order,$shop_info)
{
    $userLng 	= isset( $_REQUEST['user_lng'] ) 	? $_REQUEST['user_lng']   		: '116.641485';
    $userLat 	= isset( $_REQUEST['user_lat'] ) 	? $_REQUEST['user_lat']   		: '23.419546';
    $page 		= isset( $_REQUEST['page'] ) 	 	? intval($_REQUEST['page'])   	: '1';
    $keyword	= isset( $_REQUEST['keyword'] )		? trim($_REQUEST['keyword'])	: '';

    $strSQL = "SELECT id,shop_name,logo,bid_price,distribution_charge,average_service_time,delivery_time,longitude,latitude FROM `shop_info` WHERE is_del=0 AND status=1 AND shop_name LIKE '%{$keyword}%' ";
    $rs = $db->get_results($strSQL);

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    foreach($rs as $key=>$rows)
    {
        $s = get_distance( $userLng,$userLat, $rows->longitude, $rows->latitude, $len_type = 2, $decimal = 2 );

        if( $s > 10)
        {
            unset($rs[$key]);
        }
    }

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }


    foreach( $rs as $key=>$row )
    {
        $info[$key]['id']						= $row->id;
        $info[$key]['shop_name']				= $row->shop_name;
        $info[$key]['logo']						= $GLOBALS['IMAGE_URL']."shop_logo/".$row->logo;
        $info[$key]['bid_price']				= $row->bid_price;
        $info[$key]['distribution_charge']		= $row->distribution_charge;
        $info[$key]['average_service_time']		= $row->average_service_time;
        $info[$key]['shop_score']				= $user_comment->get_shop_score( $row->id );
        $info[$key]['order_num']				= $user_order->get_order_num( $row->id );
        $info[$key]['distance']					= get_distance( $userLng,$userLat, $row->longitude,$row->latitude,  $len_type = 2, $decimal = 2 );
        $info[$key]['comment_num']				= $user_comment->get_comment_num( $row->id );
        $info[$key]['is_work']					= is_work( $row->delivery_time );
    }

    if(is_array($info))
    {
        foreach( $info as $key=> $row )
        {
            $distance[$key] = $row['distance'];
            $is_work[$key]	= $row['is_work'];
        }
        array_multisort($is_work,SORT_DESC,$distance,SORT_ASC,$info);
    }


    $recordCount = count($info);

    $rs = arrSort($info,$recordCount, $page, $pageSize = 5);

    if ( $rs == null )
    {
        $rs = array();
        $result = array(
            'success'   => true,
            'result'    => $rs,
            'error_msg' => "查找记录为空！"
        );

        return $result;
    }

    $result = array(
        'success'   => true,
        'result'    => $rs,
        'error_msg' => "获取成功！"
    );

    return $result;
}









/*
 *功能：根据指定当前时间判断店铺是否营业中
 *返回值：1 营业中；0 未营业
 */
function is_work( $delivery_time )
{
    $arrDeliverTime = json_decode($delivery_time,true);

    $work = 0;
    foreach( $arrDeliverTime as $info )
    {
        if ( $info['start_time'] > $info['end_time'] )
        {
            if  ( $info['start_time'] < date("H:i") && $info['end_time'] < date("H:i") || $info['start_time'] > date("H:i") && $info['end_time'] > date("H:i") )
            {
                $work = 1;
                break;
            }
        }
        else
        {
            if  ( $info['start_time'] < date("H:i") && $info['end_time'] > date("H:i") )
            {
                $work = 1;
                break;
            }
        }
    }

    return $work;
}



echo json_encode($result);
?>

