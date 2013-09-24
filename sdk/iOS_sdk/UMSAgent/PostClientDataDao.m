/**
 * Cobub Razor
 *
 * An open source analytics iphone sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

#import "PostClientDataDao.h"
#include "Global.h"
#include "network.h"
#import "NSDictionary_JSONExtensions.h"
#import "ClientData.h"

@implementation PostClientDataDao

+(CommonReturn *) postClient:(NSString *) appkey deviceInfo:(ClientData *) deviceInfo
{
    @autoreleasepool {
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/postClientData"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:deviceInfo.platform forKey:@"platform"];
    [requestDictionary setObject:deviceInfo.os_version forKey:@"os_version"];
    [requestDictionary setObject:deviceInfo.language forKey:@"language"];
    [requestDictionary setObject:deviceInfo.resolution forKey:@"resolution"];
    [requestDictionary setObject:deviceInfo.deviceid forKey:@"deviceid"];
    [requestDictionary setObject:appkey forKey:@"appkey"];
    [requestDictionary setObject:deviceInfo.userid forKey:@"userid"];
    if(deviceInfo.mccmnc!=nil)
    {
        [requestDictionary setObject:deviceInfo.mccmnc forKey:@"mccmnc"];
    }
    else
    {
        [requestDictionary setObject:@"" forKey:@"mccmnc"];
            
    }
    [requestDictionary setObject:deviceInfo.version forKey:@"version"];
    [requestDictionary setObject:deviceInfo.network forKey:@"network"];
    [requestDictionary setObject:deviceInfo.devicename forKey:@"devicename"];
    [requestDictionary setObject:deviceInfo.modulename forKey:@"modulename"];
    [requestDictionary setObject:deviceInfo.time forKey:@"time"];
    [requestDictionary setObject:deviceInfo.isjailbroken forKey:@"isjailbroken"];
    NSString *retString = [network SendData:url data:requestDictionary];
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    if(!error)
    {
        ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
        ret.msg = [retDictionary objectForKey:@"msg"];
    }
    return ret;
    }
}

+(CommonReturn *) postUsingTime:(NSString *) appkey sessionMills:(NSString *)sessionMills startMils:(NSString*)startMils endMils:(NSString*)endMils duration:(NSString*)duration activity:(NSString *) activity version:(NSString *) version
{
    NSLog(@"version %@",version);
    @autoreleasepool {
        NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/postActivityLog"];
        CommonReturn *ret = [[CommonReturn alloc] init];
        NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
        [requestDictionary setObject:sessionMills forKey:@"session_id"];
        [requestDictionary setObject:startMils forKey:@"start_millis"];
        [requestDictionary setObject:endMils forKey:@"end_millis"];
        [requestDictionary setObject:duration forKey:@"duration"];
        [requestDictionary setObject:activity forKey:@"activities"];
        [requestDictionary setObject:appkey forKey:@"appkey"];
        [requestDictionary setObject:version forKey:@"version"];
        NSString *retString = [network SendData:url data:requestDictionary];
        NSError *error = nil;
        NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
        if(!error)
        {
            ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
            ret.msg = [retDictionary objectForKey:@"msg"];
        }
        return ret;
    }
}

+(CommonReturn *) postArchiveLogs:(NSMutableDictionary *) archiveLogs
{
    @autoreleasepool {
        NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/uploadLog"];
        CommonReturn *ret = [[CommonReturn alloc] init];
        NSString *retString = [network SendData:url data:archiveLogs];        
        NSError *error = nil;
        NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
        if(!error)
        {
            ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
            ret.msg = [retDictionary objectForKey:@"msg"];
        }
        return ret;
    }
}

+(CommonReturn *) postErrorLog:(NSString *) appkey errorLog:(ErrorLog *) errorLog
{
    @autoreleasepool {
        NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/postErrorLog"];
        CommonReturn *ret = [[CommonReturn alloc] init];
        NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
        [requestDictionary setObject:errorLog.time forKey:@"time"];
        [requestDictionary setObject:errorLog.stackTrace forKey:@"stacktrace"];
        [requestDictionary setObject:errorLog.version forKey:@"version"];
        [requestDictionary setObject:errorLog.osVersion forKey:@"os_version"];
        [requestDictionary setObject:errorLog.deviceID forKey:@"deviceid"];
        [requestDictionary setObject:appkey forKey:@"appkey"];
        [requestDictionary setObject:errorLog.activity forKey:@"activity"];
        NSString *retString = [network SendData:url data:requestDictionary];        
        NSError *error = nil;
        NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
        if(!error)
        {
            ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
            ret.msg = [retDictionary objectForKey:@"msg"];
        }
        return ret;
    }
}

@end
