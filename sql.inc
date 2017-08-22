<?php
//Библиотека SQL запросов


//Продажи за период:файл sql_interval.php
function sqltext_sell($start,$end,$menu)
{
return "select * from MY_PRC_RP_GET_SELL_REST_BACK ('$start','$end 23:59:59',$menu)";
}


//Сальдо клиентов:файл saldo.php
function sqltext_saldo() 
{
return "select
    dic_client.code_name,
    acc_client_saldo.sumdoh-acc_client_saldo.sumvit SALDO
from dic_client
   inner join acc_client_saldo on (dic_client.id = acc_client_saldo.client_id)
where
   (
      (acc_client_saldo.sumdoh - acc_client_saldo.sumvit) <>0
   )
   GROUP BY  dic_client.code_name,SALDO";	
} 

//Справочник номеров
function sqltext_room() 
{
	return "select 
    dic_rooms_fund.id,
    dic_rooms_fund.name
from dic_rooms_fund
	order by dic_rooms_fund.name";
}

/***************************************/
function sqltext_reservation($start,$end)  
{
return "select 
    dic_rooms_fund.name name_room,
    jor_ht_accom.room_id,
    jor_ht_reservation.num,
    jor_ht_reservation_dt.status,
    dic_client.code_name,
    jor_ht_accom.date_start,
    jor_ht_accom.date_start date_start_default,
    jor_ht_accom.date_stop,
    jor_ht_accom.date_stop date_stop_default 
from dic_rooms_fund
   inner join jor_ht_accom on (dic_rooms_fund.id = jor_ht_accom.room_id)
   inner join jor_ht_reservation_dt on (jor_ht_accom.hd_id = jor_ht_reservation_dt.id)
   inner join jor_ht_reservation on (jor_ht_reservation_dt.hd_id = jor_ht_reservation.id)
   inner join dic_client on (jor_ht_reservation.client_id = dic_client.id)
where 
   (
      (jor_ht_accom.date_start between '$start' and '$end')
   or 
      (jor_ht_accom.date_stop between '$start' and '$end')
   or (jor_ht_accom.date_start<'$start' and jor_ht_accom.date_stop>'$end')  
   )
order by dic_rooms_fund.name";
}

