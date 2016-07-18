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
#define DEFAUT_POLICY          1
#define DEFAULT_SESSIONMILLIS 30
#define DEFAULT_UPDATE_ONLY_WIFI 1
#define DEFAULT_ENABLE_CRASH_REPORT YES
#define DEFAULT_ENABLE_LOG NO
#define DEFAULT_INTERVAL_TIME 5
#define DEFAULT_FILE_SIZE 1

#define DEFAULT_MAX_CLIENTDATA_COUNT 50
#define DEFAULT_MAX_EVENT_COUNT 400
#define DEFAULT_MAX_ERROR_COUNT 20
#define DEFAULT_MAX_USINGLOG_COUNT 50
#define DEFAULT_MAX_TAGS_COUNT 100

@interface Global : NSObject
{
    
}

+ (void)ShowAlertView:(NSString*)title message:(NSString*)message delegate:(id)delegate buttonTitle:(NSString*)buttonTitle cancelButtonTitle:(NSString*)title;
+ (void)setBaseURL:(NSString *)baseURL;
+ (NSString*)getBaseURL;
+ (NSString *)getConfigBaseUrl;
+ (void)setConfigParams:(NSString*)value key:(NSString*)key;
+ (int)getConfigParams:(NSString*)key;

@end
