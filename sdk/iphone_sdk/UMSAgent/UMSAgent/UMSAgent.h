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
    REALTIME = 0,       //实时发送
    BATCH = 1,          //启动发送
} ReportPolicy;

@interface UMSAgent : NSObject<UIAlertViewDelegate>
{

}

+(void)checkUpdate;

+(void)startWithAppKey:(NSString*)appKey;

+(void)startWithAppKey:(NSString*)appKey ReportPolicy:(ReportPolicy)policy;

+(void)postEvent:(NSString *)event_id;

+(void)postEvent:(NSString *)event_id label:(NSString *)label;

+(void)postEvent:(NSString *)event_id acc:(NSInteger)acc;

+(void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc;

+(void)startTracPage:(NSString*)page_name;

+(void)endTracPage:(NSString*)page_name;

// 类方法，判断当前设备是否已经越狱
+ (BOOL)isJailbroken;
+ (void)setOnLineConfig:(BOOL)isOnlineConfig;
+ (void)setIsLogEnabled:(BOOL)isLogEnabled;


@end
