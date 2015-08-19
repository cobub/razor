SET NAMES 'utf8';
--$$

CREATE PROCEDURE `runsum`(IN `today` DATE)
    NO SQL
begin
declare s datetime;
declare e datetime;

insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('runsum','-----start runsum-----',now());
    
-- update fact_clientdata  
set s = now();
update  umsinstall_fact_clientdata a,
        umsinstall_fact_clientdata b,
        umsinstall_dim_date c,
        umsinstall_dim_product d,
        umsinstall_dim_product f 
set     a.isnew=0 
where   ((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and     a.isnew=1 
and     a.date_sk=c.date_sk 
and     c.datevalue=today
and     a.product_sk=d.product_sk 
and     b.product_sk=f.product_sk 
and     a.deviceidentifier=b.deviceidentifier 
and     d.product_id=f.product_id;

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_fact_clientdata update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

set s = now();

update umsinstall_fact_clientdata a,
       umsinstall_fact_clientdata b,
       umsinstall_dim_date c,
       umsinstall_dim_product d,
       umsinstall_dim_product f 
set    a.isnew_channel=0 
where  ((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid))
       and a.isnew_channel=1 
       and a.date_sk=c.date_sk 
       and c.datevalue=today 
       and a.product_sk=d.product_sk 
       and b.product_sk=f.product_sk 
       and a.deviceidentifier=b.deviceidentifier 
       and d.product_id=f.product_id 
       and d.channel_id=f.channel_id;

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_fact_clientdata update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum usinglog for each sessions
set s = now();
insert into umsinstall_fact_usinglog_daily
           (product_sk,
            date_sk,
            session_id,
            duration)
select  f.product_sk,
         d.date_sk,
         f.session_id,
         sum(f.duration)
from    umsinstall_fact_usinglog f,
         umsinstall_dim_date d
where   
         d.datevalue = today and f.date_sk = d.date_sk
group by f.product_sk,d.date_sk,f.session_id on duplicate key update duration = values(duration);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_fact_usinglog_daily',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum_basic_product 

set s = now();
insert into umsinstall_sum_basic_product(product_id,date_sk,sessions) 
select p.product_id, d.date_sk,count(f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d,
     umsinstall_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id on duplicate key update sessions = values(sessions);

insert into umsinstall_sum_basic_product(product_id,date_sk,startusers) 
select p.product_id, d.date_sk,count(distinct f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d,
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk=f.product_sk 
group by p.product_id on duplicate key update startusers = values(startusers);

insert into umsinstall_sum_basic_product(product_id,date_sk,newusers) 
select p.product_id, f.date_sk,sum(f.isnew) 
from umsinstall_fact_clientdata f, 
     umsinstall_dim_date d, 
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
group by p.product_id,f.date_sk on duplicate key update newusers = values(newusers);

insert into umsinstall_sum_basic_product(product_id,date_sk,upgradeusers) 
select p.product_id, d.date_sk,
count(distinct f.deviceidentifier) 
from umsinstall_fact_clientdata f, 
     umsinstall_dim_date d, 
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1
      and p.channel_active = 1 
      and p.version_active = 1 
      and exists 
(select 1 
from umsinstall_fact_clientdata ff, 
     umsinstall_dim_date dd, umsinstall_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk
      and pp.product_id = p.product_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
group by p.product_id,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into umsinstall_sum_basic_product(product_id,date_sk,allusers) 
select f.product_id, 
(
 select date_sk 
 from umsinstall_dim_date 
where datevalue=today) date_sk,
sum(f.newusers) 
from umsinstall_sum_basic_product f,
     umsinstall_dim_date d 
where d.date_sk=f.date_sk 
      and d.datevalue<=today 
group by f.product_id on duplicate key update allusers = values(allusers);

insert into umsinstall_sum_basic_product(product_id,date_sk,allsessions) 
select f.product_id,(select date_sk from umsinstall_dim_date where datevalue=today) date_sk,sum(f.sessions) 
from umsinstall_sum_basic_product f,
     umsinstall_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id on duplicate key update allsessions = values(allsessions);

insert into umsinstall_sum_basic_product(product_id,date_sk,usingtime)
select p.product_id,f.date_sk,sum(duration) 
from umsinstall_fact_usinglog_daily f,
     umsinstall_dim_product p,
     umsinstall_dim_date d 
where f.date_sk = d.date_sk 
      and d.datevalue = today 
      and f.product_sk=p.product_sk 
group by p.product_id on duplicate key update usingtime = values(usingtime);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_basic_product',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum_basic_channel 
set s = now();
insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,sessions) 
select p.product_id,p.channel_id,d.date_sk,count(f.deviceidentifier) 
from umsinstall_fact_clientdata f, 
     umsinstall_dim_date d,
     umsinstall_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id,p.channel_id on duplicate key update sessions = values(sessions);

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,startusers) 
select p.product_id,p.channel_id, d.date_sk,count(distinct f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d,
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk=f.product_sk 
group by p.product_id,p.channel_id on duplicate key update startusers = values(startusers);

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,newusers) 
select p.product_id,p.channel_id,f.date_sk,sum(f.isnew_channel) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d, 
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
group by p.product_id,p.channel_id,f.date_sk on duplicate key update newusers = values(newusers);

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,upgradeusers) 
select p.product_id,p.channel_id,d.date_sk,
count(distinct f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d, 
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk  
      and p.product_active = 1 
      and p.channel_active = 1 
     and p.version_active = 1 
and exists 
(select 1 
from umsinstall_fact_clientdata ff,
     umsinstall_dim_date dd,
     umsinstall_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk 
      and pp.product_id = p.product_id 
      and pp.channel_id=p.channel_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
 group by p.product_id,p.channel_id,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,allusers) 
select f.product_id,f.channel_id,
(select date_sk 
  from umsinstall_dim_date 
  where datevalue=today) date_sk,
sum(f.newusers)
from umsinstall_sum_basic_channel f,
     umsinstall_dim_date d
where d.date_sk=f.date_sk 
      and d.datevalue<=today 
group by f.product_id,f.channel_id on duplicate key update allusers = values(allusers); 

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,allsessions) 
select f.product_id,f.channel_id,(select date_sk from umsinstall_dim_date where datevalue=today) date_sk,
sum(f.sessions) 
from umsinstall_sum_basic_channel f,
     umsinstall_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id,f.channel_id on duplicate key update allsessions = values(allsessions);

insert into umsinstall_sum_basic_channel(product_id,channel_id,date_sk,usingtime)
select p.product_id,p.channel_id,f.date_sk,sum(duration) 
from umsinstall_fact_usinglog_daily f,
     umsinstall_dim_product p,
     umsinstall_dim_date d where f.date_sk = d.date_sk 
and d.datevalue = today and f.product_sk=p.product_sk 
group by p.product_id,p.channel_id on duplicate key update usingtime = values(usingtime);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_basic_channel',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
  
    
-- sum_basic_product_version 

set s = now();
insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,sessions) 
select p.product_id, d.date_sk,p.version_name,count(f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d,
     umsinstall_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id,p.version_name on duplicate key update sessions = values(sessions);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,startusers) 
select p.product_id, d.date_sk,p.version_name,count(distinct f.deviceidentifier) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d,
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk
      and p.product_sk=f.product_sk 
group by p.product_id,p.version_name on duplicate key update startusers = values(startusers);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,newusers) 
select p.product_id, f.date_sk,p.version_name,sum(f.isnew) 
from umsinstall_fact_clientdata f,
     umsinstall_dim_date d, 
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk  
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
      group by p.product_id,p.version_name,f.date_sk  
