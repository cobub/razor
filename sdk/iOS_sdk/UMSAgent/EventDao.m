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

#import "EventDao.h"
#import "Global.h"
#import "NSDictionary_JSONExtensions.h"
#import "NetworkUtility.h"
#import "UMSAgent.h"

@interface EventDao(){
    NSRecursiveLock *lock;
}
@property(nonatomic)NSRecursiveLock *lock;

@end

@implementation EventDao
#define kEventArray @"eventArray"
@synthesize lock;

+ (EventDao *)getinstance{
    static EventDao *instance = nil;
    if (instance == nil) {
        instance.lock = [[NSRecursiveLock alloc]init];
    }
    
    return instance;
}

+ (CommonReturn *)postEvent:(NSString *)appkey event:(Event *)mEvent
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/eventlog"];
    
    CommonReturn *ret = [[CommonReturn alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    if(mEvent.event_id)
    {
        [requestDictionary setObject:mEvent.event_id forKey:@"event_identifier"];
    }
    else
    {
        ret.flag = 1;
        ret.msg = @"discard dirty data";
        return ret;
    }
    if(mEvent.time)
    {
        [requestDictionary setObject:mEvent.time forKey:@"time"];
    }
    if(mEvent.activity)
    {
        [requestDictionary setObject:mEvent.activity forKey:@"activity"];
    }
    if(mEvent.label)
    {
        [requestDictionary setObject:mEvent.label forKey:@"label"];
    }
    if(mEvent.version)
    {
        [requestDictionary setObject:mEvent.version forKey:@"version"];
    }
    if(mEvent.lib_version){
        [requestDictionary setObject:mEvent.lib_version forKey:@"lib_version"];
    }
    
    if(mEvent.acc)
    {
        [requestDictionary setObject:[NSNumber numberWithInt:mEvent.acc] forKey:@"acc"];
    }
    [requestDictionary setObject:[UMSAgent getUMSUDID] forKey:@"deviceid"];
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
    //Parsing json Str
    if(![mEvent.jsonstr isEqualToString:@""])
    {
        NSError *evError = nil;
        NSDictionary *evDictionary = [NSDictionary dictionaryWithJSONString:mEvent.jsonstr error:&evError];
        if(!evError)
        {
            for(id key in evDictionary)
            {
                [requestDictionary setObject:[evDictionary objectForKey:key] forKey:[NSString stringWithFormat:@"V_%@",key]];
            }
        }
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

+ (NSMutableArray *)getArchiveEvent:(NSString*)appKey
{
    [[EventDao getinstance].lock lock];
    NSData *historyData = [UMSAgent getArchivedLogFromFile:kEventArray];
    [[EventDao getinstance].lock unlock];
    
    NSMutableArray * array = nil;
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"old  data num = %lu",(unsigned long)[array count]);
    }
    
    if (historyData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:historyData];
    }
    NSMutableArray *eventArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(int i=0;i<[array count];i++)
        {
            //            if(i>=DEFAULT_MAX_EVENT_COUNT)
            //            {
            //                break;
            //                //Discard old data;
            //            }
            Event *mEvent = [array objectAtIndex:([array count] - 1 - i)];
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            if(mEvent.event_id)
            {
                [requestDictionary setObject:mEvent.event_id forKey:@"event_identifier"];
            }
            else
            {
                continue;
            }
            if(mEvent.time)
            {
                [requestDictionary setObject:mEvent.time forKey:@"time"];
            }
            if(mEvent.activity)
            {
                [requestDictionary setObject:mEvent.activity forKey:@"activity"];
            }
            [requestDictionary setObject:[UMSAgent getUMSUDID] forKey:@"deviceid"];
            if(mEvent.label)
            {
                [requestDictionary setObject:mEvent.label forKey:@"label"];
            }
            if(mEvent.acc)
            {
                [requestDictionary setObject:[NSNumber numberWithInt:mEvent.acc] forKey:@"acc"];
            }
            if(appKey)
            {
                [requestDictionary setObject:appKey forKey:@"appkey"];
            }
            else
            {
                continue;
            }
            if(mEvent.version)
            {
                [requestDictionary setObject:mEvent.version forKey:@"version"];
            }
            if(mEvent.lib_version){
                [requestDictionary setObject:mEvent.lib_version forKey:@"lib_version"];
            }
            
            [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
            //Parsing json Str
            if(![mEvent.jsonstr isEqualToString:@""])
            {
                NSError *evError = nil;
                NSDictionary *evDictionary = [NSDictionary dictionaryWithJSONString:mEvent.jsonstr error:&evError];
                if(!evError)
                {
                    for(id key in evDictionary)
                    {
                        [requestDictionary setObject:[evDictionary objectForKey:key] forKey:[NSString stringWithFormat:@"V_%@",key]];
                    }
                }
            }
            
            [eventArray addObject:requestDictionary];
        }
    }
    return eventArray;
}

+ (void)postEventArray:(NSMutableArray*)eventArray
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/eventlog"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:eventArray forKey:@"data"];
    
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
        //        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"eventArray"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent removeArchivedFile:kEventArray];
    }
}

@end
