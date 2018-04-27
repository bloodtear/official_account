<?php
namespace official_account\database;
use framework\Database as fdb;

class Db_broadcast_message extends fdb\Database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new Db_broadcast_message();
        return self::$instance;
    }

    protected function __construct() {
        parent::__construct(MYSQL_PREFIX . "message");
    }

    public function get_broadcast_data() {
        $next_day_stamp = strtotime(date("Y-m-d",strtotime("+1 day")));
        $next_2day_stamp = strtotime(date("Y-m-d",strtotime("+2 day"))) - 1;
        // 在公众号下，
        // 已经 [ 关注 ] 此公众号，并且 [ unionid有关联 ] 的用户
        // 在 [ 明天 ] 拥有的所有活动，包括【自己创建】【单体关注】【单体加入】【关注分类下包含的活动】

        // 在上一段mysql中提取时间戳区间，在明天的时间段内
        // 此操作需要关联activity,所以使用了子查询

        // 然后跨database来进行关联查询。
        $sql = "
            SELECT
                auser.openid, result.*
            FROM
                official_account.official_account_user auser
            INNER JOIN (
                SELECT
                    tempuser.id tempuser_id,
                    tempuser.unionid unionid,
                    tempuser.nickname nickname,
                    count(DISTINCT activity.id) owner_count,
                    count(DISTINCT subscribe.id) sub_count,
                    count(DISTINCT sign.id) sign_count,
                    count(DISTINCT sub_type.id) sub_type_act_count
                FROM
                    my_calendar.my_calendar_tempuser tempuser
                LEFT JOIN (
                    SELECT
                        *
                    FROM
                        my_calendar.my_calendar_activity
                    WHERE
                        begintime > $next_day_stamp
                    AND endtime < $next_2day_stamp
                ) activity ON activity.`owner` = tempuser.id
                LEFT JOIN (
                    SELECT
                        sub.*
                    FROM
                        my_calendar.my_calendar_subscribe sub
                    LEFT JOIN my_calendar.my_calendar_activity act2 ON sub.activity = act2.id
                    WHERE
                        act2.begintime > $next_day_stamp
                    AND act2.endtime < $next_2day_stamp
                ) subscribe ON subscribe.tempid = tempuser.id
                LEFT JOIN (
                    SELECT
                        sig.*
                    FROM
                        my_calendar.my_calendar_sign sig
                    LEFT JOIN my_calendar.my_calendar_activity act3 ON sig.activity = act3.id
                    WHERE
                        act3.begintime > $next_day_stamp
                    AND act3.endtime < $next_2day_stamp
                ) sign ON sign.tempid = tempuser.id
                LEFT JOIN (
                    SELECT
                        sub_type.tempid tempid,
                        sub_type_activity.*
                    FROM
                        my_calendar.my_calendar_subscribe_type sub_type
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            my_calendar.my_calendar_activity
                        WHERE
                            begintime > $next_day_stamp
                        AND endtime < $next_2day_stamp
                    ) sub_type_activity ON sub_type_activity.type = sub_type.typeid
                ) sub_type ON sub_type.tempid = tempuser.id
                GROUP BY
                    tempuser.id
            ) result ON auser.unionid = result.unionid
            ";
        return Db_base::inst()->do_query($sql);
    }
    

    public function all() {
        return $this->get_all();
    }

    public function add($name) {
        return $this->insert(array("name" => $name));
    }

    public function modify($id, $name) {
        $id = (int)$id;
        return $this->update(array("name" => $name), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }


};

/*
// 当前所有用户 拥有的所有活动，包括【自己创建】【单体关注】【单体加入】【关注分类下包含的活动】
SELECT
	tempuser.id tempuser_id,
	tempuser.nickname nickname,
	count(DISTINCT activity.id) owner_count,
	count(DISTINCT subscribe.id) sub_count,
	count(DISTINCT sign.id) sign_count,
	COUNT(DISTINCT act2.id) sub_type_act_count
FROM
	my_calendar.my_calendar_tempuser tempuser
LEFT JOIN my_calendar.my_calendar_activity activity ON activity.`owner` = tempuser.id
LEFT JOIN my_calendar.my_calendar_subscribe subscribe ON subscribe.tempid = tempuser.id
LEFT JOIN my_calendar.my_calendar_sign sign ON sign.tempid = tempuser.id
LEFT JOIN my_calendar.my_calendar_subscribe_type sub_type ON sub_type.tempid = tempuser.id
LEFT JOIN my_calendar.my_calendar_activity act2 ON act2.type = sub_type.typeid
GROUP BY
	tempuser.id
*/


// 在公众号下，
// 已经 [ 关注 ] 此公众号，并且 [ unionid有关联 ] 的用户
// 在 [ 明天 ] 拥有的所有活动，包括【自己创建】【单体关注】【单体加入】【关注分类下包含的活动】

// 在上一段mysql中提取时间戳区间，在明天的时间段内
// 此操作需要关联activity,所以使用了子查询

// 然后跨database来进行关联查询。

/*
SELECT
	auser.openid, result.*
FROM
	official_account.official_account_user auser
INNER JOIN (
	SELECT
		tempuser.id tempuser_id,
		tempuser.unionid unionid,
		tempuser.nickname nickname,
		count(DISTINCT activity.id) owner_count,
		count(DISTINCT subscribe.id) sub_count,
		count(DISTINCT sign.id) sign_count,
		count(DISTINCT sub_type.id) sub_type_act_count
	FROM
		my_calendar.my_calendar_tempuser tempuser
	LEFT JOIN (
		SELECT
			*
		FROM
			my_calendar.my_calendar_activity
		WHERE
			begintime > 1524844800
		AND endtime < 1524931199
	) activity ON activity.`owner` = tempuser.id
	LEFT JOIN (
		SELECT
			sub.*
		FROM
			my_calendar.my_calendar_subscribe sub
		LEFT JOIN my_calendar.my_calendar_activity act2 ON sub.activity = act2.id
		WHERE
			act2.begintime > 1524844800
		AND act2.endtime < 1524931199
	) subscribe ON subscribe.tempid = tempuser.id
	LEFT JOIN (
		SELECT
			sig.*
		FROM
			my_calendar.my_calendar_sign sig
		LEFT JOIN my_calendar.my_calendar_activity act3 ON sig.activity = act3.id
		WHERE
			act3.begintime > 1524844800
		AND act3.endtime < 1524931199
	) sign ON sign.tempid = tempuser.id
	LEFT JOIN (
		SELECT
			sub_type.tempid tempid,
			sub_type_activity.*
		FROM
			my_calendar.my_calendar_subscribe_type sub_type
		LEFT JOIN (
			SELECT
				*
			FROM
				my_calendar.my_calendar_activity
			WHERE
				begintime > 1524844800
			AND endtime < 1524931199
		) sub_type_activity ON sub_type_activity.type = sub_type.typeid
	) sub_type ON sub_type.tempid = tempuser.id
	GROUP BY
		tempuser.id
) result ON auser.unionid = result.unionid
    
*/