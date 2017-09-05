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

@interface ErrorLog : NSObject<NSCoding>
{
    NSString *stackTrace;
    NSString *time;
    NSString *activity;
    NSString *appkey;
    NSString *osVersion;
    NSString *deviceID;
    NSString *version;
    NSString *uuID;
    NSString *cpt;//cpu type
    NSString *bim;//binary image
    NSString *lib_version;
    NSString *sessionID;
}

@property (nonatomic,strong) NSString *stackTrace;
@property (nonatomic,strong) NSString *time;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *appkey;
@property (nonatomic,strong) NSString *osVersion;
@property (nonatomic,strong) NSString *deviceID;
@property (nonatomic,strong) NSString *version;
@property (nonatomic,strong) NSString *uuID;
@property (nonatomic,strong) NSString *cpt;
@property (nonatomic,strong) NSString *bim;
@property (nonatomic,strong) NSString *lib_version;
@property (nonatomic,strong) NSString *sessionID;


@end