on duplicate key update newusers = values(newusers);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,upgradeusers) 
select p.product_id, d.date_sk,p.version_name,
count(distinct f.deviceidentifier)
from umsinstall_fact_clientdata f, 
     umsinstall_dim_date d,  
     umsinstall_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
      and exists 
(select 1 
from umsinstall_fact_clientdata ff, 
     umsinstall_dim_date dd,
     umsinstall_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk
      and pp.product_id = p.product_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
 group by   p.product_id,p.version_name,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,allusers) 
select f.product_id, 
(select date_sk 
 from umsinstall_dim_date 
where datevalue=today) date_sk,
f.version_name,
sum(f.newusers) 
from umsinstall_sum_basic_product_version f,
     umsinstall_dim_date d
where d.date_sk=f.date_sk 
      and d.datevalue<=today
group by f.product_id,f.version_name on duplicate key update allusers = values(allusers);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,allsessions) 
select f.product_id,(select date_sk from umsinstall_dim_date where datevalue=today) date_sk,f.version_name,sum(f.sessions) 
from umsinstall_sum_basic_product_version f,
     umsinstall_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id,f.version_name on duplicate key update allsessions = values(allsessions);

insert into umsinstall_sum_basic_product_version(product_id,date_sk,version_name,usingtime)
select p.product_id,f.date_sk,p.version_name,sum(duration) 
from umsinstall_fact_usinglog_daily f,
     umsinstall_dim_product p,
     umsinstall_dim_date d 
where f.date_sk = d.date_sk 
      and d.datevalue = today 
      and f.product_sk=p.product_sk 
group by p.product_id,p.version_name on duplicate key update usingtime = values(usingtime);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
values('runsum','umsinstall_sum_basic_product_version',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));  
  

