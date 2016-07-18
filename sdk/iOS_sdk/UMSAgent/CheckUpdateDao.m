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

#import "CheckUpdateDao.h"
#import "NetworkUtility.h"
#import "NSDictionary_JSONExtensions.h"
#import "Global.h"
#import "UMSAgent.h"

@implementation CheckUpdateDao

+ (CheckUpdateReturn *)checkUpdate:(NSString *)appkey version:(NSString *)version_code lib_version:(NSString *)lib_version_code
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getConfigBaseUrl],@"/appupdate"];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:version_code forKey:@"versionCode"];
    [requestDictionary setObject:appkey forKey:@"appKey"];
    [requestDictionary setObject:lib_version_code forKey:@"lib_version"];
    
    NSString *ret = [NetworkUtility postData:url data:requestDictionary];
    CheckUpdateReturn *result = [[CheckUpdateReturn alloc] init];
    
    if (ret==nil) {
        result.flag = -4;
        result.msg = [[NSString alloc] initWithFormat:@"%@",@"error"];
        return result;
    }
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:ret error:&error];
    if(!error)
    {
        NSDictionary *replyDic = [retDictionary objectForKey:@"reply"];
        result.flag = [[replyDic objectForKey:@"flag"] intValue];
        if(result.flag == 1)
        {
            result.description = [replyDic objectForKey:@"description"];
            result.version = [replyDic objectForKey:@"versionName"];
            result.fileurl = [replyDic objectForKey:@"fileUrl"];
        }
    }
    return result;
    
}

@end
