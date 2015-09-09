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
#import <UIKit/UIKit.h>

typedef enum {
    REALTIME = 0,       //RealTime Send Policy
    BATCH = 1,          //Send Data When Start
} ReportPolicy;

@interface UMSAgent : NSObject<UIAlertViewDelegate>
{

}

+(void)checkUpdate;

+(void)startWithAppKey:(NSString*)appKey ServerURL:(NSString *)serverURL;

+(void)startWithAppKey:(NSString*)appKey ReportPolicy:(ReportPolicy)policy ServerURL:(NSString*)serverURL
;
+(void)postEvent:(NSString *)event_id;

+(void)postEvent:(NSString *)event_id label:(NSString *)label;

+(void)postEvent:(NSString *)event_id acc:(NSInteger)acc;

+(void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc;

+(void)postTag:(NSString *)tag;

+(void)bindUserid:(NSString *)userid;

+(void)startTracPage:(NSString*)page_name;

+(void)endTracPage:(NSString*)page_name;

+(void)tracePage:(NSString*)page_name;

+(void)postPushid:(NSString*)pushid;

+ (void)setOnLineConfig:(BOOL)isOnlineConfig;
+ (void)setIsLogEnabled:(BOOL)isLogEnabled;
+ (NSString*)getUMSUDID;
+(NSString*)getUserId;
@end
