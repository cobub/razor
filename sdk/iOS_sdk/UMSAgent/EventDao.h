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

#import <Foundation/Foundation.h>
#import "Event.h"
#import "CommonReturn.h"

@interface EventDao : NSObject
{
    
}
+ (CommonReturn *) postEvent:(NSString *) appkey event:(Event *) mEvent;
+ (NSMutableArray *)getArchiveEvent:(NSString*)appKey;
+ (void)postEventArray:(NSMutableArray*)eventArray;

@end
