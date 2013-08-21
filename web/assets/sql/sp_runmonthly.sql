SET NAMES 'utf8';
--$$

CREATE  PROCEDURE `runmonthly`(IN `begindate` DATE, IN `enddate` DATE)
    NO SQL
begin


declare s datetime;
declare e datetime;
DECLARE col VARCHAR(16); 
DECLARE months INT; 
DECLARE m INT; 

insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('runmonthly','-----start runmonthly-----',now());
    
set s = now();
-- new users for monthly reserve. for each channel, each version
INSERT INTO umsinstall_sum_reserveusers_monthly 
            (startdate_sk, 
             enddate_sk, 
             product_id, 
             version_name, 
             channel_name, 
             usercount) 
SELECT (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'), 
       ifnull(p.channel_name,'all'),
       Count(DISTINCT f.deviceidentifier) count 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
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
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'), 
       ifnull(p.channel_name,'all'),
       Count(DISTINCT f.deviceidentifier) count 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
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
    values('runmonthly','umsinstall_sum_reserveusers_monthly new users for app,version,channel dimensions',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set m = 1;
while m<=8 do
  begin
    set col = concat('month',m);

    set months = -m;
    set s = now();
    
    -- 8 months for each channel, each version
    SET @sql=Concat(
        'insert into umsinstall_sum_reserveusers_monthly(startdate_sk, enddate_sk, product_id,version_name,channel_name,',
        col,
        ') Select
        (select date_sk from umsinstall_dim_date where datevalue = date_add(\'',begindate,'\',interval ',months,' MONTH)) startdate,
        (select date_sk from umsinstall_dim_date where datevalue = last_day(\'',enddate,'\' + interval ',months,' MONTH)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        umsinstall_fact_clientdata f, umsinstall_dim_date d, umsinstall_dim_product p where f.date_sk = d.date_sk and 
        f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active = 1 
        and p.channel_active = 1 and p.version_active = 1 and exists
        (select 1 from umsinstall_fact_clientdata ff, umsinstall_dim_product pp, umsinstall_dim_date dd 
        where ff.product_sk = pp.product_sk and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and 
        dd.datevalue between date_add(\'',begindate,'\',interval ',months,' MONTH) and last_day(\'',enddate,'\' + interval ',months,' MONTH) and 
        ff.deviceidentifier = f.deviceidentifier and pp.product_active = 1 and pp.channel_active = 1 and 
        pp.version_active = 1 and ff.isnew = 1 ) group by p.product_id,p.version_name,p.channel_name with rollup
        union
        Select
        (select date_sk from umsinstall_dim_date where datevalue = date_add(\'',begindate,'\',interval ',months,' MONTH)) startdate,
        (select date_sk from umsinstall_dim_date where datevalue = last_day(\'',enddate,'\' + interval ',months,' MONTH)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        umsinstall_fact_clientdata f, umsinstall_dim_date d, umsinstall_dim_product p where f.date_sk = d.date_sk and 
        f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active = 1 
        and p.channel_active = 1 and p.version_active = 1 and exists
        (select 1 from umsinstall_fact_clientdata ff, umsinstall_dim_product pp, umsinstall_dim_date dd 
        where ff.product_sk = pp.product_sk and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and 
        dd.datevalue between date_add(\'',begindate,'\',interval ',months,' MONTH) and last_day(\'',enddate,'\' + interval ',months,' MONTH) and 
        ff.deviceidentifier = f.deviceidentifier and pp.product_active = 1 and pp.channel_active = 1 and 
        pp.version_active = 1 and ff.isnew = 1 ) group by p.product_id,p.channel_name,p.version_name with rollup
        on duplicate key update ',
        col,
        '= values(',
        col,
        ');');
    
    PREPARE sl FROM @sql;
    EXECUTE sl;
    DEALLOCATE PREPARE sl;
    
    set e = now();
    insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly',concat('umsinstall_sum_reserveusers_monthly MONTH ',-m,' reserve users for app,channel,version dimensions'),s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    set s = now();
    

    
    set m = m + 1; 
  end;
end while;

set s = now();
INSERT INTO umsinstall_sum_basic_activeusers 
            (product_id, 
             month_activeuser, 
             month_percent) 
SELECT p.product_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   umsinstall_fact_clientdata ff, 
                                                    umsinstall_dim_date dd, 
                                                    umsinstall_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            pp.product_id = p.product_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND ff.date_sk = dd.date_sk) 
                                          percent 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id 
ON DUPLICATE KEY UPDATE month_activeuser=VALUES(month_activeuser),month_percent=VALUES(month_percent);

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly','umsinstall_sum_basic_activeusers active users and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
    
set s = now();
INSERT INTO umsinstall_sum_basic_channel_activeusers 
            (date_sk, 
             product_id, 
             channel_id, 
             activeuser, 
             percent, 
             flag) 
SELECT (SELECT date_sk 
        FROM   umsinstall_dim_date 
        WHERE  datevalue = begindate)     startdate, 
       p.product_id, 
       p.channel_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   umsinstall_fact_clientdata ff, 
                                                    umsinstall_dim_date dd, 
                                                    umsinstall_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            pp.product_id = p.product_id 
                                                    AND 
                                            pp.channel_id = p.channel_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND 
       ff.date_sk = dd.date_sk), 
       1 
FROM   umsinstall_fact_clientdata f, 
       umsinstall_dim_date d, 
       umsinstall_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id, 
          p.channel_id 
ON DUPLICATE KEY UPDATE activeuser = VALUES(activeuser),percent=VALUES(percent);

set e = now();
insert into umsinstall_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly','umsinstall_sum_basic_channel_activeusers channel activeusers and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into umsinstall_log(op_type,op_name,op_starttime) 
    values('runmonthly','-----finish runmonthly-----',now());

end;