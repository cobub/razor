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

#import <Foundation/Foundation.h>
#import "Tag.h"
#import "CommonReturn.h"

@interface TagDao : NSObject
+ (CommonReturn *) postTag:(NSString *) appkey tag:(Tag *) tag;
+ (NSMutableArray *)getArchiveTag;
+ (void)postTagsArray:(NSMutableArray*)tagArray;
@end
