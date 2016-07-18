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
#import "CommonReturn.h"
#import "ClientData.h"

@interface ClientDataDao : NSObject
{}

+ (CommonReturn *) postClient:(NSString *) appkey deviceInfo:(ClientData *) deviceInfo;

+ (void) postArchiveLogsByType:(NSMutableArray *) eventsArray activities:(NSMutableArray *) activityArray errors:(NSMutableArray *) errorArray clientdatas:(NSMutableArray *) clientdataArray tags:(NSMutableArray *) tagsArray appKey:(NSString *)appKey;

+ (void)postClientDataArray:(NSMutableArray*)clientdataArray;

+ (NSMutableArray *)getArchiveClientData:(NSString*)appKey;

@end
