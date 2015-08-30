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
#import "network.h"
#import "NSDictionary_JSONExtensions.h"
#import "Global.h"
#import "UMSAgent.h"

@implementation CheckUpdateDao

+(CheckUpdateReturn *)checkUpdate:(NSString *)appkey version:(NSString *)version_code
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/getApplicationUpdate"];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:@"1.0" forKey:@"version_code"];
    [requestDictionary setObject:appkey forKey:@"appkey"];
    NSString *ret = [network SendData:url data:requestDictionary];
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
        result.flag = [[retDictionary objectForKey:@"flag"] intValue];
        result.msg = [retDictionary objectForKey:@"msg"];
        result.description = [retDictionary objectForKey:@"description"];
        result.version = [retDictionary objectForKey:@"version"];
        result.fileurl = [retDictionary objectForKey:@"fileurl"];
        result.forceUpdate = [retDictionary objectForKey:@"forceupdate"];
        result.time= [retDictionary objectForKey:@"time"];
    }
    return result;
    
}

@end
