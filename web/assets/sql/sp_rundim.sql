SET NAMES 'utf8';
--$$

CREATE  PROCEDURE `rundim`()
    NO SQL
begin
declare s datetime;
declare e datetime;


-- dim location --
set s = now();

/* dim_location */
insert into umsinstall_dim_location
           (country,
            region,
            city)
select distinct country,
                region,
                city
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select 1
                   from   umsinstall_dim_location b
                   where  a.country = b.country
                          and a.region = b.region
                          and a.city = b.city);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_location',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- devicebrand ----
set s = now();

insert into umsinstall_dim_devicebrand(devicebrand_name)
select distinct devicename
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select 1
                   from   umsinstall_dim_devicebrand b
                   where  a.devicename = b.devicebrand_name);
 insert into umsinstall_dim_deviceos
           (deviceos_name)
select distinct osversion
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select *
                   from   umsinstall_dim_deviceos b
                   where  b.deviceos_name = a.osversion);
                   
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_deviceos',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- devicelanguage ----
set s = now();

insert into umsinstall_dim_devicelanguage
           (devicelanguage_name)
select distinct language
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select *
                   from   umsinstall_dim_devicelanguage b
                   where  a.language = b.devicelanguage_name);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_devicelanguage',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- resolution ----
set s = now();
insert into umsinstall_dim_deviceresolution
           (deviceresolution_name)
select distinct resolution
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select *
                   from   umsinstall_dim_deviceresolution b
                   where  a.resolution = b.deviceresolution_name);
                   
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_deviceresolution',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- devicesupplier ----
set s = now();
insert into umsinstall_dim_devicesupplier
           (devicesupplier_name)
select distinct service_supplier
from   databaseprefix.umsdatainstall_clientdata a
where  not exists (select *
                   from   umsinstall_dim_devicesupplier b
                   where  a.service_supplier = b.devicesupplier_name);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_devicesupplier',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- dim_product ----
set s = now();
update 
umsinstall_dim_product dp, 
databaseprefix.umsdatainstall_product p,
       databaseprefix.umsdatainstall_channel_product cp,
       databaseprefix.umsdatainstall_channel c,
       databaseprefix.umsdatainstall_clientdata cd,
       databaseprefix.umsdatainstall_product_category pc,
       databaseprefix.umsdatainstall_platform pf
set 
    dp.product_name = p.name,
    dp.product_type = pc.name,
    dp.product_active = p.active,
    dp.channel_name = c.channel_name,
    dp.channel_active = c.active,
    dp.product_key = cd.productkey,
    dp.version_name = cd.version,
    dp.platform = pf.name
where
    p.id = cp.product_id and
    cp.channel_id = c.channel_id and 
    cp.productkey = cd.productkey and 
    p.category = pc.id and 
    c.platform = pf.id and
    dp.product_id = p.id and 
    dp.channel_id = c.channel_id and 
    dp.version_name = cd.version and
    dp.userid = cp.user_id and 
    (dp.product_name <> p.name or 
    dp.product_type <> pc.name or 
    dp.product_active = p.active or 
    dp.channel_name = c.channel_name or 
    dp.channel_active = c.active or 
    dp.product_key = cd.productkey or 
    dp.version_name = cd.version or 
        dp.platform <> pf.name );
insert into umsinstall_dim_product
           (product_id,
            product_name,
            product_type,
            product_active,
            channel_id,
            channel_name,
            channel_active,
            product_key,
            version_name,
            version_active,
            userid,
            platform)
select distinct 
p.id,
p.name,
pc.name,
p.active,
c.channel_id,
c.channel_name,
c.active,
cd.productkey,
                cd.version,
                1,
                cp.user_id,
                pf.name
from  databaseprefix.umsdatainstall_product p inner join
       databaseprefix.umsdatainstall_channel_product cp on p.id = cp.product_id inner join
       databaseprefix.umsdatainstall_channel c on cp.channel_id = c.channel_id inner join
       databaseprefix.umsdatainstall_product_category pc on p.category = pc.id inner join
       databaseprefix.umsdatainstall_platform pf on c.platform = pf.id inner join (select distinct
       productkey,version from databaseprefix.umsdatainstall_clientdata) cd on cp.productkey = cd.productkey  
       and not exists (select 1
                       from   umsinstall_dim_product dp
                       where  dp.product_id = p.id and
                               dp.channel_id = c.channel_id and
                               dp.version_name = cd.version and
                               dp.userid = cp.user_id);
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_product',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- dim_network ----
set s = now();                               
                               
insert into umsinstall_dim_network
           (networkname)
select distinct cd.network
from  databaseprefix.umsdatainstall_clientdata cd
where  not exists (select 1
                       from   umsinstall_dim_network nw
                       where  nw.networkname = cd.network);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_network',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- activity ----
set s = now();   

insert into umsinstall_dim_activity  (activity_name,product_id)
select distinct f.activities,p.id
from   databaseprefix.umsdatainstall_clientusinglog f,databaseprefix.umsdatainstall_product p,databaseprefix.umsdatainstall_channel_product cp
where  
f.appkey = cp.productkey and 
cp.product_id = p.id
and not exists (select 1
                   from   umsinstall_dim_activity a
                   where  a.activity_name = f.activities
and a.product_id = p.id);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_activity',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- errirtitle ----
set s = now();
insert into umsinstall_dim_errortitle
           (title_name,isfix)
select distinct f.title,0
from   databaseprefix.umsdatainstall_errorlog f
where  not exists (select *
                   from   umsinstall_dim_errortitle ee
                   where  ee.title_name = f.title);
                   
-- dim_event
-- update dim_event
set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_errortitle',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- event ----
set s = now();
update umsinstall_dim_event e,databaseprefix.umsdatainstall_event_defination d
set e.eventidentifier = d.event_identifier,
e.eventname = d.event_name,
e.product_id = d.product_id,
e.active = d.active
where e.event_id = d.event_id and (e.eventidentifier <> d.event_identifier or e.eventname<>d.event_name or e.product_id <> d.product_id or e.active <> d.active);


insert into umsinstall_dim_event       (eventidentifier,eventname,active,product_id,createtime,event_id)
select distinct event_identifier,event_name,active,product_id,create_date,f.event_id
from   databaseprefix.umsdatainstall_event_defination f
where  not exists (select *
                   from   umsinstall_dim_event ee
                   where  ee.eventidentifier = f.event_identifier
and ee.eventname = f.event_name
and ee.active = f.active
and ee.product_id = f.product_id
and ee.createtime = f.create_date);

set e = now();
insert into umsinstall_log(op_type,op_name,op_date,affected_rows,duration) 
    values('rundim','umsinstall_dim_event',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
end;