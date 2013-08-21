SET NAMES 'utf8';
--$$

CREATE  PROCEDURE `rundaily`(IN `yesterday` DATE)
    NO SQL

begin

declare csession varchar(128);
declare clastsession varchar(128);

declare cactivityid int;
declare clastactivityid int;

declare cproductsk int;
declare clastproductsk int;
declare s datetime;
declare e datetime;
declare single int;
declare endflag int;
declare seq int;

declare usinglogcursor cursor

for

select product_sk,session_id,activity_sk from umsinstall_fact_usinglog f, umsinstall_dim_date d where f.date_sk = d.date_sk

and d.datevalue = yesterday;

declare continue handler for not found set endflag = 1;

set endflag = 0;

set clastactivityid = -1;
set single = 0;

insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('rundaily','-----start rundaily-----',now());

set s = now();

open usinglogcursor;

repeat

  fetch usinglogcursor into cproductsk,csession,cactivityid;

  if csession=clastsession then
      update umsinstall_sum_accesspath set count=count+1 
      where product_sk=cproductsk and fromid=clastactivityid 
      and toid=cactivityid and jump=seq;
      
      if row_count()=0 then 
      insert into umsinstall_sum_accesspath(product_sk,fromid,toid,jump,count)
      select cproductsk,clastactivityid,cactivityid,seq,1;
      end if;
    set seq = seq +1;

  else
     update umsinstall_sum_accesspath set count=count+1 
     where product_sk=clastproductsk and fromid=clastactivityid 
     and toid=-999 and jump=seq;
     
     if row_count()=0 then 
     insert into umsinstall_sum_accesspath(product_sk,fromid,toid,jump,count) 
     select clastproductsk,clastactivityid,-999,seq,1;
     end if;
     set seq = 1;

     end if;

   set clastsession = csession;
   set clastactivityid = cactivityid;
   set clastproductsk = cproductsk;

until endflag=1 end repeat;

close usinglogcursor;

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','umsinstall_sum_accesspath',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();

insert into umsinstall_sum_accesslevel(product_sk,fromid,toid,level,count)
select product_sk,fromid,toid,min(jump),sum(count) from umsinstall_sum_accesspath group by product_sk,fromid,toid
on duplicate key update count = values(count);

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','umsinstall_sum_accesslevel',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();


update umsinstall_fact_clientdata a,umsinstall_fact_clientdata b,umsinstall_dim_date c,
umsinstall_dim_product d,umsinstall_dim_product f set a.isnew=0 where 
((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and a.isnew=1 
and a.date_sk=c.date_sk and c.datevalue between DATE_SUB(yesterday,INTERVAL 7 DAY) and yesterday
and a.product_sk=d.product_sk 
and b.product_sk=f.product_sk 
and a.deviceidentifier=b.deviceidentifier and d.product_id=f.product_id;

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','umsinstall_fact_clientdata recalculate new users',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();


update umsinstall_fact_clientdata a,umsinstall_fact_clientdata b,umsinstall_dim_date c,
umsinstall_dim_product d,umsinstall_dim_product f set a.isnew_channel=0 where 
((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and a.isnew_channel=1 
and a.date_sk=c.date_sk and c.datevalue between DATE_SUB(yesterday,INTERVAL 7 DAY) and yesterday
and a.product_sk=d.product_sk 
and b.product_sk=f.product_sk 
and a.deviceidentifier=b.deviceidentifier and d.product_id=f.product_id and d.channel_id=f.channel_id;

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','umsinstall_fact_clientdata recalculate new users for channel',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('rundaily','-----finish rundaily-----',now());
    
end;
