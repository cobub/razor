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

@interface ActivityLog : NSObject<NSCoding>
{
    NSString *sessionMils;
    NSString *startMils;
    NSString *endMils;
    NSString *duration;
    NSString *activity;
    NSString *version;
}

@property (nonatomic,strong) NSString *sessionMils;
@property (nonatomic,strong) NSString *startMils;
@property (nonatomic,strong) NSString *endMils;
@property (nonatomic,strong) NSString *duration;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *version;

@end
