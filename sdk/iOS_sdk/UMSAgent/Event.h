//
//  Event.h
//  UMSAgent
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

@interface Event : NSObject<NSCoding>
{
    NSString *event_id;
    NSString *time;
    NSString *activity;
    NSString *label;
    int acc;
    NSString *version;
}

@property (nonatomic,strong) NSString *event_id;
@property (nonatomic,strong) NSString *time;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *label;
@property (nonatomic) int acc;
@property (nonatomic,strong) NSString *version;

@end
