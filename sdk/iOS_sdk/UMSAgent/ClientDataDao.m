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

#import "ClientDataDao.h"
#include "Global.h"
#include "NetworkUtility.h"
#import "NSDictionary_JSONExtensions.h"
#import "ClientData.h"
#import "ActivityLog.h"
#import "UMSAgent.h"
#import "EventDao.h"
#import "ErrorDao.h"
#import "TagDao.h"
#import "UsingLogDao.h"
#import <sys/sysctl.h>

@interface ClientDataDao(){
    NSRecursiveLock *lock;
}
@property(nonatomic)NSRecursiveLock *lock;

@end

@implementation ClientDataDao
#define kClientDataArray @"clientDataArray"
@synthesize lock;

+ (ClientDataDao *)getinstance{
    static ClientDataDao *instance = nil;
    if (instance == nil) {
        instance.lock = [[NSRecursiveLock alloc]init];
    }
    return instance;
    
}

+ (CommonReturn *) postClient:(NSString *) appkey deviceInfo:(ClientData *) deviceInfo
{
    @autoreleasepool
    {
        NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/clientdata"];
        CommonReturn *ret = [[CommonReturn alloc] init];
        NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
        
        if(deviceInfo.platform)
        {
            [requestDictionary setObject:deviceInfo.platform forKey:@"platform"];
        }
        if(deviceInfo.os_version)
        {
            [requestDictionary setObject:deviceInfo.os_version forKey:@"os_version"];
        }
        
        if(deviceInfo.language)
        {
            [requestDictionary setObject:deviceInfo.language forKey:@"language"];
        }
        
        if(deviceInfo.resolution)
        {
            [requestDictionary setObject:deviceInfo.resolution forKey:@"resolution"];
        }
        
        if(deviceInfo.deviceid)
        {
            [requestDictionary setObject:deviceInfo.deviceid forKey:@"deviceid"];
        }
        else
        {
            ret.flag = 1;
            ret.msg = @"discard dirty data";
            return ret;
        }
        
        if(appkey)
        {
            [requestDictionary setObject:appkey forKey:@"appkey"];
        }
        else
        {
            ret.flag = 1;
            ret.msg = @"discard dirty data";
            return ret;
        }
        
        
        [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
        if(deviceInfo.sessionId)
        {
            [requestDictionary setObject:deviceInfo.sessionId forKey:@"session_id"];
        }
        
        if(deviceInfo.latitude)
        {
            [requestDictionary setObject:deviceInfo.latitude forKey:@"latitude"];
        }
        
        if(deviceInfo.longitude)
        {
            [requestDictionary setObject:deviceInfo.longitude forKey:@"longitude"];
        }
        if(deviceInfo.mccmnc!=nil)
        {
            [requestDictionary setObject:deviceInfo.mccmnc forKey:@"mccmnc"];
        }
        else
        {
            [requestDictionary setObject:@"" forKey:@"mccmnc"];
            
        }
        if(deviceInfo.version)
        {
            [requestDictionary setObject:deviceInfo.version forKey:@"version"];
        }
        
        if(deviceInfo.network)
        {
            [requestDictionary setObject:deviceInfo.network forKey:@"network"];
        }
        
        if(deviceInfo.devicename)
        {
            [requestDictionary setObject:deviceInfo.devicename forKey:@"devicename"];
        }
        if(deviceInfo.modulename)
        {
            [requestDictionary setObject:deviceInfo.modulename forKey:@"modulename"];
        }
        
        if(deviceInfo.time)
        {
            [requestDictionary setObject:deviceInfo.time forKey:@"time"];
        }
        if(deviceInfo.isjailbroken)
        {
            [requestDictionary setObject:deviceInfo.isjailbroken forKey:@"isjailbroken"];
        }
        if(deviceInfo.lib_version){
            [requestDictionary setObject:deviceInfo.lib_version forKey:@"lib_version"];
        }
        
        NSMutableArray *mArray = [[NSMutableArray alloc] init];
        [mArray addObject:requestDictionary];
        
        NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
        [finalDic setObject:mArray forKey:@"data"];
        
        NSString *retString = [NetworkUtility postData:url data:finalDic];
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

+ (NSMutableArray *)getArchiveClientData:(NSString*)appKey
{
    
    [[ClientDataDao getinstance].lock lock];
    NSData *oldData = [UMSAgent getArchivedLogFromFile:kClientDataArray];
    [[ClientDataDao getinstance].lock unlock];
    
    NSMutableArray * array = nil;
    if (oldData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Have error data num = %lu",(unsigned long)[array count]);
        }
    }
    NSMutableArray *clientDataArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(int i=0;i<[array count];i++)
        {
            //            if(i>=DEFAULT_MAX_CLIENTDATA_COUNT)
            //            {
            //                break;
            //                //Discard old data;
            //            }
            ClientData *clientData = [array objectAtIndex:([array count] - 1 - i)];
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            if(clientData.platform)
            {
                [requestDictionary setObject:clientData.platform forKey:@"platform"];
            }
            if(clientData.os_version)
            {
                [requestDictionary setObject:clientData.os_version forKey:@"os_version"];
            }
            
            if(clientData.language)
            {
                [requestDictionary setObject:clientData.language forKey:@"language"];
            }
            
            if(clientData.resolution)
            {
                [requestDictionary setObject:clientData.resolution forKey:@"resolution"];
            }
            
            if(clientData.deviceid)
            {
                [requestDictionary setObject:clientData.deviceid forKey:@"deviceid"];
            }
            else
            {
                continue;
            }
            
            if(appKey)
            {
                [requestDictionary setObject:appKey forKey:@"appkey"];
            }
            else
            {
                continue;
            }
            
            [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
            if(clientData.sessionId)
            {
                [requestDictionary setObject:clientData.sessionId forKey:@"session_id"];
            }
            
            if(clientData.latitude)
            {
                [requestDictionary setObject:clientData.latitude forKey:@"latitude"];
            }
            
            if(clientData.longitude)
            {
                [requestDictionary setObject:clientData.longitude forKey:@"longitude"];
            }
            
            if(clientData.mccmnc!=nil)
            {
                [requestDictionary setObject:clientData.mccmnc forKey:@"mccmnc"];
            }
            else
            {
                [requestDictionary setObject:@"" forKey:@"mccmnc"];
                
            }
            
            if(clientData.version)
            {
                [requestDictionary setObject:clientData.version forKey:@"version"];
            }
            
            if(clientData.network)
            {
                [requestDictionary setObject:clientData.network forKey:@"network"];
            }
            
            if(clientData.devicename)
            {
                [requestDictionary setObject:clientData.devicename forKey:@"devicename"];
            }
            
            if(clientData.modulename)
            {
                [requestDictionary setObject:clientData.modulename forKey:@"modulename"];
            }
            
            if(clientData.time)
            {
                [requestDictionary setObject:clientData.time forKey:@"time"];
            }
            
            if(clientData.isjailbroken)
            {
                [requestDictionary setObject:clientData.isjailbroken forKey:@"isjailbroken"];
            }
            
            if(clientData.lib_version){
                [requestDictionary setObject:clientData.lib_version forKey:@"lib_version"];
            }
            
            [clientDataArray addObject:requestDictionary];
        }
    }
    return clientDataArray;
}


+ (void) postArchiveLogsByType:(NSMutableArray *) eventsArray activities:(NSMutableArray *) activityArray errors:(NSMutableArray *) errorArray clientdatas:(NSMutableArray *) clientdataArray tags:(NSMutableArray *) tagsArray appKey:(NSString *)appKey
{
    @autoreleasepool {
        
        if(eventsArray && [eventsArray count]>0)
            [EventDao postEventArray:eventsArray];
    }
    
    if(activityArray && [activityArray count])
    {
        [UsingLogDao postActivityArray:activityArray appKey:appKey];
    }
    
    if(errorArray && [errorArray count] > 0)
    {
        [ErrorDao postErrorArray:errorArray];
    }
    
    if(clientdataArray && [clientdataArray count])
    {
        [ClientDataDao postClientDataArray:clientdataArray];
    }
    
    if(tagsArray && [tagsArray count])
    {
        [TagDao postTagsArray:tagsArray];
    }
    
}

+ (void)postClientDataArray:(NSMutableArray*)clientdataArray
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/clientdata"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:clientdataArray forKey:@"data"];
    
    NSString *retString = [NetworkUtility postData:url data:finalDic];
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    if(!error)
    {
        ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
        ret.msg = [retDictionary objectForKey:@"msg"];
    }
    if(ret.flag > 0)
    {
        //        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"clientDataArray"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent removeArchivedFile:kClientDataArray];
    }
}
@end
