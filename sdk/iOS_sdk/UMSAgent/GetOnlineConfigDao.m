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

#import "GetOnlineConfigDao.h"
#import "Global.h"
#import "network.h"
#import "NSDictionary_JSONExtensions.h"

@implementation GetOnlineConfigDao
+(ConfigPreference *) getOnlineConfig:(NSString *)appkey
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/getOnlineConfiguration"];
    
    ConfigPreference *ret = [[ConfigPreference alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    
    [requestDictionary setObject:appkey forKey:@"appkey"];
    
    NSString *retString = [network SendData:url data:requestDictionary];
    
    NSError *error = nil;
    NSDictionary *retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    if(!error)
    {
        ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
        ret.msg = [retDictionary objectForKey:@"msg"];
        ret.autogetlocation = [retDictionary objectForKey:@"autogetlocation"];
        ret.Updateonlywifi = [retDictionary objectForKey:@"updateonlywifi"];
        ret.sessionmillis = [retDictionary objectForKey:@"sessionmillis"];
        ret.reportpolicy = [retDictionary objectForKey:@"reportpolicy"];
    }
    return ret;


}
@end
