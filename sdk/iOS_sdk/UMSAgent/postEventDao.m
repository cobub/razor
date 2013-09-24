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

#import "postEventDao.h"
#import "Global.h"
#import "NSDictionary_JSONExtensions.h"
#import "network.h"

@implementation postEventDao

+(CommonReturn *)postEvent:(NSString *)appkey event:(Event *)mEvent
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/postEvent"];
    
    CommonReturn *ret = [[CommonReturn alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:mEvent.event_id forKey:@"event_identifier"];
    [requestDictionary setObject:mEvent.time forKey:@"time"];
    [requestDictionary setObject:mEvent.activity forKey:@"activity"];
    [requestDictionary setObject:mEvent.label forKey:@"label"];
    [requestDictionary setObject:mEvent.version forKey:@"version"];
    [requestDictionary setObject:[NSNumber numberWithInt:mEvent.acc] forKey:@"acc"];
    [requestDictionary setObject:appkey forKey:@"appkey"];
    
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

@end
