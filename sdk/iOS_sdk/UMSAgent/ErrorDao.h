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

#import "CommonReturn.h"
#import "ErrorLog.h"

@interface ErrorDao : NSObject

+ (CommonReturn *) postErrorLog:(NSString *) appkey errorLog:(ErrorLog *) errorLog;
+ (NSMutableArray *)getArchiveErrorLog;
+ (void)postErrorArray:(NSMutableArray*)errorArray;

@end