/*Журнал  чеков:файл checks.php*/
function sql_checks($start,$end,$id)
{
	$text=" select
    jor_checks.id,
    jor_checks.date_time,
    jor_checks.num,
    dic_client.code_name,
    dic_subdivision.name subdivision,
    sum( jor_checks_dt.orig_sum ) sum_of_orig_sum,
    sum( jor_checks_dt.sum_) sum_of_sum_,
    (select sum(jor_cash_dt.sum_row) from jor_cash_dt where (jor_cash_dt.doc_id=jor_checks.id)) sum_of_cache,
    (select sum(jor_card_dt.sum_row) from jor_card_dt where (jor_card_dt.doc_id=jor_checks.id)) sum_of_card

from jor_checks_dt
   inner join jor_checks on (jor_checks_dt.hd_id = jor_checks.id)
   inner join dic_client on (jor_checks.client_id = dic_client.id)
   inner join dic_subdivision on (jor_checks.subdivision_id=dic_subdivision.id)
   where 
   (
      (jor_checks.date_time between '$start' and '$end 23:59:59') and (jor_checks_dt.REFUSE_TIME is NULL)
   and 
      (jor_checks.doc_state = 1)";
 if($id<>0){$text.=" and (jor_checks.subdivision_id=$id)";}
$text.=") group by jor_checks.date_time, jor_checks.num, dic_client.code_name, jor_checks.id,dic_subdivision.name";
	return $text;
}

/*Журнал детализации чеков:файл checks.php*/
function sql_checks_dt($id)
{
	return 	"select
jor_checks.date_time,
jor_checks_dt.hd_id,
dic_goods.name,
sum (jor_checks_dt.cnt) cnt,
jor_checks_dt.orig_price,
jor_checks_dt.price,
sum(jor_checks_dt.orig_sum) orig_sum,
sum(jor_checks_dt.sum_) sum_
from jor_checks_dt
inner join jor_checks on (jor_checks_dt.hd_id = jor_checks.id)
inner join dic_goods on (jor_checks_dt.goods_id = dic_goods.id)
where
((jor_checks_dt.hd_id=$id)and (jor_checks_dt.REFUSE_TIME is NULL))
group by jor_checks.date_time,jor_checks_dt.hd_id, dic_goods.name,jor_checks_dt.orig_price, jor_checks_dt.price";
}

/*Журнал кассы:файл cashe.php*/
function sql_cashe($start,$end,$id)
{
	$text="select 
    jor_cash.id,
    jor_cash.num,
    jor_cash.date_time,
    jor_cash.doc_sum,
    case jor_cash.contragent_type
    		when 0  then (select dic_org.name from dic_org where (dic_org.id = jor_cash.org_id))
   		    when 1  then  (select dic_employee.code_name from dic_employee where (dic_employee.id = jor_cash.employee_id))
    		when 2  then  (select dic_client.code_name from dic_client where (dic_client.id = jor_cash.client_id))
    end contragent,
    case  jor_cash.doc_type
          when 0 then 'Приход'
          when 1 then 'Расход'
     end type,
    dic_subdivision.name,
    dic_io_items.name io_items
from jor_cash
   inner join dic_subdivision on (jor_cash.subdivision_id = dic_subdivision.id)
   inner join dic_io_items on (jor_cash.io_item_id = dic_io_items.id)
where 
   (
      (jor_cash.date_time between '$start' and '$end 23:59:59') and (jor_cash.doc_state=1)";
 if($id<>0){$text.=" and (jor_cash.subdivision_id=$id)";}
 $text.= ")";
 return $text;
}

/*Журнал детализации кассы:файл cashe_dt.php*/
function sql_cashe_dt($id)
{
	return 	"select
	case jor_cash_dt.doc_type
	when 0 then 'Чек'
	when 1 then 'Чек отеля'
	when 2 then 'Приходная накладная'
	when 3 then 'Чек фитнеса'
	when 4 then 'Расходная накладная'
	when 5 then 'Ведомость зарплат'
	when 6 then 'Договор'
	when 7 then 'Акт выполненных работ'
	when 8 then 'Счета на оплату'
end type,
jor_cash_dt.sum_row	
from jor_cash_dt
where (jor_cash_dt.hd_id=$id)";
}

/*Инкасация*/
function sqltext_incassation()
{
	return "select
	dic_subdivision.name,
	sum( jor_cash.doc_sum* case jor_cash.doc_type
	when 0 then 1
	when 1 then -1
	end) doc_sum_
	
	
	from jor_cash
	inner join dic_subdivision on (jor_cash.subdivision_id = dic_subdivision.id)
	where
	(
	(jor_cash.doc_state = 1) and jor_cash.io_item_id=30 
	
	)
	group by  dic_subdivision.name";
}

/*Касса*/
function sqltext_kassa()
{
	return "select
	dic_subdivision.name,
	sum( jor_cash.doc_sum* case jor_cash.doc_type
	when 0 then 1
	when 1 then -1
	end) doc_sum_


	from jor_cash
	inner join dic_subdivision on (jor_cash.subdivision_id = dic_subdivision.id)
	where
	(
	(jor_cash.doc_state = 1)

	)
	group by  dic_subdivision.name";
}

/*Приход за период*/
function sqltext_r_bill_in($start,$end)
{
	$text= "WITH RECURSIVE
 Rec (id, parent_id, name)
 AS (
   SELECT id, parent_id, name FROM dic_goods_grp WHERE parent_id=0 and id<>0
   UNION ALL
   SELECT dic_goods_grp.id,dic_goods_grp.parent_id, rec.name
    FROM Rec, dic_goods_grp
    WHERE Rec.id = dic_goods_grp.parent_id
   )
	select p.name,p.cnt,p.unit,
	(select rec.name from rec where p.grp_id=rec.id) AS NAME_P		
			from (select 
    dic_goods.name,
    dic_goods.grp_id,
    sum( jor_bill_in_dt.cnt ) cnt,
    dic_unit.name unit
    from dic_unit
   inner join jor_bill_in_dt on (dic_unit.id = jor_bill_in_dt.unit_id)
   inner join jor_bill_in on (jor_bill_in_dt.hd_id = jor_bill_in.id)
   inner join dic_goods on (jor_bill_in_dt.goods_id = dic_goods.id)
where 
   (
      (jor_bill_in.date_time between '$start' and '$end')
   )
group by dic_goods.name,dic_goods.grp_id, jor_bill_in_dt.unit_id, dic_unit.name) p
	order by NAME_P";
	return $text;
}


/*Продажа за период*/
function sqltext_r_sel_interval($start,$end,$id){
$text= "WITH RECURSIVE
 Rec (id, parent_id, name)
 AS (
   SELECT id, parent_id, name FROM dic_goods_grp WHERE parent_id=0 and id<>0
   UNION ALL
   SELECT dic_goods_grp.id,dic_goods_grp.parent_id, rec.name
    FROM Rec, dic_goods_grp
    WHERE Rec.id = dic_goods_grp.parent_id
   )


  select p.name,
         p.cnt,
         (select dic_unit.name from dic_unit where dic_unit.id=p.unit_id) as unit,
         (select rec.name from rec where p.grp_id=rec.id) AS NAME_P
   from 
 (select
   dic_goods.name,
    dic_goods.grp_id,
    dic_goods.unit_id,
  sum(jor_checks_dt.cnt) cnt

from dic_goods
   inner join jor_checks_dt on (dic_goods.id = jor_checks_dt.goods_id)
   inner join jor_checks on (jor_checks_dt.hd_id = jor_checks.id)

where 
   (
      (jor_checks.date_time between '$start' and '$end') and (jor_checks_dt.REFUSE_TIME is NULL)";

if($id<>0){$text.=" and (jor_checks.subdivision_id=$id)";}

$text.= " and 
      (jor_checks.doc_state = 1)
   )

  group by dic_goods.name, dic_goods.grp_id,dic_goods.unit_id) p 
 order by NAME_P,name";
return $text;
}

/*Продажа за период с разбитием на ингридиенты */
function sqltext_r_sel_interval_dt($start,$end,$id){
$text=" with RECURSIVE
 Rec (id, parent_id, name)
 AS (
   SELECT id, parent_id, name FROM dic_goods_grp WHERE parent_id=0 and id<>0
   UNION ALL
   SELECT dic_goods_grp.id,dic_goods_grp.parent_id, rec.name
    FROM Rec, dic_goods_grp
    WHERE Rec.id = dic_goods_grp.parent_id
   ),
 Cost (goods_id,ingr_id,unit_id,gross) as( select cost_card.goods_id,dic_cost_cards_dt.goods_id ingr_id,dic_cost_cards_dt.unit_id,dic_cost_cards_dt.gross
from
(select
    dic_cost_cards.goods_id,
    (select first 1 dic_cost_cards_date.id from dic_cost_cards_date where(dic_cost_cards_date.hd_id = dic_cost_cards.id) order by dic_cost_cards_date.date_from desc) cost_card_id
from dic_cost_cards) cost_card

 inner join dic_cost_cards_dt on (dic_cost_cards_dt.date_id=cost_card.cost_card_id))


select result.result_goods_id,
       (select dic_goods.name from dic_goods where dic_goods.id=result.result_goods_id) name,
        result.result_unit_id,
       (select dic_unit.name from dic_unit where dic_unit.id= result.result_unit_id) unit,
       (select rec.name from rec where rec.id=(select dic_goods.grp_id from dic_goods where dic_goods.id=result.result_goods_id)) AS NAME_P,
       sum(result.result_gross) cnt from
(select
    jor_checks_dt.goods_id,
    jor_checks_dt.cnt,
    (select dic_goods.unit_id from dic_goods where jor_checks_dt.goods_id=dic_goods.id) goods_unit,
    cost.ingr_id,
    cost.unit_id,
    cost.gross,
    case
    when cost.ingr_id is null then jor_checks_dt.goods_id
    else cost.ingr_id
    end result_goods_id ,
    case
    when cost.unit_id is null then  (select dic_goods.unit_id from dic_goods where jor_checks_dt.goods_id=dic_goods.id) 
    else cost.unit_id
    end result_unit_id,
    case 
    when cost.gross is null then  jor_checks_dt.cnt
    else cost.gross*jor_checks_dt.cnt
    end result_gross
from jor_checks_dt

   inner join jor_checks on (jor_checks_dt.hd_id = jor_checks.id)
   left outer join cost on  (jor_checks_dt.goods_id=cost.goods_id)

where 
   ((jor_checks.date_time between '$start' and '$end') and  (jor_checks.doc_state=1) and  (jor_checks_dt.refuse_time is null)";

if($id<>0){ $text.=" and (jor_checks.subdivision_id=$id)";}

$text.=")) result
 group by result.result_goods_id, result.result_unit_id
 order by  NAME_P,name";	
return $text;
}

function sqltext_user_passwod($user,$password)
{
	
	return "select sec_users.id,sec_users.user_login,sec_users.user_password from sec_users where user_login='$user' and user_password='".md5($password)."'";
}

//Справочник групп
function sqltext_dic_grp($name){
$text="select id,parent_id,name from {$name}_GRP";
return $text;
}

//Справочник товаров
function sqltext_DIC_GOODS_item($groupId,$name){
$text="select id,name from $name where grp_id=$groupId";
return $text;
}

//Справочник клиентов
function sqltext_DIC_CLIENT_item($groupId,$name){
$text="select id,code_name name from $name where grp_id=$groupId order by code_name";
return $text;
}

//Наценки
function sqltext_markup($grp_id,$type,$markup){
$text2='';
$text="select price_gname,goods_name,price,last_in_price,nacenka from MY_DIC_PRICE_LIST($grp_id) where is_active=1 and GRP_ID<>154";

switch($type){
case 'min': $text2=" and price<last_in_price";break;
case 'low' :$text2=" and nacenka<$markup";break;
case 'hi' :$text2=" and nacenka>$markup";break;
}
$text.=$text2;
return $text;
	}


?>
