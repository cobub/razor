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

@interface ClientData : NSObject<NSCoding>
{
    NSString *platform;
    NSString *os_version;
    NSString *language;
    NSString *resolution;
    NSString *deviceid;
    NSString *mccmnc;
    NSString *version;
    NSString *network;
    NSString *devicename;
    NSString *modulename;
    NSString *time;
    NSString *isjailbroken;
    NSString *userid;
}

@property (nonatomic,strong) NSString *platform;
@property (nonatomic,strong) NSString *os_version;
@property (nonatomic,strong) NSString *language;
@property (nonatomic,strong) NSString *resolution;
@property (nonatomic,strong) NSString *deviceid;
@property (nonatomic,strong) NSString *mccmnc;
@property (nonatomic,strong) NSString *version;
@property (nonatomic,strong) NSString *network;
@property (nonatomic,strong) NSString *devicename;
@property (nonatomic,strong) NSString *modulename;
@property (nonatomic,strong) NSString *time;
@property (nonatomic,strong) NSString *isjailbroken;
@property (nonatomic,strong) NSString *userid;

@end
