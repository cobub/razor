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
#import "ErrorLog.h"
#import "ClientData.h"

@interface PostClientDataDao : NSObject
{}

+(CommonReturn *) postClient:(NSString *) appkey deviceInfo:(ClientData *) deviceInfo;

+(CommonReturn *) postUsingTime:(NSString *) appkey sessionMills:(NSString *)sessionMills startMils:(NSString*)startMils endMils:(NSString*)endMils duration:(NSString*)duration activity:(NSString *) activity version:(NSString *) version;

+(CommonReturn *) postArchiveLogs:(NSMutableDictionary *) archiveLogs;

+(CommonReturn *) postErrorLog:(NSString *) appkey errorLog:(ErrorLog *) errorLog;
@end
