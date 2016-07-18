//
//  UsingLogDao.m
//  UMSAgentExample
//
//  Created by tim on 14/12/11.
//
//

#import "UsingLogDao.h"
#import "UMSAgent.h"
#import "ActivityLog.h"
#import "Global.h"
#import "CommonReturn.h"
#import "NetworkUtility.h"
#import "NSDictionary_JSONExtensions.h"

@interface UsingLogDao(){
    NSRecursiveLock *lock;
    
}
@property(nonatomic)NSRecursiveLock *lock;
@end


@implementation UsingLogDao
#define kActivityLog @"activityLog"
@synthesize lock;

+ (UsingLogDao *)getinstance{
    static UsingLogDao *instance = nil;
    if (instance == nil) {
        instance.lock = [[NSRecursiveLock alloc]init];
    }
    return instance;
}

+ (NSMutableArray *)getArchiveActivityLog
{
    
    [[UsingLogDao getinstance].lock lock];
    NSData *historyData = [UMSAgent getArchivedLogFromFile:kActivityLog];
    [[UsingLogDao getinstance].lock unlock];
    
    NSMutableArray * array = nil;
    if (historyData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:historyData];
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Have activity data num = %lu",(unsigned long)[array count]);
        }
    }
    return array;
}


+ (void)postActivityArray:(NSMutableArray*)activityArray appKey:(NSString*)appKey
{
    //NSString *sessionID = @"";
    NSMutableArray *finalAcvitityArray = [[NSMutableArray alloc] init];
    if ([activityArray count]>0)
    {
        NSMutableDictionary *requestDictionary = nil;
        //NSString *activities = @"";
        //NSString *sd = @"";
        //float duration = 0.0;
        //int arrayCounter = 0;
        for(ActivityLog *mLog in activityArray)
        {
            //arrayCounter ++;
            //            if(![mLog.sessionID isEqualToString:sessionID] || arrayCounter > [activityArray count])
            //            {
            if([UMSAgent getInstance].isLogEnabled)
            {
                NSLog(@"Session MIlls = %@",mLog.sessionID);
            }
            
            
            requestDictionary = [[NSMutableDictionary alloc] init];
            
            // sessionID = mLog.sessionID;
            
            
            if(mLog.sessionID)
            {
                [requestDictionary setObject:mLog.sessionID forKey:@"session_id"];
            }
            else
            {
                continue;
            }
            
            if(mLog.startMils)
            {
                [requestDictionary setObject:mLog.startMils forKey:@"start_millis"];
            }
            else
            {
                continue;
            }
            
            if(appKey && ![appKey isEqualToString:@""])
            {
                [requestDictionary setObject:appKey forKey:@"appkey"];
            }
            else
            {
                continue;
            }
            
            [requestDictionary setObject:[UMSAgent getUMSUDID] forKey:@"deviceid"];
            [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
            if(mLog.version)
            {
                [requestDictionary setObject:mLog.version forKey:@"version"];
            }
            
            if(mLog.lib_version){
                [requestDictionary setObject:mLog.lib_version forKey:@"lib_version"];
            }
            
            //activities = @"";
            
            //duration = 0.0;
            //}
            
            if(mLog.endMils)
            {
                [requestDictionary setObject:mLog.endMils forKey:@"end_millis"];
            }
            else
            {
                continue;
            }
            
            if(mLog.activity && ![mLog.activity isEqualToString:@""])
            {
                [requestDictionary setObject:mLog.activity forKey:@"activities"];
            }
            else
            {
                continue;
            }
            
            if([mLog.duration intValue] > 0){
                
                [requestDictionary setObject:[NSString stringWithFormat:@"%d",[mLog.duration intValue]] forKey:@"duration"];
            }
            [finalAcvitityArray addObject:requestDictionary];
        }
        
        
    }
    
    
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"Post ACtivity Array");
    }
    
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/usinglog"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:finalAcvitityArray forKey:@"data"];
    
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
        //        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"activityLog"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        
        
        
        [UMSAgent removeArchivedFile:kActivityLog];
    }
}


@end
