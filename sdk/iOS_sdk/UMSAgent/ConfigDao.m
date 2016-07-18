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

#import "ConfigDao.h"
#import "Global.h"
#import "NetworkUtility.h"
#import "NSDictionary_JSONExtensions.h"

@implementation ConfigDao
+ (ConfigPreference *) getOnlineConfig:(NSString *)appkey
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getConfigBaseUrl],@"/pushpolicyquery"];
    
    ConfigPreference *ret = [[ConfigPreference alloc] init];
    //Set default preference
    ret.autogetlocation =  [Global getConfigParams:@"autoGetLocation"];
    ret.reportpolicy = [Global getConfigParams:@"reportPolicy"];
    ret.Updateonlywifi = [Global getConfigParams:@"updateOnlyWifi"];
    ret.sessionmillis = [Global getConfigParams:@"sessionMillis"];
    ret.sendInterval = [Global getConfigParams:@"intervalTime"];
    ret.maxFileSize = [Global getConfigParams:@"fileSize"];
    
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    
    [requestDictionary setObject:appkey forKey:@"appKey"];
    
    NSString *retString = [NetworkUtility postData:url data:requestDictionary];
    
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    if(!error)
    {
        NSDictionary *replyDic = [retDictionary objectForKey:@"reply"];
        if([replyDic.allKeys containsObject:@"autoGetLocation"])
        {
            ret.autogetlocation = [[replyDic objectForKey:@"autoGetLocation"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"autoGetLocation"] key:@"autoGetLocation"];
        }
        if([replyDic.allKeys containsObject:@"reportPolicy"])
        {
            
            ret.reportpolicy = [[replyDic objectForKey:@"reportPolicy"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"reportPolicy"] key:@"reportPolicy"];
        }
        if([replyDic.allKeys containsObject:@"updateOnlyWifi"])
        {
            ret.Updateonlywifi = [[replyDic objectForKey:@"updateOnlyWifi"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"updateOnlyWifi"] key:@"updateOnlyWifi"];
        }
        if([replyDic.allKeys containsObject:@"sessionMillis"])
        {
            ret.sessionmillis = [[replyDic objectForKey:@"sessionMillis"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"sessionMillis"] key:@"sessionMillis"];
        }
        if([replyDic.allKeys containsObject:@"intervalTime"])
        {
            ret.sendInterval = [[replyDic objectForKey:@"intervalTime"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"intervalTime"] key:@"intervalTime"];
        }
        if([replyDic.allKeys containsObject:@"fileSize"])
        {
            ret.maxFileSize = [[replyDic objectForKey:@"fileSize"] intValue];
            [Global setConfigParams:[replyDic objectForKey:@"fileSize"] key:@"fileSize"];
        }
    }
    return ret;
}

+ (void)getCustomParams:(NSString*)appkey
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getConfigBaseUrl],@"/getAllparameters"];
    
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:appkey forKey:@"appKey"];
    
    NSString *retString = [NetworkUtility postData:url data:requestDictionary];
    
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    if(!error)
    {
        NSDictionary *replyDic = [retDictionary objectForKey:@"reply"];
        if([replyDic.allKeys containsObject:@"parameters"])
        {
            NSArray *paramsDic = [replyDic objectForKey:@"parameters"];
            if(paramsDic)
            {
                for (NSDictionary *paramObj in paramsDic)
                {
                    NSString *paramKey = [paramObj objectForKey:@"key"];
                    NSString *paramVal = [paramObj objectForKey:@"value"];
                    [[NSUserDefaults standardUserDefaults] setObject:paramVal forKey:[NSString stringWithFormat:@"cus_%@",paramKey]];
                }
                [[NSUserDefaults standardUserDefaults] synchronize];
                
            }
        }
    }
}
@end
