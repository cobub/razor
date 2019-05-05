//
//  ErrorDao.m
//  UMSAgent
//
//  Created by tim on 14/12/5.
//
//
#include "Global.h"
#include "NetworkUtility.h"
#import "NSDictionary_JSONExtensions.h"
#import "ClientData.h"
#import "ActivityLog.h"
#import "UMSAgent.h"
#import "ErrorDao.h"

@interface ErrorDao(){
    NSRecursiveLock *lock;
}
@property(nonatomic)NSRecursiveLock *lock;
@end

@implementation ErrorDao
#define kErrorLog @"errorLog"
@synthesize lock;


+ (ErrorDao *)getinstance{
    static ErrorDao *instance = nil;
    
    if (instance == nil) {
        instance.lock = [[NSRecursiveLock alloc]init];
    }
    return instance;
}

+ (CommonReturn *) postErrorLog:(NSString *) appkey errorLog:(ErrorLog *) errorLog
{
    @autoreleasepool {
        NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/errorlog"];
        CommonReturn *ret = [[CommonReturn alloc] init];
        NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
        if(errorLog.time)
        {
            [requestDictionary setObject:errorLog.time forKey:@"time"];
        }
        if(errorLog.stackTrace)
        {
            [requestDictionary setObject:errorLog.stackTrace forKey:@"stacktrace"];
        }
        else
        {
            ret.flag = 1;
            ret.msg = @"discard dirty data";
            return ret;
        }
        
        if(errorLog.version)
        {
            [requestDictionary setObject:errorLog.version forKey:@"version"];
        }
        if(errorLog.sessionID)
        {
            [requestDictionary setObject:errorLog.sessionID forKey:@"session_id"];
        }
        if(errorLog.osVersion)
        {
            [requestDictionary setObject:errorLog.osVersion forKey:@"os_version"];
        }
        
        [requestDictionary setObject:[UMSAgent getUMSUDID] forKey:@"deviceid"];
        if([[UMSAgent getInstance]machineName])
        {
            [requestDictionary setObject:[[UMSAgent getInstance]machineName] forKey:@"devicename"];
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
        if(errorLog.activity)
        {
            [requestDictionary setObject:errorLog.activity forKey:@"activity"];
        }
        if(errorLog.uuID)
        {
            [requestDictionary setObject:errorLog.uuID forKey:@"dsymid"];
        }
        
        if(errorLog.cpt)
        {
            [requestDictionary setObject:errorLog.cpt forKey:@"cpt"];
        }
        
        if(errorLog.bim){
            [requestDictionary setObject:errorLog.bim forKey:@"bim"];
        }

        if(errorLog.lib_version){
            [requestDictionary setObject:errorLog.lib_version forKey:@"lib_version"];
        }
        
        NSMutableArray *mArray = [[NSMutableArray alloc] init];
        [mArray addObject:requestDictionary];
        
        NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
        [finalDic setObject:mArray forKey:@"data"];
        
        NSString *retString = [NetworkUtility postData:url data:finalDic];
        NSError *error = nil;
        [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
        NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
        if(!error)
        {
            ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
            ret.msg = [retDictionary objectForKey:@"msg"];
        }
        return ret;
    }
}

#pragma mark - error log
+ (NSMutableArray *)getArchiveErrorLog
{
    [[ErrorDao getinstance].lock lock];
    NSData *historyData = [UMSAgent getArchivedLogFromFile:kErrorLog];
    [[ErrorDao getinstance].lock unlock];
    
    NSMutableArray * array = nil;
    if (historyData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:historyData];
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"History error data num = %lu",(unsigned long)[array count]);
        }
    }
    NSMutableArray *errorLogArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(int i=0;i<[array count];i++)
        {
            //            if(i>=DEFAULT_MAX_ERROR_COUNT)
            //            {
            //                break;
            //                //Discard old data;
            //            }
            ErrorLog *errorLog = [array objectAtIndex:([array count] - 1 - i)];
            
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            if(errorLog.time)
            {
                [requestDictionary setObject:errorLog.time forKey:@"time"];
            }
            if(errorLog.stackTrace)
            {
                [requestDictionary setObject:errorLog.stackTrace forKey:@"stacktrace"];
            }
            else
            {
                continue;
            }
            if(errorLog.version)
            {
                [requestDictionary setObject:errorLog.version forKey:@"version"];
            }
            if(errorLog.version)
            {
                [requestDictionary setObject:errorLog.version forKey:@"version"];
            }
            if(errorLog.osVersion)
            {
                [requestDictionary setObject:errorLog.osVersion forKey:@"os_version"];
            }
            
            
            [requestDictionary setObject:[UMSAgent getUMSUDID] forKey:@"deviceid"];
            
            if([[UMSAgent getInstance]machineName])
            {
                [requestDictionary setObject:[[UMSAgent getInstance]machineName] forKey:@"devicename"];
            }
            if(errorLog.appkey)
            {
                [requestDictionary setObject:errorLog.appkey forKey:@"appkey"];
            }
            else
            {
                continue;
            }
            if(errorLog.activity)
            {
                [requestDictionary setObject:errorLog.activity forKey:@"activity"];
            }
            if(errorLog.uuID)
            {
                [requestDictionary setObject:errorLog.uuID forKey:@"dsymid"];
            }
            
            if(errorLog.cpt)
            {
                [requestDictionary setObject:errorLog.cpt forKey:@"cpt"];
            }
            
            if(errorLog.bim){
                [requestDictionary setObject:errorLog.bim forKey:@"bim"];
            }

            if(errorLog.lib_version){
                [requestDictionary setObject:errorLog.lib_version forKey:@"lib_version"];
            }
            [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
            [errorLogArray addObject:requestDictionary];
        }
    }
    return errorLogArray;
}


+ (void)postErrorArray:(NSMutableArray*)errorArray
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/errorlog"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:errorArray forKey:@"data"];
    
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
        //        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"errorLog"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent removeArchivedFile:kErrorLog];
    }
}


@end
