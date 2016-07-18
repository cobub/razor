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
 * @since		Version 1.0
 * @filesource
 */

#import "TagDao.h"
#import "Global.h"
#import "NSDictionary_JSONExtensions.h"
#import "NetworkUtility.h"
#import "UMSAgent.h"

@interface TagDao(){
    NSRecursiveLock *lock;
}
@property(nonatomic)NSRecursiveLock *lock;
@end


@implementation TagDao

#define kTagArray @"tagArray"
@synthesize lock;

+ (TagDao *)getinstance{
    static TagDao *instance = nil;
    if (instance == nil) {
        instance.lock = [[NSRecursiveLock alloc]init];
    }
    return instance;
}

+ (CommonReturn *)postTag:(NSString *)appkey tag:(Tag *)tag
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/tag"];
    
    CommonReturn *ret = [[CommonReturn alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    if(tag.deviceid)
    {
        [requestDictionary setObject:tag.deviceid forKey:@"deviceid"];
    }
    else
    {
        ret.flag = 1;
        ret.msg = @"discard dirty data";
        return ret;
    }
    
    if(tag.tag && ![tag.tag isEqualToString:@""])
    {
        [requestDictionary setObject:tag.tag forKey:@"tag"];
    }
    else
    {
        ret.flag = 1;
        ret.msg = @"discard dirty data";
        return ret;
    }
    if(tag.productkey && ![tag.productkey isEqualToString:@""])
    {
        [requestDictionary setObject:tag.productkey forKey:@"appkey"];
    }
    else
    {
        ret.flag = 1;
        ret.msg = @"discard dirty data";
        return ret;
    }
    
    if(tag.lib_version){
        [requestDictionary setObject:tag.lib_version forKey:@"lib_version"];
    }
    
    NSMutableArray *finalTagArray = [[NSMutableArray alloc] init];
    [finalTagArray addObject:requestDictionary];
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:finalTagArray forKey:@"data"];
    
    NSString *retString = [NetworkUtility postData:url data:finalDic];
    
    NSError* error = nil;
    NSDictionary * retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
    ret.msg = [retDictionary objectForKey:@"msg"];
    return ret;
}


+ (NSMutableArray *)getArchiveTag
{
    [[TagDao getinstance].lock lock];
    NSData *historyData = [UMSAgent getArchivedLogFromFile:kTagArray];
    [[TagDao getinstance].lock unlock];
    
    NSMutableArray * array = nil;
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"History  data num = %lu",(unsigned long)[array count]);
    }
    
    if (historyData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:historyData];
    }
    NSMutableArray *tagArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(int i=0;i<[array count];i++)
        {
            //            if(i>=DEFAULT_MAX_TAGS_COUNT)
            //            {
            //                break;
            //                //Discard old data;
            //            }
            Tag *mTag = [array objectAtIndex:([array count] - 1 - i)];
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            if(mTag.deviceid)
            {
                [requestDictionary setObject:mTag.deviceid forKey:@"deviceid"];
            }
            else
            {
                continue;
            }
            
            if(mTag.tag && ![mTag.tag isEqualToString:@""])
            {
                [requestDictionary setObject:mTag.tag forKey:@"tag"];
            }
            else
            {
                continue;
            }
            if(mTag.productkey && ![mTag.productkey isEqualToString:@""])
            {
                [requestDictionary setObject:mTag.productkey forKey:@"appkey"];
            }
            else
            {
                continue;
            }
            
            if(mTag.lib_version){
                [requestDictionary setObject:mTag.lib_version forKey:@"lib_version"];
            }
            
            [requestDictionary setObject:[UMSAgent getUserId] forKey:@"useridentifier"];
            [tagArray addObject:requestDictionary];
        }
    }
    return tagArray;
}

+ (void)postTagsArray:(NSMutableArray*)tagArray
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/tag"];
    CommonReturn *ret = [[CommonReturn alloc] init];
    
    NSMutableDictionary *finalDic = [[NSMutableDictionary alloc] init];
    [finalDic setObject:tagArray forKey:@"data"];
    
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
        //        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"tagArray"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent removeArchivedFile:kTagArray];
    }
}
@end