set s = now();
-- update segment_sk column

update umsinstall_fact_usinglog_daily f,umsinstall_dim_segment_usinglog s,umsinstall_dim_date d
set    f.segment_sk = s.segment_sk
where  f.duration >= s.startvalue
       and f.duration < s.endvalue
       and f.date_sk = d.date_sk
       and d.datevalue = today;
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_fact_usinglog_daily update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

set s = now();
-- sum_basic_byhour --
Insert into umsinstall_sum_basic_byhour(product_sk,date_sk,hour_sk,
sessions) 
Select f.product_sk, f.date_sk,f.hour_sk,
count(f.deviceidentifier) from umsinstall_fact_clientdata f, umsinstall_dim_date d
where d.datevalue = today and f.date_sk = d.date_sk
group by f.product_sk,f.date_sk,f.hour_sk on duplicate 
key update sessions = values(sessions);

Insert into umsinstall_sum_basic_byhour(product_sk,date_sk,hour_sk,
startusers) 
Select f.product_sk, f.date_sk,f.hour_sk,
count(distinct f.deviceidentifier) from 
umsinstall_fact_clientdata f, umsinstall_dim_date d where d.datevalue = today  
and f.date_sk = d.date_sk group by f.product_sk,d.date_sk,
f.hour_sk on duplicate key update startusers = values(startusers);

Insert into umsinstall_sum_basic_byhour(product_sk,date_sk,hour_sk,newusers) 
Select f.product_sk, f.date_sk,f.hour_sk,count(distinct f.deviceidentifier) from umsinstall_fact_clientdata f, umsinstall_dim_date d, umsinstall_dim_product p where d.datevalue = today and f.date_sk = d.date_sk and p.product_sk = f.product_sk and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1 and not exists (select 1 from umsinstall_fact_clientdata ff, umsinstall_dim_date dd, umsinstall_dim_product pp where dd.datevalue < today and ff.date_sk = dd.date_sk and pp.product_sk = ff.product_sk and p.product_id = pp.product_id and pp.product_active = 1 and pp.channel_active = 1 and pp.version_active = 1 and f.deviceidentifier = ff.deviceidentifier) group by f.product_sk,f.date_sk,f.hour_sk on duplicate key update newusers = values(newusers);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_basic_byhour',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
-- sum_usinglog_activity --
insert into umsinstall_sum_usinglog_activity(date_sk,product_sk,activity_sk,accesscount,totaltime)
select d.date_sk,p.product_sk,a.activity_sk, count(*), sum(duration)
from        umsinstall_fact_usinglog f,         umsinstall_dim_product p,   umsinstall_dim_date d, umsinstall_dim_activity a
where    f.date_sk = d.date_sk and f.activity_sk = a.activity_sk
         and d.datevalue =today
         and f.product_sk = p.product_sk
         and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1 
group by d.date_sk,p.product_sk,a.activity_sk
on duplicate key update accesscount = values(accesscount),totaltime = values(totaltime);

insert into umsinstall_sum_usinglog_activity(date_sk,product_sk,activity_sk,exitcount)
select tt.date_sk,tt.product_sk, tt.activity_sk,count(*)
from
(select * from(
select   d.date_sk,session_id,p.product_sk,f.activity_sk,endtime
                    from     umsinstall_fact_usinglog f,
                             umsinstall_dim_product p,
                             umsinstall_dim_date d
                    where    f.date_sk = d.date_sk
                             and d.datevalue = today
                             and f.product_sk = p.product_sk
                    order by session_id,
                             endtime desc) t group by t.session_id) tt
group by tt.date_sk,tt.product_sk,tt.activity_sk
order by tt. date_sk,tt.product_sk,tt.activity_sk on duplicate key update
exitcount = values(exitcount);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_usinglog_activity',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into umsinstall_fact_launch_daily
           (product_sk,
            date_sk,
            segment_sk,
            accesscount) 
select rightf.product_sk,
       rightf.date_sk,
       rightf.segment_sk,
       ifnull(ffff.num,0)
