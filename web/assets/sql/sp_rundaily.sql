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
DECLARE col VARCHAR(16); 
DECLARE days INT; 
DECLARE d INT; 

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

-- generate the count of new users for yesterday

-- for channels, versions
INSERT INTO umsinstall_sum_reserveusers_daily 
            (startdate_sk, 
             enddate_sk, 
             product_id, 
             version_name, 
             channel_name, 
             usercount) 
SELECT (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = yesterday)     startdate_sk, 
       (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = yesterday)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue = yesterday 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.version_name,
          p.channel_name with rollup
union
SELECT (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = yesterday)     startdate_sk, 
       (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = yesterday)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue = yesterday 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.channel_name,
          p.version_name with rollup
ON DUPLICATE KEY UPDATE usercount=VALUES(usercount);

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','umsinstall_sum_reserveusers_daily new users for app,version,channel dimensions',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set d = 1;
while d<=8 do
  begin
    set col = concat('day',d);

    set days = -d;
    
    set s = now();
    
    -- 8 days for app,channel, version
    SET @sql=concat(
        'insert into umsinstall_sum_reserveusers_daily(startdate_sk, enddate_sk, product_id, version_name,channel_name,',
        col,
        ')
        Select 
        (select date_sk from umsinstall_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) startdate,
        (select date_sk from umsinstall_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        umsinstall_fact_clientdata f, umsinstall_dim_date d, umsinstall_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue = \'',yesterday,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from umsinstall_fact_clientdata ff, umsinstall_dim_product pp, umsinstall_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.version_name,p.channel_name with rollup
         union
         Select 
        (select date_sk from umsinstall_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) startdate,
        (select date_sk from umsinstall_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        umsinstall_fact_clientdata f, umsinstall_dim_date d, umsinstall_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue = \'',yesterday,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from umsinstall_fact_clientdata ff, umsinstall_dim_product pp, umsinstall_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.channel_name,p.version_name with rollup
        on duplicate key update ',col,'=values(',col,');');
        
    
    PREPARE sl FROM @sql;
    EXECUTE sl;
    DEALLOCATE PREPARE sl;
    
    set e = now();
    insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily',concat('umsinstall_sum_reserveusers_daily DAY ',-d,' reserve users for app,channel,version dimensions'),s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
    set d = d + 1; 
  end;
end while;

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