from (select  fff.product_sk,
         fff.date_sk,
         fff.segment_sk,
         count(fff.segment_sk) num
         from (select fs.datevalue,
                 dd.date_sk,
                 fs.product_sk,
                 fs.deviceidentifier,
                 fs.times,
                 ss.segment_sk
                 from (select   d.datevalue,
                           p.product_sk,
                           deviceidentifier,
                           count(* ) times
                           from  umsinstall_fact_clientdata f,
                           umsinstall_dim_date d,
                           umsinstall_dim_product p
                           where d.datevalue = today
                           and f.date_sk = d.date_sk
                           and p.product_sk = f.product_sk
                  group by d.datevalue,p.product_sk,deviceidentifier) fs,
                 umsinstall_dim_segment_launch ss,
                 umsinstall_dim_date dd
          where  fs.times between ss.startvalue and ss.endvalue
                 and dd.datevalue = fs.datevalue) fff
group by fff.date_sk,fff.segment_sk,fff.product_sk
order by fff.date_sk,
         fff.segment_sk,
         fff.product_sk) ffff right join (select fff.date_sk,fff.product_sk,sss.segment_sk
         from (select distinct d.date_sk,p.product_sk 
         from umsinstall_fact_clientdata f,umsinstall_dim_date d,umsinstall_dim_product p 
         where d.datevalue=today and f.date_sk=d.date_sk and p.product_sk = f.product_sk) fff cross join
         umsinstall_dim_segment_launch sss) rightf on ffff.date_sk=rightf.date_sk and
         ffff.product_sk=rightf.product_sk and ffff.segment_sk=rightf.segment_sk
          on duplicate key update accesscount = values(accesscount);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_fact_launch_daily',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into umsinstall_sum_location(product_id,date_sk,location_sk,sessions)
select p.product_id,d.date_sk,l.location_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_location l
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.location_sk = l.location_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,l.location_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_location(product_id,date_sk,location_sk,newusers)
select p.product_id,d.date_sk,l.location_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_location l
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.location_sk = l.location_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,l.location_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_location',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));



set s = now();

insert into umsinstall_sum_devicebrand(product_id,date_sk,devicebrand_sk,sessions)
select p.product_id,d.date_sk,b.devicebrand_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_devicebrand b
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicebrand_sk = b.devicebrand_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,b.devicebrand_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_devicebrand(product_id,date_sk,devicebrand_sk,newusers)
select p.product_id,d.date_sk,b.devicebrand_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_devicebrand b
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicebrand_sk = b.devicebrand_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,b.devicebrand_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_devicebrand',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    

set s = now();

insert into umsinstall_sum_deviceos(product_id,date_sk,deviceos_sk,sessions)
select p.product_id,d.date_sk,o.deviceos_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_deviceos o
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceos_sk = o.deviceos_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,o.deviceos_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_deviceos(product_id,date_sk,deviceos_sk,newusers)
select p.product_id,d.date_sk,o.deviceos_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_deviceos o
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceos_sk = o.deviceos_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,o.deviceos_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_deviceos',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    

set s = now();

insert into umsinstall_sum_deviceresolution(product_id,date_sk,deviceresolution_sk,sessions)
select p.product_id,d.date_sk,r.deviceresolution_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_deviceresolution r
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceresolution_sk = r.deviceresolution_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,r.deviceresolution_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_deviceresolution(product_id,date_sk,deviceresolution_sk,newusers)
select p.product_id,d.date_sk,r.deviceresolution_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_deviceresolution r
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceresolution_sk = r.deviceresolution_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,r.deviceresolution_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_deviceresolution',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into umsinstall_sum_devicesupplier(product_id,date_sk,devicesupplier_sk,sessions)
select p.product_id,d.date_sk,s.devicesupplier_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_devicesupplier s
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicesupplier_sk = s.devicesupplier_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,s.devicesupplier_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_devicesupplier(product_id,date_sk,devicesupplier_sk,newusers)
select p.product_id,d.date_sk,s.devicesupplier_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_devicesupplier s
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicesupplier_sk = s.devicesupplier_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,s.devicesupplier_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_devicesupplier',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into umsinstall_sum_devicenetwork(product_id,date_sk,devicenetwork_sk,sessions)
select p.product_id,d.date_sk,n.network_sk, count(*)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_network n
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.network_sk = n.network_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,n.network_sk
on duplicate key update sessions = values(sessions);


insert into umsinstall_sum_devicenetwork(product_id,date_sk,devicenetwork_sk,newusers)
select p.product_id,d.date_sk,n.network_sk, count(distinct f.deviceidentifier)
from     umsinstall_fact_clientdata f,
         umsinstall_dim_product p,
         umsinstall_dim_date d,
         umsinstall_dim_network n
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.network_sk = n.network_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,n.network_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_devicenetwork',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into umsinstall_sum_event(product_sk,date_sk,event_sk, total)
SELECT product_sk,f.date_sk,event_sk,sum(number) FROM `umsinstall_fact_event` f,
         umsinstall_dim_date d
where f.date_sk = d.date_sk  and d.datevalue = today 
group by product_sk,f.date_sk,event_sk
on duplicate key update total=values(total);

insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','umsinstall_sum_event',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('runsum','-----finish runsum-----',now());
    
end;